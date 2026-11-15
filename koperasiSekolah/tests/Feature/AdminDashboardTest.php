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

    public function test_regular_users_can_still_access_the_customer_catalog(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('Jelajahi Katalog Barang');
    }
}
