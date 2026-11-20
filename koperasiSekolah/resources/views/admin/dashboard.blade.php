@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <p class="text-sm text-slate-500">Ringkasan kondisi katalog dan transaksi koperasi.</p>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.transactions.syncPending') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Sinkronkan Midtrans
                </button>
            </form>
            <a href="{{ route('admin.reports.export', ['format' => 'csv']) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Ekspor CSV</a>
            <a href="{{ route('admin.reports.export', ['format' => 'xlsx']) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Ekspor XLSX</a>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Produk</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($stats['products'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.inventory.index') }}" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:underline">Kelola produk</a>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Kategori</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($stats['categories'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.categories.index') }}" class="mt-3 inline-block text-xs font-semibold text-blue-600 hover:underline">Kelola kategori</a>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-amber-800">Menunggu Pembayaran</p>
            <p class="mt-3 text-3xl font-bold tracking-tight text-amber-900">{{ number_format($stats['pending'], 0, ',', '.') }}</p>
            <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="mt-3 inline-block text-xs font-semibold text-amber-800 hover:underline">Lihat transaksi</a>
        </div>
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
            <p class="text-sm font-medium text-emerald-800">Pendapatan Dibayar</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            <p class="mt-3 text-xs text-emerald-800">Dari transaksi lunas dan selesai</p>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-4 xl:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <h2 class="font-semibold text-slate-900">Pendapatan per Bulan</h2>
                <p class="mt-1 text-xs text-slate-500">Pendapatan dari pesanan yang dibuat selama enam bulan terakhir.</p>
            </div>
            <div class="mt-6 grid h-64 grid-cols-6 items-end gap-2 sm:gap-4">
                @foreach($monthlySales as $month)
                    @php
                        $revenueHeight = $month['revenue'] > 0
                            ? max(4, round(($month['revenue'] / $maxMonthlyRevenue) * 100))
                            : 0;
                    @endphp
                    <div class="flex h-full min-w-0 flex-col items-center justify-end gap-2">
                        <span class="max-w-full truncate text-center text-[10px] font-medium text-slate-500 sm:text-xs">
                            Rp {{ number_format($month['revenue'], 0, ',', '.') }}
                        </span>
                        <div class="flex h-40 w-full items-end overflow-hidden rounded-t-lg bg-slate-100">
                            <div class="w-full rounded-t-lg bg-emerald-500 transition-all" style="height: {{ $revenueHeight }}%"></div>
                        </div>
                        <span class="text-center text-[10px] font-medium text-slate-500 sm:text-xs">{{ $month['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <h2 class="font-semibold text-slate-900">Jumlah Transaksi per Status</h2>
                <p class="mt-1 text-xs text-slate-500">Jumlah pesanan bulanan berdasarkan status terakhir yang tercatat.</p>
            </div>
            <div class="mt-6 space-y-4">
                @foreach($monthlySales as $month)
                    <div>
                        <div class="mb-1.5 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-600">{{ $month['label'] }}</span>
                            <span class="text-slate-500">{{ $month['total'] }} transaksi</span>
                        </div>
                        <div class="flex h-4 overflow-hidden rounded-full bg-slate-100">
                            <div class="bg-blue-500" style="width: {{ round(($month['paid'] / $maxMonthlyTransactions) * 100, 2) }}%" title="Lunas: {{ $month['paid'] }}"></div>
                            <div class="bg-amber-400" style="width: {{ round(($month['pending'] / $maxMonthlyTransactions) * 100, 2) }}%" title="Menunggu: {{ $month['pending'] }}"></div>
                            <div class="bg-rose-400" style="width: {{ round(($month['cancelled'] / $maxMonthlyTransactions) * 100, 2) }}%" title="Dibatalkan: {{ $month['cancelled'] }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-600">
                <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>Lunas/Selesai</span>
                <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>Menunggu</span>
                <span class="inline-flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-rose-400"></span>Dibatalkan</span>
            </div>
        </section>
    </div>
    <p class="mb-8 text-xs text-slate-500">Grafik menggunakan data transaksi tersimpan, termasuk pembaruan status dari notifikasi Midtrans. Gunakan tombol sinkronisasi untuk memeriksa transaksi yang masih menunggu melalui API Midtrans.</p>

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
            <a href="{{ route('admin.reports') }}" class="text-sm font-semibold text-blue-600 hover:underline">Semua transaksi</a>
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
