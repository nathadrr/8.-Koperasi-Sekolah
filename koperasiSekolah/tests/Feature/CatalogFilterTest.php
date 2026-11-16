<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_explore_shows_all_categories_as_filters_and_filters_products(): void
    {
        $stationery = Category::create(['name' => 'Alat Tulis']);
        $uniforms = Category::create(['name' => 'Seragam']);

        Product::create([
            'category_id' => $stationery->id,
            'name' => 'Buku Tulis',
            'price' => 5000,
            'stock' => 10,
        ]);
        Product::create([
            'category_id' => $uniforms->id,
            'name' => 'Seragam Putih',
            'price' => 50000,
            'stock' => 5,
        ]);

        $this->get(route('explore'))
            ->assertOk()
            ->assertSee('Alat Tulis')
            ->assertSee('Seragam')
            ->assertSee('Buku Tulis')
            ->assertSee('Seragam Putih');

        $this->get(route('explore', ['category' => $stationery->id]))
            ->assertOk()
            ->assertSee('Alat Tulis')
            ->assertSee('Seragam')
            ->assertSee('Buku Tulis')
            ->assertDontSee('Seragam Putih');
    }
}
