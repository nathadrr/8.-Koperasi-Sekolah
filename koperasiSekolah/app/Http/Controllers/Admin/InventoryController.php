<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    // Menampilkan daftar produk (Dashboard Admin) + Fitur Search
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Fitur Pencarian untuk Admin
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

        // withQueryString() memastikan keyword pencarian tidak hilang saat navigasi halaman pagination
        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.inventory.index', compact('products'));
    }

    // Form Tambah Produk
    public function create()
    {
        $categories = Category::all();
        return view('admin.inventory.create', compact('categories'));
    }

    // Simpan Produk Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form Edit Produk
    public function edit(Product $inventory)
    {
        $product = $inventory;
        $categories = Category::all();
        return view('admin.inventory.edit', compact('product', 'categories'));
    }

    // Update Produk
    public function update(Request $request, Product $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $product = $inventory;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $product->image,
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus Produk
    public function destroy(Product $inventory)
    {
        $product = $inventory;
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.inventory.index')->with('success', 'Produk berhasil dihapus.');
    }
}