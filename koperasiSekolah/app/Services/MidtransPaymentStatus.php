<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionPaymentAttempt;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class MidtransPaymentStatus
{
    public function syncPendingForUser(User $user): void
    {
        $serverKey = config('services.midtrans.server_key');

        if (!$serverKey) {
            return;
        }

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production');

        Transaction::query()
            ->where('user_id', $user->id)
            ->where('payment_method', 'midtrans')
            ->where('status', 'pending')
            ->with('paymentAttempts')
            ->each(function (Transaction $transaction): void {
                $attempts = $transaction->paymentAttempts;
                $orderIds = $attempts->isEmpty()
                    ? [$transaction->transaction_code]
                    : $attempts->sortByDesc('id')->pluck('order_id')->all();
                $updates = [];
                $hasPendingPayment = false;
                $hasUnknownPayment = false;
                $hasPaidPayment = false;
                $hasTerminalPayment = false;

                foreach ($orderIds as $orderId) {
                    try {
                        $status = MidtransTransaction::status($orderId);
                    } catch (\Exception $exception) {
                        if ($exception->getCode() === 404) {
                            $hasTerminalPayment = true;
                            if ($attempt = $attempts->firstWhere('order_id', $orderId)) {
                                $updates[$attempt->id] = 'cancelled';
                            }
                            continue;
                        }

                        $hasUnknownPayment = true;
                        Log::warning('Could not refresh a pending Midtrans payment.', [
                            'order_id' => $orderId,
                            'error_code' => $exception->getCode(),
                        ]);
                        continue;
                    }

                    if (
                        ($status->order_id ?? null) !== $orderId
                        || !isset($status->gross_amount)
                        || round((float) $status->gross_amount, 2) !== round((float) $transaction->total_price, 2)
                    ) {
                        $hasUnknownPayment = true;
                        Log::warning('Midtrans status did not match the local transaction.', [
                            'order_id' => $orderId,
                            'transaction_id' => $transaction->id,
                        ]);
                        continue;
                    }

                    $attempt = $attempts->firstWhere('order_id', $orderId);
                    $paymentStatus = $status->transaction_status ?? '';

                    if (
                        $paymentStatus === 'settlement'
                        || ($paymentStatus === 'capture' && ($status->fraud_status ?? '') === 'accept')
                    ) {
                        $hasPaidPayment = true;
                        if ($attempt) {
                            $updates[$attempt->id] = 'paid';
                        }
                    } elseif ($paymentStatus === 'pending') {
                        $hasPendingPayment = true;
                        if ($attempt) {
                            $updates[$attempt->id] = 'pending';
                        }
                    } elseif (in_array($paymentStatus, ['deny', 'cancel', 'expire', 'failure'], true)) {
                        $hasTerminalPayment = true;
                        if ($attempt) {
                            $updates[$attempt->id] = $paymentStatus === 'expire' ? 'expired' : 'cancelled';
                        }
                    } else {
                        $hasUnknownPayment = true;
                    }
                }

                if ($updates === [] && !$hasPaidPayment && !$hasPendingPayment && !$hasTerminalPayment) {
                    return;
                }

                DB::transaction(function () use (
                    $transaction,
                    $updates,
                    $hasPendingPayment,
                    $hasUnknownPayment,
                    $hasPaidPayment,
                    $hasTerminalPayment
                ): void {
                    $lockedTransaction = Transaction::query()
                        ->lockForUpdate()
                        ->findOrFail($transaction->id);

                    foreach ($updates as $attemptId => $status) {
                        TransactionPaymentAttempt::query()
                            ->whereKey($attemptId)
                            ->where('transaction_id', $lockedTransaction->id)
                            ->update(['status' => $status]);
                    }

                    if ($lockedTransaction->status !== 'pending') {
                        return;
                    }

                    if ($hasPaidPayment) {
                        $lockedTransaction->update(['status' => 'paid']);

                        return;
                    }

                    if (!$hasPendingPayment && !$hasUnknownPayment && $hasTerminalPayment) {
                        $this->cancelAndRestoreStock($lockedTransaction);
                    }
                });
            });
    }

    public function applyVerifiedStatus(
        Transaction $transaction,
        ?TransactionPaymentAttempt $attempt,
        object $status
    ): void {
        $paymentStatus = $status->transaction_status ?? '';
        $mappedStatus = match (true) {
            $paymentStatus === 'settlement' => 'paid',
            $paymentStatus === 'capture' && ($status->fraud_status ?? '') === 'accept' => 'paid',
            $paymentStatus === 'pending' => 'pending',
            $paymentStatus === 'expire' => 'expired',
            in_array($paymentStatus, ['deny', 'cancel', 'failure'], true) => 'cancelled',
            default => null,
        };

        if ($mappedStatus === null) {
            return;
        }

        DB::transaction(function () use ($transaction, $attempt, $mappedStatus): void {
            $lockedTransaction = Transaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);
            $lockedAttempt = $attempt
                ? TransactionPaymentAttempt::query()->lockForUpdate()->findOrFail($attempt->id)
                : null;

            if ($mappedStatus === 'paid') {
                $lockedAttempt?->update(['status' => 'paid']);

                if ($lockedTransaction->status === 'pending') {
                    $lockedTransaction->update(['status' => 'paid']);
                }

                return;
            }

            if ($mappedStatus === 'pending') {
                $lockedAttempt?->update(['status' => 'pending']);

                return;
            }

            $lockedAttempt?->update(['status' => $mappedStatus]);

            if (
                $lockedTransaction->status === 'pending'
                && !$lockedTransaction->paymentAttempts()->where('status', 'pending')->exists()
            ) {
                $this->cancelAndRestoreStock($lockedTransaction);
            }
        });
    }

    private function cancelAndRestoreStock(Transaction $transaction): void
    {
        foreach ($transaction->details()->with('product')->get() as $detail) {
            $detail->product?->increment('stock', $detail->quantity);
        }

        if ($transaction->details()->doesntExist() && $transaction->product) {
            $transaction->product->increment('stock', $transaction->quantity);
        }

        $transaction->update(['status' => 'cancelled']);
    }
}
