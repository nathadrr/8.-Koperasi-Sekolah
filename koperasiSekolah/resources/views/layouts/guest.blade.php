<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Koperasi SMPK St. Agnes') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #e8f7ec 0%, #f0f7f4 30%, #fdf4f6 70%, #fef1f2 100%);
            background-attachment: fixed;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="text-gray-900 antialiased min-h-screen flex flex-col">
    
    <nav x-data="{ open: false }" class="bg-white/60 backdrop-blur-md sticky top-0 z-50 border-b border-white">
        <div class="w-full px-4 sm:px-6 lg:px-10">
            <div class="flex justify-between h-16 items-center">
                
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-blue-900 shrink-0">
                    St. Agnes Coop.
                </a>
                
                <div class="hidden md:flex space-x-8 items-center ml-10">
                    <a href="{{ route('home') }}" class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }} py-1 transition">Home</a>
                    <a href="{{ route('explore') }}" class="text-sm font-semibold {{ request()->routeIs('explore') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900' }} py-1 transition">Explore</a>
                    <a href="{{ route('home') }}#about" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">About</a>
                    <a href="{{ route('home') }}#contact" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">Contact</a>
                </div>

                <div class="hidden md:flex items-center space-x-4 ml-auto">
                    @auth
                        <!-- Ikon Keranjang Belanja -->
                        <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-700 hover:text-blue-600 transition" title="Keranjang Belanja">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @if(count(session('cart', [])) > 0)
                                <span class="absolute top-0 right-0 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>

                        <!-- Link Riwayat Belanja -->
                        <a href="{{ route('history.index') }}" class="text-sm font-medium {{ request()->routeIs('history.index') ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }} transition">
                            Riwayat Belanja
                        </a>

                        <div class="flex items-center gap-3 pl-2 border-l border-gray-200">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-white bg-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                                    Panel Admin
                                </a>
                            @endif

                            <span class="text-sm font-semibold text-blue-700 bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
                                👤 {{ Auth::user()->name }}
                            </span>
                            
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-blue-600 transition shadow-md">Sign Up</a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="open = ! open" class="text-gray-900 focus:outline-none p-2">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="open" class="md:hidden bg-white/95 backdrop-blur-xl border-b border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-lg">Home</a>
                <a href="{{ route('explore') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-lg">Explore</a>
                <a href="{{ route('home') }}#about" class="block px-3 py-3 text-base font-medium text-gray-600 hover:bg-gray-50 rounded-lg">About</a>
                <a href="{{ route('home') }}#contact" class="block px-3 py-3 text-base font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Contact</a>
                
                <div class="border-t border-gray-200 pt-4 mt-2">
                    @auth
                        <a href="{{ route('cart.index') }}" class="block px-3 py-2.5 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-lg flex items-center justify-between">
                            <span>🛒 Keranjang Belanja</span>
                            @if(count(session('cart', [])) > 0)
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                    {{ count(session('cart', [])) }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('history.index') }}" class="block px-3 py-2.5 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-lg">
                            📜 Riwayat Belanja
                        </a>

                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg my-2 text-center">
                                Panel Admin
                            </a>
                        @endif
                        <div class="block px-3 py-2 text-base font-semibold text-blue-700 bg-blue-50 rounded-lg my-2">
                            👤 {{ Auth::user()->name }}
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-3 text-base font-medium text-red-500 hover:bg-red-50 rounded-lg">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-3 text-base font-medium text-gray-900 hover:bg-gray-50 rounded-lg">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-3 mt-2 text-base font-medium bg-gray-900 text-white text-center rounded-lg">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

</body>
</html>