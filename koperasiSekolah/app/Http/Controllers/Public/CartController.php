<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Menampilkan Halaman Keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return view('public.cart', compact('cart', 'totalPrice'));
    }

    // Menambah Barang ke Keranjang
    public function add(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock < 1) {
            return redirect()->back()->with('error', 'Stok barang habis.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] + 1 > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah barang melebihi stok yang tersedia.');
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'category' => $product->category->name ?? 'Umum',
                'max_stock' => $product->stock,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

    // Perbarui Jumlah Barang di Keranjang
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $product = Product::find($id);

        if (isset($cart[$id])) {
            if ($product && $request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }

            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Jumlah barang berhasil diperbarui.');
    }

    // Hapus Barang dari Keranjang
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Barang dihapus dari keranjang.');
    }

    // Proses Checkout Seluruh Isi Keranjang
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $user = Auth::user();
        $batchCode = 'TRX-' . strtoupper(Str::random(6)) . '-' . date('Ymd');

        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            if (!$product || $product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', 'Stok untuk ' . $item['name'] . ' tidak mencukupi.');
            }

            Transaction::create([
                'transaction_code' => $batchCode,
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
                'status' => 'pending',
                'payment_method' => 'cash',
                'buyer_name' => $user->name,
                'buyer_class' => $user->role === 'student' ? 'Siswa St. Agnes' : 'Orang Tua / Umum',
            ]);

            // Kurangi stok barang
            $product->decrement('stock', $item['quantity']);
        }

        // Kosongkan keranjang setelah checkout
        session()->forget('cart');

        return redirect()->route('history.index')->with('success', 'Pesanan berhasil dibuat dengan Kode: ' . $batchCode . '. Silakan bayar di kasir koperasi saat mengambil barang.');
    }
}