<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller {
    public function index() {
        $products = Product::with('category')->get();
        return view('admin.inventory.index', compact('products'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.inventory.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.inventory.index')->with('success', 'Produk berhasil ditambah!');
    }

    public function destroy(Product $product) {
        $product->delete();
        return back()->with('success', 'Produk dihapus!');
    }
}
