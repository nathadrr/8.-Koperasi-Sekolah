<x-guest-layout>
    <div class="w-full">
        
        <!-- Hero Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 text-center">
            <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 font-semibold text-xs rounded-full uppercase tracking-widest mb-4">
                Official School Cooperative
            </span>
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight mb-4">
                WELCOME TO
            </h1>
            <h2 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold text-blue-600 tracking-tight mb-8">
                Koperasi SMPK St. Agnes
            </h2>
            <p class="text-gray-600 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto mb-10">
                Digitalisasi kebutuhan akademik SMPK Santa Agnes Surabaya. Cepat, transparan, dan mudah diakses oleh seluruh siswa, guru, dan orang tua.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('explore') }}" class="w-full sm:w-auto bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-blue-600 transition shadow-lg text-sm">
                    Jelajahi Katalog Barang
                </a>
                <a href="#about" class="w-full sm:w-auto bg-white/80 text-gray-800 px-8 py-4 rounded-full font-semibold hover:bg-white transition border border-gray-200 text-sm">
                    Pelajari Selengkapnya
                </a>
            </div>
        </section>

        <!-- Preview Kategori -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-gray-900">Kategori Utama</h3>
                <a href="{{ route('explore') }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Semua Katalog &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($categories as $category)
                <a href="{{ route('explore', ['category' => $category->id]) }}" class="bg-white/60 backdrop-blur-md p-6 rounded-2xl border border-white hover:bg-white hover:shadow-lg transition text-center group">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $category->name }}</h4>
                    <p class="text-xs text-gray-400">{{ $category->products_count }} Produk</p>
                </a>
                @endforeach
            </div>
        </section>

        <!-- Preview Produk Unggulan -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Produk Terbaru</h3>
                    <p class="text-sm text-gray-500">Perlengkapan sekolah terpopuler dan siap dipesan.</p>
                </div>
                <a href="{{ route('explore') }}" class="text-sm font-semibold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-4 border border-white shadow-sm hover:shadow-md transition flex flex-col h-full">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-xl bg-gray-50 mb-4">
                        <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=500&auto=format&fit=crop&q=80' }}" 
                             alt="{{ $product->name }}" class="h-48 w-full object-cover">
                    </div>
                    <div class="flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                            <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-2">{{ $product->name }}</h4>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <span class="font-bold text-gray-900 text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="{{ route('explore') }}" class="text-xs font-semibold text-blue-600 hover:underline">Detail &rarr;</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Seksi ABOUT -->
        <section id="about" class="bg-white/40 border-y border-white/60 py-20 mt-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Tentang Kami</span>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2 mb-6">Tentang Koperasi SMPK St. Agnes</h3>
                <p class="text-gray-600 leading-relaxed text-base mb-8">
                    Koperasi SMPK St. Agnes Surabaya berkomitmen menyediakan seluruh kebutuhan perlengkapan akademik murid secara lengkap, transparan, dan efisien. Dengan sistem digital ini, orang tua dan siswa dapat melihat ketersediaan stok seragam, buku, dan alat tulis secara langsung.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                    <div class="bg-white/80 p-6 rounded-2xl border border-white">
                        <h4 class="font-bold text-gray-900 mb-2">⚡ Cepat & Praktis</h4>
                        <p class="text-xs text-gray-500">Cek persediaan stok barang kapan saja tanpa perlu datang langsung ke sekolah.</p>
                    </div>
                    <div class="bg-white/80 p-6 rounded-2xl border border-white">
                        <h4 class="font-bold text-gray-900 mb-2">🏷️ Harga Resmi</h4>
                        <p class="text-xs text-gray-500">Seluruh harga produk sesuai dengan standar penetapan sekolah dan transparan.</p>
                    </div>
                    <div class="bg-white/80 p-6 rounded-2xl border border-white">
                        <h4 class="font-bold text-gray-900 mb-2">🎒 Terlengkap</h4>
                        <p class="text-xs text-gray-500">Menyediakan seragam resmi, modul pembelajaran, atribut, hingga makanan ringan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Seksi CONTACT -->
        <section id="contact" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Layanan Pelanggan</span>
            <h3 class="text-3xl font-extrabold text-gray-900 mt-2 mb-4">Hubungi Koperasi Sekolah</h3>
            <p class="text-gray-500 text-sm mb-10 max-w-xl mx-auto">
                Ada pertanyaan mengenai ketersediaan ukuran seragam atau pesanan khusus? Silakan hubungi tim kami melalui kontak di bawah ini.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto text-left">
                <div class="bg-white/80 p-6 rounded-2xl border border-white flex items-start gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Lokasi Sekolah</h4>
                        <p class="text-xs text-gray-500 mt-1">SMPK St. Agnes Surabaya<br>Jl. Mendut No.7, Surabaya, Jawa Timur</p>
                    </div>
                </div>
                <div class="bg-white/80 p-6 rounded-2xl border border-white flex items-start gap-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Jam Operasional</h4>
                        <p class="text-xs text-gray-500 mt-1">Senin - Jumat: 07.00 - 14.00 WIB<br>Sabtu - Minggu: Tutup</p>
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-guest-layout>