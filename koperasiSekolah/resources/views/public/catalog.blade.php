@extends('layouts.public')

@section('content')
    <!-- HERO SECTION -->
    <section class="px-8 py-20 text-center">
        <h1 class="text-7xl md:text-9xl font-bold text-gray-900 tracking-tighter mb-6 leading-tight">
            WELCOME TO<br><span class="text-blue-600">Koperasi SMPK St. Agnes Surabaya</span>
        </h1>
        <p class="text-gray-500 max-w-xl mx-auto text-lg mb-10 leading-relaxed">
            Digitalisasi kebutuhan akademik SMPK Santa Agnes. Cepat, transparan, dan mudah diakses oleh seluruh warga sekolah.
        </p>
        <div class="flex justify-center gap-4">
            <a href="#products" class="bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-blue-600 transition-all shadow-xl">Explore Products</a>
        </div>
    </section>

    <!-- PRODUCT SECTION -->
    <section id="products" class="px-8 py-20">
        <div class="flex justify-between items-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Available Supplies</h2>
            <div class="flex gap-2">
                @foreach($categories as $cat)
                    <a href="?category={{ $cat->id }}" class="px-4 py-2 rounded-full text-sm border border-gray-200 hover:bg-blue-600 hover:text-white transition-all">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-10">
            @foreach($products as $product)
            <div class="group cursor-pointer">
                <div class="relative aspect-square bg-gray-100 rounded-3xl overflow-hidden mb-4 transition-all group-hover:shadow-2xl group-hover:-translate-y-2">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    <div class="absolute top-4 right-4 bg-white/80 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold text-gray-900">
                        Rp {{ number_format($product->price) }}
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-600 transition">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $product->category->name }}</p>
                <button class="w-full py-3 rounded-2xl border border-gray-200 text-sm font-medium group-hover:bg-gray-900 group-hover:text-white transition-all">
                    Add to Cart
                </button>
            </div>
            @endforeach
        </div>
    </section>
@endsection
