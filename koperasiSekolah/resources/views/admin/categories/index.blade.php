@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-slate-900">Kategori Produk</h2>
        <p class="mt-1 text-sm text-slate-500">Atur kelompok produk yang tampil di katalog koperasi.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.6fr)]">
        <section class="h-fit rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold text-slate-900">Tambah Kategori</h3>
            <p class="mt-1 text-sm text-slate-500">Nama kategori harus unik.</p>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="category-name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama kategori</label>
                    <input id="category-name" type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none transition focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                </div>
                <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Simpan Kategori</button>
            </form>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-4">
                <h3 class="font-semibold text-slate-900">Daftar Kategori</h3>
                <p class="mt-1 text-xs text-slate-500">{{ $categories->total() }} kategori</p>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <div class="flex flex-col gap-3 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex min-w-0 flex-1 items-center gap-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $category->name }}" required maxlength="255" aria-label="Nama kategori {{ $category->name }}"
                                class="min-w-0 flex-1 rounded-lg border border-transparent bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-2 focus:ring-blue-100">
                            <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">{{ $category->products_count }} produk</span>
                            <button type="submit" class="shrink-0 text-sm font-semibold text-blue-600 hover:underline">Simpan</button>
                        </form>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" @disabled($category->products_count > 0) class="rounded-lg border border-rose-100 px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-40">Hapus</button>
                        </form>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center">
                        <p class="font-semibold text-slate-700">Belum ada kategori.</p>
                        <p class="mt-1 text-sm text-slate-500">Tambahkan kategori melalui formulir di samping.</p>
                    </div>
                @endforelse
            </div>
            @if($categories->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">{{ $categories->links() }}</div>
            @endif
        </section>
    </div>
@endsection
