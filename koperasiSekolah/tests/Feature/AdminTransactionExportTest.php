<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class AdminTransactionExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_filtered_transactions_as_csv(): void
    {
        [$admin, $product] = $this->createExportData();
        $transaction = $this->createTransaction($admin, $product, 'pending');
        $this->createTransaction($admin, $product, 'paid', 'TRX-EXPORT-PAID');

        $response = $this->actingAs($admin)->get(route('admin.reports.export', [
            'format' => 'csv',
            'status' => 'pending',
        ]));

        $response->assertOk()->assertDownload();
        $path = $response->baseResponse->getFile()->getPathname();

        try {
            $contents = file_get_contents($path);
            $this->assertIsString($contents);
            $this->assertStringContainsString('Kode Transaksi', $contents);
            $this->assertStringContainsString($transaction->transaction_code, $contents);
            $this->assertStringContainsString('Buku Tulis', $contents);
            $this->assertStringNotContainsString('TRX-EXPORT-PAID', $contents);
        } finally {
            @unlink($path);
        }
    }

    public function test_admin_can_export_transactions_as_a_readable_xlsx_file(): void
    {
        [$admin, $product] = $this->createExportData();
        $transaction = $this->createTransaction($admin, $product, 'paid');

        $response = $this->actingAs($admin)->get(route('admin.reports.export', ['format' => 'xlsx']));

        $response->assertOk()->assertDownload();
        $path = $response->baseResponse->getFile()->getPathname();
        $spreadsheet = null;

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();

            $this->assertSame('Kode Transaksi', $sheet->getCell('A1')->getValue());
            $this->assertSame($transaction->transaction_code, $sheet->getCell('A2')->getValue());
            $this->assertSame('Buku Tulis', $sheet->getCell('E2')->getValue());
            $this->assertSame('Lunas', $sheet->getCell('H2')->getValue());
            $this->assertSame(1, (int) $sheet->getCell('F2')->getValue());
        } finally {
            $spreadsheet?->disconnectWorksheets();
            @unlink($path);
        }
    }

    public function test_transaction_export_rejects_unsupported_formats(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin/reports/export/pdf')
            ->assertNotFound();
    }

    private function createExportData(): array
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Alat Tulis']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Buku Tulis',
            'price' => 5000,
            'stock' => 10,
        ]);

        return [$admin, $product];
    }

    private function createTransaction(
        User $admin,
        Product $product,
        string $status,
        string $code = 'TRX-EXPORT-001'
    ): Transaction {
        return Transaction::create([
            'transaction_code' => $code,
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'total_price' => 5000,
            'status' => $status,
            'payment_method' => 'midtrans',
            'buyer_name' => $admin->name,
        ]);
    }
}
