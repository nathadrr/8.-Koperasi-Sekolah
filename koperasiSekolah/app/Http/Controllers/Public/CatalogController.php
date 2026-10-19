<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category; // <-- Pastikan Model Category di-import
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
        // 1. Ambil 6 produk terbaru beserta kategorinya
        $featuredProducts = Product::with('category')->latest()->take(6)->get();

        // 2. Ambil semua kategori beserta hitungan jumlah produknya (withCount)
        $categories = Category::withCount('products')->get();

        // 3. Kirim kedua variabel ($featuredProducts dan $categories) ke view
        return view('public.home', compact('featuredProducts', 'categories'));
    }

    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter pencarian jika ada parameter 'search'
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter kategori jika diklik dari halaman home
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        return view('public.explore', compact('products'));
    }
}