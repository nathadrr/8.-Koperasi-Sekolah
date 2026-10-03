@extends('layouts.admin')

@section('page_title', 'Dashboard Overview')

@section('content')
    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">Total Revenue</p>
            <h3 class="text-3xl font-bold text-gray-900">Rp 12.450.000</h3>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 //..."></path></svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">Total Transactions</p>
            <h3 class="text-3xl font-bold text-gray-900">1,240</h3>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.88l-1. la..."></path></svg>
            </div>
            <p class="text-gray-400 text-sm font-medium">Low Stock Alert</p>
            <h3 class="text-3xl font-bold text-gray-900">12 Items</h3>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h4 class="text-xl font-bold mb-6">Recent Transactions</h4>
            <div class="space-y-4">
                <!-- Dummy Data -->
                <div class="flex justify-between items-center p-4 hover:bg-gray-50 rounded-2xl transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">G</div>
                        <div><p class="text-sm font-semibold">Grace Amanda</p><p class="text-xs text-gray-400">2 mins ago</p></div>
                    </div>
                    <p class="font-bold text-gray-900">Rp 45.000</p>
                </div>
            </div>
        </div>
    </div>
@endsection
