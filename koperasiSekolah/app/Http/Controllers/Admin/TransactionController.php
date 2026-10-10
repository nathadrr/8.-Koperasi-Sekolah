<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // Proses Checkout oleh User / Siswa
    public function checkout(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Stok barang tidak mencukupi.');
        }

        $user = $request->user();
        $totalPrice = $product->price * $quantity;
        $transactionCode = 'TRX-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

        Transaction::create([
            'transaction_code' => $transactionCode,
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'payment_method' => 'cash',
            'buyer_name' => $user->name,
            'buyer_class' => $user->role === 'student' ? 'Siswa St. Agnes' : 'Orang Tua / Umum',
        ]);

        // Kurangi stok barang saat pemesanan
        $product->decrement('stock', $quantity);

        return redirect()->route('explore')->with('success', 'Pesanan berhasil dibuat dengan kode: ' . $transactionCode . '. Silakan lakukan pembayaran di koperasi sekolah.');
    }

    // Menampilkan Laporan Penjualan & Data Transaksi di Panel Admin
    public function report(Request $request)
    {
        $query = Transaction::with(['user', 'product'])->latest();

        // Filter berdasarkan Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Tanggal
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->paginate(15);

        // Ringkasan Laporan Penjualan (Dashboard Metrics)
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_price');
        $totalTransactions = Transaction::count();
        $pendingCount = Transaction::where('status', 'pending')->count();
        $completedCount = Transaction::where('status', 'completed')->count();

        return view('admin.reports', compact('transactions', 'totalRevenue', 'totalTransactions', 'pendingCount', 'completedCount'));
    }

    // Update Status Transaksi oleh Admin
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,completed,cancelled',
        ]);

        $oldStatus = $transaction->status;
        $newStatus = $request->status;

        // Jika transaksi dibatalkan, kembalikan stok barang
        if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
            $product = $transaction->product;
            if ($product) {
                $product->increment('stock', $transaction->quantity);
            }
        }

        $transaction->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status transaksi ' . $transaction->transaction_code . ' berhasil diperbarui.');
    }

    // Menampilkan Riwayat Belanja Pengguna Aktif
    public function userHistory(Request $request)
    {
        $transactions = Transaction::with('product.category')
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->latest()
            ->paginate(10);

        return view('public.history', compact('transactions'));
    }
}