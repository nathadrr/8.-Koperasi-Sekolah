@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <p class="text-sm text-slate-500">Ringkasan kondisi katalog dan transaksi koperasi.</p>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Produk</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($stats['products'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.inventory.index') }}" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:underline">Kelola produk &rarr;</a>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Kategori</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($stats['categories'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.categories.index') }}" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:underline">Kelola kategori &rarr;</a>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-amber-800">Menunggu Pembayaran</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-amber-900">{{ number_format($stats['pending'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="mt-3 inline-block text-xs font-semibold text-amber-800 hover:underline">Lihat transaksi &rarr;</a>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-emerald-800">Pendapatan Dibayar</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            <p class="mt-3 text-xs text-emerald-800">Dari transaksi lunas dan selesai</p>
        </div>
    </div>

    <div class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <h2 class="font-semibold text-slate-900">Perhatian stok</h2>
            <p class="mt-1 text-sm text-slate-500">{{ $stats['lowStock'] }} produk memiliki stok 5 unit atau kurang.</p>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Periksa inventaris</a>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-900">Transaksi Terbaru</h2>
                <p class="mt-1 text-xs text-slate-500">Lima pesanan yang terakhir dibuat</p>
            </div>
            <a href="{{ route('admin.reports') }}" class="text-sm font-semibold text-blue-600 hover:underline">Semua transaksi &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Kode Pesanan</th>
                        <th class="px-5 py-3">Pembeli</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-900">{{ $transaction->transaction_code }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction->buyer_name ?? $transaction->user?->name ?? 'Pembeli' }}</td>
                            <td class="px-5 py-4 font-medium text-slate-900">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'cancelled' => 'bg-rose-100 text-rose-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'paid' => 'Lunas',
                                        'completed' => 'Selesai',
                                        'cancelled' => 'Dibatalkan',
                                    ];
                                @endphp
                                <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-semibold', $statusStyles[$transaction->status] ?? 'bg-slate-100 text-slate-700'])>
                                    {{ $statusLabels[$transaction->status] ?? $transaction->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-slate-500">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
