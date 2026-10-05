<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Koperasi SMPK St. Agnes') }}</title>

    <!-- Font Minimalis -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN & AlpineJS -->
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
    
    <!-- Navbar Utama -->
    <nav x-data="{ open: false }" class="bg-white/60 backdrop-blur-md sticky top-0 z-50 border-b border-white">
        <div class="w-full px-4 sm:px-6 lg:px-10">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight text-blue-900 shrink-0">
                    St. Agnes Coop.
                </a>
                
                <!-- Menu Desktop -->
                <div class="hidden md:flex space-x-8 items-center ml-10">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-900 border-b-2 border-gray-900 py-1">Home</a>
                    <a href="{{ route('home') }}#explore" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">Explore</a>
                    <a href="#about" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">About</a>
                    <a href="#contact" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">Contact</a>
                </div>

                <!-- Bagian Kanan: Autentikasi -->
                <div class="hidden md:flex items-center space-x-4 ml-auto">
                    @auth
                        <div class="flex items-center gap-3">
                            <!-- Jika Admin, boleh akses link ke Dashboard Admin dari sini -->
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="text-xs font-semibold text-white bg-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                                    Panel Admin
                                </a>
                            @endif

                            <!-- User Biasa: Hanya Nama Profil Saja (Tanpa Link Dashboard) -->
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

                <!-- Tombol Hamburger Mobile -->
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

        <!-- Menu Dropdown Mobile -->
        <div x-show="open" class="md:hidden bg-white/95 backdrop-blur-xl border-b border-gray-100 absolute w-full shadow-lg">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('home') }}" class="block px-3 py-3 text-base font-medium text-gray-900 bg-gray-50 rounded-lg">Home</a>
                <a href="{{ route('home') }}#explore" class="block px-3 py-3 text-base font-medium text-gray-600 hover:bg-gray-50 rounded-lg">Explore</a>
                <a href="#about" class="block px-3 py-3 text-base font-medium text-gray-600 hover:bg-gray-50 rounded-lg">About</a>
                
                <div class="border-t border-gray-200 pt-4 mt-2">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg mb-2 text-center">
                                Panel Admin
                            </a>
                        @endif
                        <div class="block px-3 py-2 text-base font-semibold text-blue-700 bg-blue-50 rounded-lg mb-2">
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

    <!-- Kandungan Utama -->
    <main class="flex-grow w-full">
        {{ $slot }}
    </main>

</body>
</html>