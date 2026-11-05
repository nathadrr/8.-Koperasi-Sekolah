<x-guest-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        
        <!-- Judul Halaman -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Jelajahi Katalog Barang</h1>
            <p class="text-gray-600 text-sm sm:text-base">Temukan berbagai produk dan kebutuhan sekolah di Koperasi SMPK St. Agnes.</p>
        </div>

        <!-- Notifikasi Pesan -->
        @if(session('success'))
            <div class="mb-6 bg-green-50/80 backdrop-blur-md border border-green-200 text-green-700 px-4 py-3 rounded-2xl text-sm shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50/80 backdrop-blur-md border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-sm shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Pencarian (Search Bar Modern) -->
        <form action="{{ route('explore') }}" method="GET" id="filterForm" class="mb-8">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" placeholder="Cari nama barang atau deskripsi..." value="{{ request('search') }}" 
                           class="w-full pl-11 pr-4 py-3.5 text-sm bg-white/80 backdrop-blur-md border border-white rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-sm transition-all placeholder-gray-400">
                </div>
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="px-8 py-3.5 bg-blue-600 text-white font-semibold text-sm rounded-2xl hover:bg-blue-700 transition shadow-md">
                    Cari
                </button>
            </div>

            <!-- Filter Kategori Badges/Pills -->
            <div class="mt-6">
                <div class="flex overflow-x-auto no-scrollbar pb-2 gap-2 snap-x">
                    <!-- Semua Items -->
                    <a href="{{ route('explore', array_filter(['search' => request('search')])) }}" 
                       class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all snap-start whitespace-nowrap border border-white shadow-sm {{ request('category') == '' ? 'bg-gray-900 text-white shadow-md' : 'bg-white/70 text-gray-600 hover:bg-white' }}">
                        Semua Items
                    </a>

                    <!-- Daftar Kategori -->
                    @foreach($categories ?? [] as $category)
                    <a href="{{ route('explore', array_filter(['category' => $category->id, 'search' => request('search')])) }}" 
                       class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all snap-start whitespace-nowrap border border-white shadow-sm {{ request('category') == $category->id ? 'bg-gray-900 text-white shadow-md' : 'bg-white/70 text-gray-600 hover:bg-white' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </form>

        <!-- Grid Produk -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse ($products ?? [] as $product)
            <div class="group flex flex-col h-full bg-white/70 backdrop-blur-md rounded-3xl p-4 transition-all duration-300 shadow-sm hover:shadow-xl hover:bg-white border border-white relative">
                
                <!-- Gambar Produk -->
                <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-2xl bg-gray-50 mb-4 relative">
                    @if($product->stock > 0 && $product->stock <= 5)
                        <span class="absolute top-2 left-2 z-10 bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">Sisa {{ $product->stock }}</span>
                    @elseif($product->stock == 0)
                        <span class="absolute top-2 left-2 z-10 bg-gray-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">Habis</span>
                    @endif

                    <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=500&auto=format&fit=crop&q=80' }}" 
                         alt="{{ $product->name }}" class="h-44 sm:h-52 w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 {{ $product->stock == 0 ? 'grayscale opacity-60' : '' }}">
                </div>
                
                <!-- Info Produk -->
                <div class="flex-grow flex flex-col justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        <h3 class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2 mb-2">{{ $product->name }}</h3>
                        @if($product->description)
                            <p class="text-xs text-gray-500 line-clamp-2 mb-3 leading-relaxed">{{ $product->description }}</p>
                        @endif
                    </div>
                    
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between mt-auto">
                        <div>
                            <span class="text-[10px] text-gray-400 block uppercase font-medium">Harga</span>
                            <span class="text-sm sm:text-base font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($product->stock > 0)
                            @auth
                                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-gray-900 hover:text-white transition-all text-xs font-semibold flex items-center gap-1.5 shadow-sm">
                                        <span>+ Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="px-3.5 py-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-gray-900 hover:text-white transition-all text-xs font-semibold shadow-sm">
                                    + Keranjang
                                </a>
                            @endauth
                        @else
                            <span class="px-3 py-1.5 rounded-xl bg-gray-100 text-gray-400 text-xs font-medium cursor-not-allowed">
                                Stok Habis
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center bg-white/50 backdrop-blur-md rounded-3xl border border-white">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <h3 class="text-base font-semibold text-gray-900">Tidak ada produk ditemukan</h3>
                <p class="text-sm text-gray-500 mt-1">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif

    </div>
</x-guest-layout>