<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartQuantityControlsTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_uses_minus_and_plus_quantity_controls(): void
    {
        $product = $this->createProduct();

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$product->id => $this->cartItem($product, 2)]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('data-quantity-step="-1"', false)
            ->assertSee('data-quantity-step="1"', false)
            ->assertSee('value="2"', false)
            ->assertDontSee('>Ubah</button>', false);
    }

    public function test_cart_quantity_control_updates_the_session_and_order_total(): void
    {
        $product = $this->createProduct();

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$product->id => $this->cartItem($product, 2)]])
            ->patch(route('cart.update', $product->id), ['quantity' => 3])
            ->assertRedirect()
            ->assertSessionHas("cart.{$product->id}.quantity", 3);

        $this->actingAs(User::factory()->create())
            ->withSession(['cart' => [$product->id => $this->cartItem($product, 3)]])
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Rp 15.000');
    }

    private function createProduct(): Product
    {
        $category = Category::create(['name' => 'Alat Tulis']);

        return Product::create([
            'category_id' => $category->id,
            'name' => 'Buku Tulis',
            'price' => 5000,
            'stock' => 10,
        ]);
    }

    private function cartItem(Product $product, int $quantity): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'image' => $product->image,
            'category' => $product->category->name,
            'max_stock' => $product->stock,
        ];
    }
}
