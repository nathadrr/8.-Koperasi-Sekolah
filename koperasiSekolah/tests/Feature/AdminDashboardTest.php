<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_database_metrics_and_recent_transactions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Alat Tulis']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Buku Tulis',
            'price' => 5000,
            'stock' => 4,
        ]);
        Transaction::create([
            'transaction_code' => 'TRX-DASH-001',
            'user_id' => $admin->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'total_price' => 10000,
            'status' => 'paid',
            'payment_method' => 'cash',
            'buyer_name' => $admin->name,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Produk')
            ->assertSee('Kategori')
            ->assertSee('Pendapatan Dibayar')
            ->assertSee('Pendapatan per Bulan')
            ->assertSee('Jumlah Transaksi per Status')
            ->assertSee('Ekspor CSV')
            ->assertSee('Ekspor XLSX')
            ->assertSee('1 transaksi')
            ->assertSee('Rp 10.000')
            ->assertSee('TRX-DASH-001');
    }

    public function test_admin_pages_share_a_consistent_layout(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Perlengkapan']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Pensil',
            'price' => 3000,
            'stock' => 10,
        ]);

        foreach ([
            route('admin.dashboard'),
            route('admin.inventory.index'),
            route('admin.categories.index'),
            route('admin.reports'),
            route('admin.inventory.create'),
            route('admin.inventory.edit', $product),
        ] as $url) {
            $this->actingAs($admin)
                ->get($url)
                ->assertOk()
                ->assertSee('Koperasi')
                ->assertSee('Panel Admin');
        }
    }

    public function test_admins_are_redirected_from_the_customer_catalog(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('home'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->get(route('explore'))
            ->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Lihat katalog')
            ->assertDontSee('Katalog');
    }

    public function test_admin_is_told_when_midtrans_server_key_is_not_configured_for_sync(): void
    {
        config(['services.midtrans.server_key' => null]);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.transactions.syncPending'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error', 'Server Key Midtrans belum dikonfigurasi. Status transaksi tidak dapat disinkronkan.');
    }

    public function test_regular_users_can_still_access_the_customer_catalog(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Jelajahi Katalog Barang');
    }

    public function test_report_summaries_match_transaction_statuses_and_active_filters(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Alat Tulis']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Buku Tulis',
            'price' => 1000,
            'stock' => 20,
        ]);

        foreach ([
            ['TRX-REPORT-PENDING', 'pending', 1000],
            ['TRX-REPORT-PAID', 'paid', 2000],
            ['TRX-REPORT-COMPLETED', 'completed', 3000],
            ['TRX-REPORT-CANCELLED', 'cancelled', 4000],
        ] as [$code, $status, $totalPrice]) {
            Transaction::create([
                'transaction_code' => $code,
                'user_id' => $admin->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'total_price' => $totalPrice,
                'status' => $status,
                'payment_method' => 'cash',
                'buyer_name' => $admin->name,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.reports'))
            ->assertOk()
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp 5.000</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">4</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-amber-900">1</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-blue-900">2</p>', false);

        $this->actingAs($admin)
            ->get(route('admin.reports', ['status' => 'pending']))
            ->assertOk()
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp 0</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">1</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-amber-900">1</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-blue-900">0</p>', false)
            ->assertDontSee('TRX-REPORT-PAID')
            ->assertDontSee('TRX-REPORT-COMPLETED');

        $this->actingAs($admin)
            ->get(route('admin.reports', ['status' => 'completed']))
            ->assertOk()
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp 3.000</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">1</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-amber-900">0</p>', false)
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-blue-900">1</p>', false)
            ->assertSee('TRX-REPORT-COMPLETED')
            ->assertDontSee('TRX-REPORT-PAID');

        $this->actingAs($admin)
            ->get(route('admin.reports', ['status' => 'paid']))
            ->assertOk()
            ->assertSee('Rp 2.000')
            ->assertSee('<p class="mt-3 text-2xl font-bold tracking-tight text-blue-900">1</p>', false)
            ->assertSee('TRX-REPORT-PAID');
    }
}
