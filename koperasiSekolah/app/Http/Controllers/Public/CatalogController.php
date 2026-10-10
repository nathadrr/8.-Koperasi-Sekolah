<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    // Halaman Utama (Landing Page / Home)
    public function home()
    {
        $categories = Category::withCount('products')->get();
        $featuredProducts = Product::with('category')->latest()->take(8)->get();

        return view('public.home', compact('categories', 'featuredProducts'));
    }

    // Halaman Explore (Katalog Produk + Search & Filter)
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('public.catalog', compact('products', 'categories'));
    }
}