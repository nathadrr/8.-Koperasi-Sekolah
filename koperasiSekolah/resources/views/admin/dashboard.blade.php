<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - Koperasi St. Agnes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-900">

    <!-- Sidebar Admin -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <span class="text-lg font-bold text-blue-800">Admin Panel</span>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <!-- Link Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <!-- Link Kelola Produk -->
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Data Produk
            </a>

            <!-- Link Transaksi -->
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg font-medium transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Data Transaksi
            </a>
        </nav>

        <!-- Logout Area -->
        <div class="p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- Area Konten Utama -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-50">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h1 class="text-xl font-semibold text-gray-800">Overview</h1>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Halo, {{ Auth::user()->name }}</span>
                <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Lihat Katalog Web</a>
            </div>
        </header>

        <!-- Isi Konten -->
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card Statistik Cepat -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Total Produk</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">124</p> <!-- Nanti ganti dengan count dari database -->
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Pesanan Masuk</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">12</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Pendapatan</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Rp 2.450.000</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Selamat datang di Panel Admin</h2>
                <p class="text-gray-600">Gunakan navigasi di sebelah kiri untuk menambah/mengedit barang di katalog dan memproses transaksi yang masuk dari siswa.</p>
            </div>
        </div>
    </main>

</body>
</html>