<x-guest-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h1>
            <a href="{{ route('explore') }}" class="text-sm font-semibold text-blue-600 hover:underline">&larr; Lanjut Belanja</a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if(!empty($cart))
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Daftar Barang -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $id => $item)
                <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl border border-white shadow-sm flex items-center justify-between gap-4">
                    <img src="{{ $item['image'] ? (str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/'.$item['image'])) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=500&auto=format&fit=crop&q=80' }}" 
                         class="w-16 h-16 rounded-xl object-cover shrink-0">
                    
                    <div class="flex-grow">
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ $item['category'] }}</span>
                        <h3 class="font-semibold text-gray-900 text-sm leading-snug">{{ $item['name'] }}</h3>
                        <p class="font-bold text-blue-600 text-sm mt-1">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center gap-1">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_stock'] }}" 
                                   class="w-14 px-2 py-1 border border-gray-200 rounded-lg text-center text-sm font-semibold outline-none focus:ring-1 focus:ring-blue-500">
                            <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-2 py-1 rounded-lg">Ubah</button>
                        </form>

                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Ringkasan Pesanan & Checkout -->
            <div class="bg-white/90 backdrop-blur-md p-6 rounded-2xl border border-white shadow-sm h-fit">
                <h2 class="font-bold text-gray-900 text-base mb-4">Ringkasan Pesanan</h2>
                <div class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between text-gray-600">
                        <span>Metode Pembayaran</span>
                        <span class="font-semibold text-gray-900">Tunai (Di Koperasi)</span>
                    </div>
                    <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-gray-900 text-base">
                        <span>Total Bayar</span>
                        <span class="text-blue-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gray-900 text-white font-semibold py-3 rounded-xl hover:bg-blue-600 transition text-sm shadow-md">
                        Konfirmasi Pesanan
                    </button>
                </form>
            </div>

        </div>
        @else
        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-12 text-center border border-white">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h3 class="text-base font-semibold text-gray-900 mb-1">Keranjang Belanja Kosong</h3>
            <p class="text-sm text-gray-500 mb-6">Anda belum menambahkan barang apapun ke keranjang.</p>
            <a href="{{ route('explore') }}" class="inline-block bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-full text-sm hover:bg-blue-700 transition">Mulai Belanja</a>
        </div>
        @endif

    </div>
</x-guest-layout>