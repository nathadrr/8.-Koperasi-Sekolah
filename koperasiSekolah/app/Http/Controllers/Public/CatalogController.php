<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller {
    public function index(Request $request) {
        $query = Product::query();
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        $products = $query->get();
        $categories = Category::all();
        return view('public.catalog', compact('products', 'categories'));
    }
}

