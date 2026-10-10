<x-guest-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Belanja Saya</h1>
            <a href="{{ route('explore') }}" class="text-sm font-semibold text-blue-600 hover:underline">+ Pesan Barang Lagi</a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-white shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6">Kode & Tanggal</th>
                        <th class="py-4 px-6">Barang</th>
                        <th class="py-4 px-6">Total Harga</th>
                        <th class="py-4 px-6">Status Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-white/50 transition">
                        <td class="py-4 px-6">
                            <span class="font-bold text-gray-900 block">{{ $trx->transaction_code }}</span>
                            <span class="text-xs text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }} WIB</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-medium text-gray-900 block">{{ $trx->product->name ?? 'Produk Dihapus' }}</span>
                            <span class="text-xs text-gray-500">{{ $trx->quantity }} unit</span>
                        </td>
                        <td class="py-4 px-6 font-bold text-gray-900">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6">
                            @if($trx->status === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu Pembayaran</span>
                            @elseif($trx->status === 'paid')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Lunas</span>
                            @elseif($trx->status === 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500">Anda belum memiliki riwayat pesanan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
</x-guest-layout>