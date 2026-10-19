<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // READ: Menampilkan daftar produk & stok
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.stock.index', compact('products'));
    }

    // CREATE: Form tambah produk baru
    public function create()
    {
        $categories = Category::all();
        return view('admin.stock.create', compact('categories'));
    }

    // STORE: Simpan produk baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.stock.index')->with('success', 'Produk dan stok berhasil ditambahkan.');
    }

    // EDIT: Form edit produk & stok
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.stock.edit', compact('product', 'categories'));
    }

    // UPDATE: Update data produk & stok
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.stock.index')->with('success', 'Stok dan data produk berhasil diperbarui.');
    }

    // DELETE: Hapus produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.stock.index')->with('success', 'Produk berhasil dihapus.');
    }
}