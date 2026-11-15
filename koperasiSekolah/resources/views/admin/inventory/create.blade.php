@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('page-title', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold text-blue-600 hover:underline">&larr; Kembali ke produk</a>
            <h2 class="mt-3 text-xl font-bold tracking-tight text-slate-900">Tambah Barang Baru</h2>
            <p class="mt-1 text-sm text-slate-500">Lengkapi informasi produk yang akan ditampilkan di katalog.</p>
        </div>

        <form action="{{ route('admin.inventory.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
            @csrf
            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Barang</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @if($categories->isEmpty())
                        <p class="mt-2 text-xs text-rose-600">Buat kategori terlebih dahulu melalui <a href="{{ route('admin.categories.index') }}" class="font-semibold underline">Kelola Kategori</a>.</p>
                    @endif
                </div>
                <div>
                    <label for="stock" class="mb-1.5 block text-sm font-medium text-slate-700">Stok Awal</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock') }}" required min="0"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="price" class="mb-1.5 block text-sm font-medium text-slate-700">Harga (Rp)</label>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" required min="0" step="100"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <div>
                    <label for="image" class="mb-1.5 block text-sm font-medium text-slate-700">Gambar Produk</label>
                    <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-slate-200 bg-white text-sm text-slate-600 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-slate-500">JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                </div>
                <div class="sm:col-span-2">
                    <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="mt-7 flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                <a href="{{ route('admin.inventory.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</a>
                <button type="submit" @disabled($categories->isEmpty()) class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">Simpan Produk</button>
            </div>
        </form>
    </div>
@endsection
