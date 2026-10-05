<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function checkout(Request $request) {
        $userId = $request->user()->getAuthIdentifier();
        DB::transaction(function () use ($request, $userId) {
            $transaction = Transaction::create([
                'user_id' => $userId,
                'total_price' => $request->total,
                'payment_status' => 'paid',
            ]);

            foreach ($request->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                // Inventory Reduction
                Product::find($item['id'])->decrement('stock', $item['qty']);
            }
        });
        return redirect()->route('home')->with('success', 'Pembayaran Berhasil!');
    }
}

