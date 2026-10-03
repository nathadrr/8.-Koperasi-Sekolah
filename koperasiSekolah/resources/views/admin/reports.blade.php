@extends('layouts.admin')

@section('page_title', 'Detail Transaksi')

@section('content')
    <!-- FILTER BAR -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="text-xs font-semibold text-gray-400 uppercase mb-2 block">Cari Nama/ID</label>
            <input type="text" placeholder="Search..." class="w-full px-4 py-2 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div class="w-48">
            <label class="text-xs font-semibold text-gray-400 uppercase mb-2 block">Dari Tanggal</label>
            <input type="date" class="w-full px-4 py-2 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div class="w-48">
            <label class="text-xs font-semibold text-gray-400 uppercase mb-2 block">Hingga Tanggal</label>
            <input type="date" class="w-full px-4 py-2 rounded-xl border border-gray-100 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <button class="bg-gray-900 text-white px-6 py-2 rounded-xl font-medium hover:bg-gray-800 transition">
            Filter
        </button>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">ID Transaksi</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Tanggal & Waktu</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Nama Pembeli</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase">Total Bayar</th>
                    <th class="px-6 py-4 text-sm font-semibold text-gray-500 uppercase text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <!-- Data Loop -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">#TRX-10023</td>
                    <td class="px-6 py-4 text-gray-500">03 Oct 2026, 14:20</td>
                    <td class="px-6 py-4 text-gray-700">Grace Amanda</td>
                    <td class="px-6 py-4 font-semibold">Rp 150.000</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">Lunas</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
