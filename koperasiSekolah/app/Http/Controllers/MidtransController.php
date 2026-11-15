<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionPaymentAttempt;
use App\Services\MidtransPaymentStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class MidtransController extends Controller
{
    public function createTransaction(Request $request): JsonResponse
    {
        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');

        if (!$serverKey || !$clientKey) {
            Log::error('Midtrans keys are not configured.');

            return response()->json([
                'success' => false,
                'message' => 'Pembayaran online belum dikonfigurasi.',
            ], 503);
        }

        $cart = session()->get('cart', []);

        if (!is_array($cart) || $cart === []) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja Anda kosong.',
            ], 422);
        }

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');
        $items = [];
        $grossAmount = 0;
        $totalQuantity = 0;

        foreach ($cart as $productId => $cartItem) {
            $quantity = is_array($cartItem)
                ? filter_var($cartItem['quantity'] ?? null, FILTER_VALIDATE_INT)
                : false;
            $product = $products->get((int) $productId);

            if ($quantity === false || $quantity < 1 || !$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja tidak valid. Silakan muat ulang halaman.',
                ], 422);
            }

            if ($quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$product->name} tidak mencukupi.",
                ], 422);
            }

            $unitPrice = (int) round((float) $product->price);

            if ($unitPrice < 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Harga {$product->name} tidak valid.",
                ], 422);
            }

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ];
            $grossAmount += $unitPrice * $quantity;
            $totalQuantity += $quantity;
        }

        $orderId = 'KOP-' . Str::upper(Str::random(20));
        $snapItems = array_map(fn (array $item) => [
            'id' => (string) $item['product']->id,
            'price' => $item['unit_price'],
            'quantity' => $item['quantity'],
            'name' => Str::limit($item['product']->name, 50, ''),
        ], $items);
        $user = $request->user();

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $grossAmount,
                ],
                'item_details' => $snapItems,
                'customer_details' => [
                    'first_name' => Str::limit($user->name, 50, ''),
                    'email' => $user->email,
                ],
            ]);
        } catch (\Exception $exception) {
            $errorCode = $exception->getCode();
            Log::error('Could not create a Midtrans Snap transaction.', [
                'order_id' => $orderId,
                'error_code' => $errorCode,
            ]);

            return response()->json([
                'success' => false,
                'message' => $errorCode === 401
                    ? 'Kunci Server Midtrans ditolak. Periksa MIDTRANS_SERVER_KEY dan pastikan kunci sandbox sesuai dengan mode sandbox.'
                    : 'Midtrans gagal membuat transaksi. Periksa koneksi dan konfigurasi, lalu coba lagi.',
            ], 502);
        }

        DB::transaction(function () use ($items, $orderId, $grossAmount, $totalQuantity, $user, $snapToken): void {
            $lockedProducts = Product::query()
                ->whereIn('id', array_map(fn (array $item) => $item['product']->id, $items))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $lockedProducts->get($item['product']->id);

                if (
                    !$product
                    || $product->stock < $item['quantity']
                    || (int) round((float) $product->price) !== $item['unit_price']
                ) {
                    abort(409, 'Harga atau stok produk berubah. Silakan periksa keranjang Anda.');
                }
            }

            $transaction = Transaction::create([
                'transaction_code' => $orderId,
                'user_id' => $user->id,
                'product_id' => $items[0]['product']->id,
                'quantity' => $totalQuantity,
                'total_price' => $grossAmount,
                'status' => 'pending',
                'payment_method' => 'midtrans',
                'buyer_name' => $user->name,
                'buyer_class' => $user->role === 'student' ? 'Siswa St. Agnes' : 'Orang Tua / Umum',
            ]);

            $transaction->paymentAttempts()->create([
                'order_id' => $orderId,
                'snap_token' => $snapToken,
            ]);

            foreach ($items as $item) {
                $transaction->details()->create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
                $lockedProducts->get($item['product']->id)->decrement('stock', $item['quantity']);
            }
        });

        session()->forget('cart');

        return response()->json([
            'success' => true,
            'token' => $snapToken,
        ]);
    }

    public function retryPayment(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(404);
        }

        if ($transaction->payment_method !== 'midtrans' || $transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini tidak menunggu pembayaran Midtrans.',
            ], 422);
        }

        $serverKey = config('services.midtrans.server_key');
        $clientKey = config('services.midtrans.client_key');

        if (!$serverKey || !$clientKey) {
            Log::error('Midtrans keys are not configured.');

            return response()->json([
                'success' => false,
                'message' => 'Pembayaran online belum dikonfigurasi.',
            ], 503);
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;

        $attempts = $transaction->paymentAttempts()->where('status', 'pending')->get();

        if ($attempts->count() === 1 && $attempts->first()->snap_token) {
            return response()->json([
                'success' => true,
                'token' => $attempts->first()->snap_token,
            ]);
        }

        if ($attempts->isEmpty()) {
            $legacyAttempt = $transaction->paymentAttempts()
                ->where('order_id', $transaction->transaction_code)
                ->first();

            if (!$legacyAttempt) {
                try {
                    $legacyStatus = MidtransTransaction::status($transaction->transaction_code);
                } catch (\Exception $exception) {
                    if ($exception->getCode() !== 404) {
                        Log::error('Could not inspect a pending Midtrans transaction before retry.', [
                            'order_id' => $transaction->transaction_code,
                            'error_code' => $exception->getCode(),
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Status pembayaran lama tidak dapat diperiksa. Silakan coba lagi.',
                        ], 502);
                    }

                    $legacyStatus = null;
                }

                if (
                    ($legacyStatus->transaction_status ?? null) === 'settlement'
                    || (
                        ($legacyStatus->transaction_status ?? null) === 'capture'
                        && ($legacyStatus->fraud_status ?? null) === 'accept'
                    )
                ) {
                    $transaction->update(['status' => 'paid']);

                    return response()->json([
                        'success' => false,
                        'message' => 'Pesanan ini sudah dibayar.',
                    ], 409);
                }

                if (($legacyStatus->transaction_status ?? null) === 'pending') {
                    try {
                        MidtransTransaction::expire($transaction->transaction_code);
                    } catch (\Exception $exception) {
                        Log::error('Could not expire the previous Midtrans payment before retry.', [
                            'order_id' => $transaction->transaction_code,
                            'error_code' => $exception->getCode(),
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Pembayaran sebelumnya belum dapat ditutup. Silakan coba lagi.',
                        ], 502);
                    }
                }

                $transaction->paymentAttempts()->create([
                    'order_id' => $transaction->transaction_code,
                    'status' => 'expired',
                ]);
            }
        } else {
            foreach ($attempts as $attempt) {
                try {
                    $paymentStatus = MidtransTransaction::status($attempt->order_id);
                } catch (\Exception $exception) {
                    Log::error('Could not inspect a pending Midtrans payment attempt before retry.', [
                        'order_id' => $attempt->order_id,
                        'error_code' => $exception->getCode(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Status pembayaran sebelumnya tidak dapat diperiksa. Silakan coba lagi.',
                    ], 502);
                }

                if (
                    ($paymentStatus->transaction_status ?? null) === 'settlement'
                    || (
                        ($paymentStatus->transaction_status ?? null) === 'capture'
                        && ($paymentStatus->fraud_status ?? null) === 'accept'
                    )
                ) {
                    $attempt->update(['status' => 'paid']);
                    $transaction->update(['status' => 'paid']);

                    return response()->json([
                        'success' => false,
                        'message' => 'Pesanan ini sudah dibayar.',
                    ], 409);
                }

                if (($paymentStatus->transaction_status ?? null) === 'pending') {
                    try {
                        MidtransTransaction::expire($attempt->order_id);
                    } catch (\Exception $exception) {
                        Log::error('Could not expire a previous Midtrans payment attempt.', [
                            'order_id' => $attempt->order_id,
                            'error_code' => $exception->getCode(),
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Pembayaran sebelumnya belum dapat ditutup. Silakan coba lagi.',
                        ], 502);
                    }
                }

                $attempt->update(['status' => 'expired']);
            }
        }

        $items = $transaction->details()->with('product')->get()->map(fn ($detail) => [
            'id' => (string) $detail->product_id,
            'price' => (int) round((float) $detail->unit_price),
            'quantity' => $detail->quantity,
            'name' => Str::limit($detail->product?->name ?? 'Produk Koperasi', 50, ''),
        ]);

        if ($items->isEmpty() && $transaction->product) {
            $items->push([
                'id' => (string) $transaction->product_id,
                'price' => (int) round((float) $transaction->total_price / $transaction->quantity),
                'quantity' => $transaction->quantity,
                'name' => Str::limit($transaction->product->name, 50, ''),
            ]);
        }

        $orderId = 'KOP-' . Str::upper(Str::random(20));

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) round((float) $transaction->total_price),
                ],
                'item_details' => $items->all(),
                'customer_details' => [
                    'first_name' => Str::limit($transaction->buyer_name ?? $request->user()->name, 50, ''),
                    'email' => $request->user()->email,
                ],
            ]);
        } catch (\Exception $exception) {
            Log::error('Could not create a replacement Midtrans payment.', [
                'order_id' => $orderId,
                'transaction_id' => $transaction->id,
                'error_code' => $exception->getCode(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Midtrans gagal menyiapkan pembayaran baru. Silakan coba lagi.',
            ], 502);
        }

        $transaction->paymentAttempts()->create([
            'order_id' => $orderId,
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'success' => true,
            'token' => $snapToken,
        ]);
    }

    public function notification(Request $request, MidtransPaymentStatus $paymentStatus): JsonResponse
    {
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = config('services.midtrans.signature_key');

        if (!$serverKey || !$signatureKey) {
            Log::error('Midtrans notification keys are not configured.');

            return response()->json(['message' => 'Payment notification is unavailable.'], 503);
        }

        $payload = $request->json()->all();
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signature = $payload['signature_key'] ?? null;

        if (
            !is_string($orderId)
            || !is_scalar($statusCode)
            || !is_scalar($grossAmount)
            || !is_string($signature)
        ) {
            return response()->json(['message' => 'Invalid payment notification.'], 422);
        }

        $expectedSignature = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $signatureKey
        );

        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['message' => 'Invalid payment signature.'], 403);
        }

        $attempt = TransactionPaymentAttempt::with('transaction')
            ->where('order_id', $orderId)
            ->first();
        $transaction = $attempt?->transaction
            ?? Transaction::where('transaction_code', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found.'], 404);
        }

        if (
            round((float) $transaction->total_price, 2) !== round((float) $grossAmount, 2)
        ) {
            return response()->json(['message' => 'Payment notification does not match the transaction.'], 403);
        }

        $paymentStatus->applyVerifiedStatus($transaction, $attempt, (object) $payload);

        return response()->json(['success' => true]);
    }
}
