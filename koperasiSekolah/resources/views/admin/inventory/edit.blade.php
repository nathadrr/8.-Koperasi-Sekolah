@extends('layouts.admin')

@section('title', 'Edit Produk')
@section('page-title', 'Edit Produk')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">Kembali ke produk</a>
            <h2 class="mt-3 text-xl font-bold tracking-tight text-slate-900">Edit Produk</h2>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi {{ $product->name }}.</p>
        </div>

        <form action="{{ route('admin.inventory.update', $product) }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            @method('PUT')
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Barang</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" required maxlength="255"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stock" class="mb-1.5 block text-sm font-medium text-slate-700">Stok</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="price" class="mb-1.5 block text-sm font-medium text-slate-700">Harga (Rp)</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="100"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="image" class="mb-1.5 block text-sm font-medium text-slate-700">Ganti Gambar (Opsional)</label>
                    <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-slate-200 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-500">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                </div>
                @if($product->image)
                    <div class="sm:col-span-2">
                        <p class="mb-2 text-sm font-medium text-slate-700">Gambar Saat Ini</p>
                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="h-24 w-24 rounded-xl border border-slate-200 object-cover">
                    </div>
                @endif
                <div class="sm:col-span-2">
                    <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
            <div class="mt-7 flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('admin.inventory.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Perbarui Produk</button>
            </div>
        </form>
    </div>
@endsection
