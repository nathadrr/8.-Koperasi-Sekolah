<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionExportController extends Controller
{
    public function __invoke(Request $request, string $format): BinaryFileResponse
    {
        abort_unless(in_array($format, ['csv', 'xlsx'], true), 404);

        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['pending', 'paid', 'completed', 'cancelled'])],
            'date' => ['nullable', 'date'],
        ]);

        $transactions = Transaction::query()
            ->with(['user', 'product', 'details.product', 'paymentAttempts'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('created_at', $date))
            ->latest()
            ->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Transaksi');
        $sheet->fromArray([
            'Kode Transaksi',
            'Tanggal',
            'Nama Pembeli',
            'Kelas/Keterangan',
            'Produk',
            'Jumlah',
            'Total Harga',
            'Status',
            'Metode Pembayaran',
            'Referensi Midtrans',
        ], null, 'A1');
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:J1');

        foreach ($transactions as $index => $transaction) {
            $row = $index + 2;
            $details = $transaction->details;
            $items = $details->isNotEmpty()
                ? $details->map(fn ($detail) => ($detail->product?->name ?? 'Produk Dihapus').' ('.$detail->quantity.')')->implode('; ')
                : ($transaction->product?->name ?? 'Produk Dihapus');
            $quantity = $details->isNotEmpty()
                ? $details->sum('quantity')
                : $transaction->quantity;
            $latestAttempt = $transaction->paymentAttempts->sortByDesc('id')->first();

            $textValues = [
                'A' => $transaction->transaction_code,
                'B' => $transaction->created_at->format('Y-m-d H:i:s'),
                'C' => $transaction->buyer_name ?? $transaction->user?->name ?? 'Pembeli',
                'D' => $transaction->buyer_class ?? 'Umum',
                'E' => $items,
                'H' => match ($transaction->status) {
                    'pending' => 'Menunggu Pembayaran',
                    'paid' => 'Lunas',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $transaction->status,
                },
                'I' => $transaction->payment_method === 'midtrans' ? 'Midtrans' : 'Tunai',
                'J' => $latestAttempt?->order_id ?? (
                    $transaction->payment_method === 'midtrans' ? $transaction->transaction_code : ''
                ),
            ];

            foreach ($textValues as $column => $value) {
                $sheet->setCellValueExplicit(
                    "{$column}{$row}",
                    $this->safeText($value, $format),
                    DataType::TYPE_STRING
                );
            }

            $sheet->setCellValue("F{$row}", $quantity);
            $sheet->setCellValue("G{$row}", (float) $transaction->total_price);
        }

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'koperasi-transactions-');
        if ($temporaryPath === false) {
            throw new \RuntimeException('Unable to create a temporary transaction export file.');
        }

        try {
            if ($format === 'csv') {
                $writer = new Csv($spreadsheet);
                $writer->setDelimiter(',');
                $writer->setEnclosure('"');
                $writer->setLineEnding("\r\n");
                $writer->setUseBOM(true);
            } else {
                $writer = new Xlsx($spreadsheet);
            }

            $writer->save($temporaryPath);
        } catch (\Throwable $exception) {
            @unlink($temporaryPath);
            throw $exception;
        } finally {
            $spreadsheet->disconnectWorksheets();
        }

        $extension = $format === 'xlsx'
            ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            : 'text/csv; charset=UTF-8';
        $filename = 'transaksi-'.now()->format('Ymd-His').'.'.$format;

        return response()->download($temporaryPath, $filename, [
            'Content-Type' => $extension,
        ])->deleteFileAfterSend(true);
    }

    private function safeText(string $value, string $format): string
    {
        if ($format === 'csv' && preg_match('/^\s*[=+\-@]/u', $value) === 1) {
            return "'".$value;
        }

        return $value;
    }
}
