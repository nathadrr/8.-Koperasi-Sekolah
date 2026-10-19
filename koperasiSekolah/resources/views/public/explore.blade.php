<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajahi Katalog Barang - Koperasi Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col text-gray-900">

    <!-- Header / Navbar Public -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-700">Koperasi Sekolah</a>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('explore') }}" class="text-sm font-semibold text-blue-600">Katalog Barang</a>
                @auth
                    <a href="{{ route('cart.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Keranjang</a>
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Log In</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Konten Utama Katalog -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full">
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-2xl font-bold text-gray-900">Jelajahi Katalog Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Temukan berbagai produk dan kebutuhan sekolah di Koperasi.</p>
        </div>

        <!-- Form Pencarian Produk -->
        <div class="mb-8 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('explore') }}" method="GET" class="flex gap-3">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari nama barang atau deskripsi..." 
                        value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                    >
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                    Cari
                </button>
                @if(request('search') || request('category'))
                    <a href="{{ route('explore') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Grid Daftar Barang -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                    <img 
                        src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=400&q=80' }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-48 object-cover border-b border-gray-100"
                    >
                    <div class="p-5 flex flex-col flex-1">
                        <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">
                            {{ $product->category->name ?? 'Umum' }}
                        </span>
                        <h3 class="font-bold text-gray-900 text-base mb-1 line-clamp-1">{{ $product->name }}</h3>
                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
                        
                        <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-gray-400 block">Harga</span>
                                <span class="text-base font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>

                            @if($product->stock > 0)
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                        + Keranjang
                                    </button>
                                </form>
                            @else
                                <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded-lg text-xs font-semibold">Habis</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-gray-500 font-medium">
                        @if(request('search'))
                            Barang dengan kata kunci "<span class="font-semibold text-gray-800">{{ request('search') }}</span>" tidak ditemukan.
                        @else
                            Belum ada barang yang tersedia di katalog.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400 mt-auto">
        &copy; {{ date('Y') }} Koperasi Sekolah. All rights reserved.
    </footer>

</body>
</html>