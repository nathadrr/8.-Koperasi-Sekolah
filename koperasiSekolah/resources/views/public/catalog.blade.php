<x-guest-layout>
    <div class="w-full px-4 sm:px-6 lg:px-10 py-8 lg:py-12">
        
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-2">Katalog Produk</h1>
            <p class="text-gray-500 text-sm">Cari dan filter perlengkapan sekolah sesuai kebutuhan Anda.</p>
        </div>

        @if(session('success'))
            <div class="max-w-7xl mx-auto mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-7xl mx-auto mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div id="explore" class="flex flex-col lg:flex-row gap-8 items-start">
            
            <!-- Sidebar Filter -->
            <aside class="w-full lg:w-72 flex-shrink-0 bg-white/50 backdrop-blur-md lg:bg-transparent p-4 lg:p-0 rounded-2xl border border-white lg:border-none shadow-sm lg:shadow-none lg:sticky lg:top-24">
                <form action="{{ route('explore') }}" method="GET" id="filterForm">
                    
                    <div class="mb-6 relative">
                        <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}" 
                               class="w-full pl-4 pr-10 py-3 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-sm transition-all">
                        <button type="submit" class="absolute right-3 top-3 text-gray-400 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>

                    <div>
                        <h3 class="hidden lg:block text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Kategori Produk</h3>
                        
                        <div class="flex overflow-x-auto no-scrollbar pb-2 lg:pb-0 lg:flex-col gap-2 snap-x">
                            
                            <label class="cursor-pointer flex-shrink-0 snap-start">
                                <input type="radio" name="category" value="" onchange="document.getElementById('filterForm').submit();" class="peer sr-only" {{ request('category') == '' ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-full lg:rounded-xl bg-white lg:bg-transparent border border-gray-100 lg:border-transparent text-sm text-gray-600 peer-checked:bg-gray-900 peer-checked:text-white lg:peer-checked:bg-white lg:peer-checked:text-blue-700 lg:peer-checked:shadow-sm transition-all flex items-center">
                                    <span class="font-medium whitespace-nowrap">Semua Items</span>
                                </div>
                            </label>

                            @foreach($categories ?? [] as $category)
                            <label class="cursor-pointer flex-shrink-0 snap-start">
                                <input type="radio" name="category" value="{{ $category->id }}" onchange="document.getElementById('filterForm').submit();" class="peer sr-only" {{ request('category') == $category->id ? 'checked' : '' }}>
                                <div class="px-4 py-2 rounded-full lg:rounded-xl bg-white lg:bg-transparent border border-gray-100 lg:border-transparent text-sm text-gray-600 peer-checked:bg-gray-900 peer-checked:text-white lg:peer-checked:bg-white lg:peer-checked:text-blue-700 lg:peer-checked:shadow-sm transition-all flex items-center">
                                    <span class="font-medium whitespace-nowrap">{{ $category->name }}</span>
                                </div>
                            </label>
                            @endforeach
                            
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Grid Produk -->
            <section class="flex-1 w-full">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    
                    @forelse ($products ?? [] as $product)
                    <div class="group flex flex-col h-full bg-white/70 backdrop-blur-sm rounded-2xl p-4 transition-all duration-300 shadow-sm hover:shadow-xl hover:bg-white border border-white relative">
                        
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-xl bg-gray-100 mb-4 relative">
                            @if($product->stock > 0 && $product->stock <= 5)
                                <span class="absolute top-2 left-2 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Sisa {{ $product->stock }}</span>
                            @elseif($product->stock == 0)
                                <span class="absolute top-2 left-2 z-10 bg-gray-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">Habis</span>
                            @endif

                            <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=500&auto=format&fit=crop&q=80' }}" 
                                 alt="{{ $product->name }}" class="h-44 sm:h-52 w-full object-cover object-center group-hover:scale-105 transition-transform duration-500 {{ $product->stock == 0 ? 'grayscale opacity-60' : '' }}">
                        </div>
                        
                        <div class="flex-grow flex flex-col justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                <h3 class="text-sm font-semibold text-gray-900 leading-snug line-clamp-2">{{ $product->name }}</h3>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <p class="text-sm sm:text-base font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                
                                @if($product->stock > 0)
                                    @auth
                                        <!-- Perbaikan Rute: Mengarahkan ke cart.add -->
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-gray-900 hover:text-white transition-colors" title="Tambah ke Keranjang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="w-9 h-9 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 hover:bg-gray-900 hover:text-white transition-colors" title="Login untuk Membeli">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                        </a>
                                    @endauth
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center bg-white/40 rounded-2xl border border-white">
                        <p class="text-gray-500 font-medium">Tidak ada produk yang ditemukan.</p>
                    </div>
                    @endforelse

                </div>
                
                @if(isset($products) && $products->hasPages())
                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-guest-layout>