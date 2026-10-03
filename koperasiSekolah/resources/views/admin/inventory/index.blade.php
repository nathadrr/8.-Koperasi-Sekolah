@extends('layouts.admin')

@section('page_title', 'Manajemen Barang')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div class="w-1/3">
            <label class="text-xs font-semibold text-gray-400 uppercase mb-2 block">Cari Barang</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" placeholder="Ketik nama barang..." class="w-full pl-10 pr-4 py-3 rounded-2xl border-none bg-white shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
        </div>
        
        <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-medium hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
            + Tambah Barang
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($products as $product)
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-12 h-12 rounded-xl object-cover">
                        <span class="font-medium text-gray-700">{{ $product->name }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">Rp {{ number_format($product->price) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg text-xs font-medium {{ $product->stock < 10 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                            {{ $product->stock }} unit
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="text-gray-400 hover:text-red-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
