<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\MidtransPaymentStatus;
use Tests\TestCase;

class MidtransTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_request_a_midtrans_token(): void
    {
        $this->postJson(route('midtrans.token'))
            ->assertUnauthorized();
    }

    public function test_midtrans_checkout_rejects_an_empty_cart(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.client_key' => 'test-client-key',
        ]);

        $this->actingAs(User::factory()->create())
            ->postJson(route('midtrans.token'))
            ->assertUnprocessable()
            ->assertJsonPath('success', false);
    }

    public function test_midtrans_notification_rejects_an_invalid_signature(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.signature_key' => 'test-signature-key',
        ]);

        $this->postJson(route('midtrans.notification'), [
            'order_id' => 'KOP-TEST',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => 'invalid',
        ])->assertForbidden();
    }

    public function test_midtrans_notification_requires_a_dedicated_signature_key(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.signature_key' => null,
        ]);

        $this->postJson(route('midtrans.notification'), [])
            ->assertServiceUnavailable();
    }

    public function test_valid_settlement_notification_updates_transaction_without_status_api_access(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.signature_key' => 'test-signature-key',
        ]);

        $transaction = $this->createMidtransTransaction(User::factory()->create());
        $attempt = $transaction->paymentAttempts()->create([
            'order_id' => 'KOP-' . fake()->unique()->bothify('####################'),
            'status' => 'pending',
        ]);
        $payload = [
            'order_id' => $attempt->order_id,
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'transaction_status' => 'settlement',
            'signature_key' => hash(
                'sha512',
                $attempt->order_id . '20010000.00test-signature-key'
            ),
        ];

        $this->postJson(route('midtrans.notification'), $payload)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('transaction_payment_attempts', [
            'id' => $attempt->id,
            'status' => 'paid',
        ]);
    }

    public function test_valid_expiration_notification_cancels_pending_transaction(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.signature_key' => 'test-signature-key',
        ]);

        $transaction = $this->createMidtransTransaction(User::factory()->create());
        $attempt = $transaction->paymentAttempts()->create([
            'order_id' => 'KOP-' . fake()->unique()->bothify('####################'),
            'status' => 'pending',
        ]);
        $payload = [
            'order_id' => $attempt->order_id,
            'status_code' => '407',
            'gross_amount' => '10000.00',
            'transaction_status' => 'expire',
            'signature_key' => hash(
                'sha512',
                $attempt->order_id . '40710000.00test-signature-key'
            ),
        ];

        $this->postJson(route('midtrans.notification'), $payload)
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('transaction_payment_attempts', [
            'id' => $attempt->id,
            'status' => 'expired',
        ]);
    }

    public function test_verified_settlement_updates_transaction_to_paid(): void
    {
        $transaction = $this->createMidtransTransaction(User::factory()->create());
        $attempt = $transaction->paymentAttempts()->create([
            'order_id' => 'KOP-' . fake()->unique()->bothify('####################'),
            'status' => 'pending',
        ]);

        app(MidtransPaymentStatus::class)->applyVerifiedStatus($transaction, $attempt, (object) [
            'transaction_status' => 'settlement',
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('transaction_payment_attempts', [
            'id' => $attempt->id,
            'status' => 'paid',
        ]);
    }

    public function test_verified_expiration_cancels_transaction_and_restores_stock_once(): void
    {
        $transaction = $this->createMidtransTransaction(User::factory()->create());
        $product = $transaction->product;
        $product->update(['stock' => 4]);
        $transaction->details()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10000,
        ]);
        $attempt = $transaction->paymentAttempts()->create([
            'order_id' => 'KOP-' . fake()->unique()->bothify('####################'),
            'status' => 'pending',
        ]);

        $paymentStatus = app(MidtransPaymentStatus::class);
        $paymentStatus->applyVerifiedStatus($transaction, $attempt, (object) [
            'transaction_status' => 'expire',
        ]);
        $paymentStatus->applyVerifiedStatus($transaction, $attempt, (object) [
            'transaction_status' => 'expire',
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'cancelled',
        ]);
        $this->assertDatabaseHas('transaction_payment_attempts', [
            'id' => $attempt->id,
            'status' => 'expired',
        ]);
        $this->assertSame(5, $product->fresh()->stock);
    }

    public function test_user_cannot_retry_another_users_payment(): void
    {
        $owner = User::factory()->create();
        $transaction = $this->createMidtransTransaction($owner);

        $this->actingAs(User::factory()->create())
            ->postJson(route('midtrans.retry', $transaction))
            ->assertNotFound();
    }

    public function test_only_pending_midtrans_transactions_can_be_retried(): void
    {
        $user = User::factory()->create();
        $cashTransaction = $this->createMidtransTransaction($user, 'cash');

        $this->actingAs($user)
            ->postJson(route('midtrans.retry', $cashTransaction))
            ->assertUnprocessable();

        $paidTransaction = $this->createMidtransTransaction($user, 'midtrans', 'paid');

        $this->postJson(route('midtrans.retry', $paidTransaction))
            ->assertUnprocessable();
    }

    public function test_pending_transaction_reuses_its_active_snap_token(): void
    {
        config([
            'services.midtrans.server_key' => 'test-server-key',
            'services.midtrans.client_key' => 'test-client-key',
        ]);

        $user = User::factory()->create();
        $transaction = $this->createMidtransTransaction($user);
        $transaction->paymentAttempts()->create([
            'order_id' => 'KOP-' . fake()->unique()->bothify('####################'),
            'snap_token' => 'existing-snap-token',
        ]);

        $this->actingAs($user)
            ->postJson(route('midtrans.retry', $transaction))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('token', 'existing-snap-token');
    }

    private function createMidtransTransaction(User $user, string $paymentMethod = 'midtrans', string $status = 'pending'): Transaction
    {
        $category = Category::create(['name' => fake()->unique()->word()]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Test product',
            'price' => 10000,
            'stock' => 5,
        ]);

        return Transaction::create([
            'transaction_code' => 'TRX-' . fake()->unique()->bothify('########'),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'total_price' => 10000,
            'status' => $status,
            'payment_method' => $paymentMethod,
        ]);
    }
}
