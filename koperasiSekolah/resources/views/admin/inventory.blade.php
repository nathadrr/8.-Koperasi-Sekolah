@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Stok</h1>
        <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition">
            + Tambah Barang
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Produk</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Kategori</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Harga</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Stok</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                        <span class="font-medium">{{ $product->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($product->price) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs {{ $product->stock < 10 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                            {{ $product->stock }} unit
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.inventory.destroy', $product->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
