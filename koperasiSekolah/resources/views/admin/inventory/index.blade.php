@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Data Produk')
@section('header-actions')
    <a href="{{ route('admin.inventory.create') }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">+ Tambah Produk</a>
@endsection

@section('content')
    <div class="mb-5 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900">Inventaris Produk</h2>
            <p class="mt-1 text-sm text-slate-500">Kelola produk, harga, dan persediaan koperasi.</p>
        </div>
        <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex w-full gap-2 sm:w-auto">
            <label for="product-search" class="sr-only">Cari produk</label>
            <input id="product-search" type="search" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100 sm:w-64">
            <button type="submit" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cari</button>
        </form>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Harga</th>
                        <th class="px-5 py-3.5">Stok</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=100&q=80' }}"
                                        alt="" class="h-11 w-11 rounded-xl border border-slate-100 object-cover">
                                    <span class="font-semibold text-slate-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                    'bg-rose-50 text-rose-700' => $product->stock <= 5,
                                    'bg-emerald-50 text-emerald-700' => $product->stock > 5,
                                ])>{{ $product->stock }} unit</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('admin.inventory.edit', $product) }}" class="font-semibold text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.inventory.destroy', $product) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-rose-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <p class="font-semibold text-slate-700">{{ request('search') ? 'Produk tidak ditemukan.' : 'Belum ada produk.' }}</p>
                                <p class="mt-1 text-sm text-slate-500">Tambah produk atau ubah kata kunci pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $products->links() }}</div>
        @endif
    </section>
@endsection
