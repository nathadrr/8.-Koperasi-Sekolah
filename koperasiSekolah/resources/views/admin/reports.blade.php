@extends('layouts.admin')

@section('title', 'Transaksi')
@section('page-title', 'Laporan Penjualan & Transaksi')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl font-bold tracking-tight text-slate-900">Ringkasan Penjualan</h2>
        <p class="mt-1 text-sm text-slate-500">Ringkasan dan daftar mengikuti filter yang dipilih. Pendapatan mencakup transaksi lunas dan selesai.</p>
    </div>

    <div class="mb-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-sm font-medium text-emerald-800">Pendapatan Dibayar</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-sm font-medium text-amber-800">Menunggu Pembayaran</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-amber-900">{{ number_format($pendingCount, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
            <p class="text-sm font-medium text-blue-800">Transaksi Berhasil</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-blue-900">{{ number_format($completedCount, 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="status-filter" class="mb-1.5 block text-xs font-semibold text-slate-600">Status</label>
                <select id="status-filter" name="status" class="min-w-44 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                    <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
                    <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label for="date-filter" class="mb-1.5 block text-xs font-semibold text-slate-600">Tanggal</label>
                <input id="date-filter" type="date" name="date" value="{{ request('date') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            </div>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Terapkan Filter</button>
            @if(request('status') || request('date'))
                <a href="{{ route('admin.reports') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Reset</a>
            @endif
        </form>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h3 class="font-semibold text-slate-900">Daftar Transaksi</h3>
                <p class="mt-1 text-xs text-slate-500">{{ $transactions->total() }} transaksi ditemukan</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-left text-sm">
                <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3.5">Pesanan</th>
                        <th class="px-5 py-3.5">Pembeli</th>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Total</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="align-top transition hover:bg-slate-50/70">
                            <td class="px-5 py-4">
                                <span class="block font-semibold text-slate-900">{{ $trx->transaction_code }}</span>
                                <span class="mt-1 block text-xs text-slate-500">{{ $trx->created_at->format('d M Y, H:i') }} WIB</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="block font-medium text-slate-800">{{ $trx->buyer_name ?? $trx->user?->name ?? 'Pembeli' }}</span>
                                <span class="mt-1 block text-xs text-slate-500">{{ $trx->buyer_class ?? 'Umum' }}</span>
                            </td>
                            <td class="max-w-xs px-5 py-4">
                                @forelse($trx->details as $detail)
                                    <span class="block font-medium text-slate-800">{{ $detail->product->name ?? 'Produk Dihapus' }}</span>
                                    <span class="mb-2 block text-xs text-slate-500">{{ $detail->quantity }} unit x Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</span>
                                @empty
                                    <span class="block font-medium text-slate-800">{{ $trx->product->name ?? 'Produk Dihapus' }}</span>
                                    <span class="block text-xs text-slate-500">{{ $trx->quantity }} unit x Rp {{ number_format($trx->product->price ?? 0, 0, ',', '.') }}</span>
                                @endforelse
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
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
                                <span @class(['inline-flex rounded-full px-2.5 py-1 text-xs font-semibold', $statusStyles[$trx->status] ?? 'bg-slate-100 text-slate-700'])>
                                    {{ $statusLabels[$trx->status] ?? $trx->status }}
                                </span>
                                <span class="mt-1 block text-xs text-slate-500">{{ $trx->payment_method === 'midtrans' ? 'Midtrans' : 'Tunai' }}</span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('admin.transactions.updateStatus', $trx) }}" method="POST" class="inline-flex">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status-{{ $trx->id }}" class="sr-only">Ubah status transaksi {{ $trx->transaction_code }}</label>
                                    <select id="status-{{ $trx->id }}" name="status" onchange="this.form.submit()" class="rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-medium text-slate-700 outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                                        <option value="pending" @selected($trx->status === 'pending')>Menunggu</option>
                                        <option value="paid" @selected($trx->status === 'paid')>Lunas</option>
                                        <option value="completed" @selected($trx->status === 'completed')>Selesai</option>
                                        <option value="cancelled" @selected($trx->status === 'cancelled')>Dibatalkan</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center text-slate-500">Belum ada transaksi untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
            <div class="border-t border-slate-100 px-5 py-4">{{ $transactions->links() }}</div>
        @endif
    </section>
@endsection
