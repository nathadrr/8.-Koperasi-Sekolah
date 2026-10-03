<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Koperasi St. Agnes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FBFBFC; }
        .sidebar-item:hover { background-color: #F3F4F6; color: #2563EB; }
        .sidebar-item.active { background-color: #EFF6FF; color: #2563EB; font-weight: 600; }
    </style>
</head>
<body class="text-gray-800">
    <div class="flex min-h-screen">
        <!-- SIDEBAR NAVIGATION -->
        <aside class="w-72 bg-white border-r border-gray-100 flex flex-col sticky top-0 h-screen">
            <div class="p-8">
                <h1 class="text-xl font-bold text-blue-600 tracking-tight">Koperasi <span class="text-gray-400">Admin</span></h1>
            </div>

            <nav class="flex-1 px-4 space-y-8">
                <!-- Section 1: Inventory -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Inventory</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.inventory.index') }}" class="sidebar-item flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            Kategori Barang
                        </a>
                        <a href="#" class="sidebar-item flex items-center px-4 py-3 rounded-2xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            Jenis Barang
                        </a>
                    </div>
                </div>

                <!-- Section 2: Transaction -->
                <div>
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Finance</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.reports') }}" class="sidebar-item flex items-center px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Detail Transaksi
                        </a>
                    </div>
                </div>
            </nav>

            <div class="p-6 border-t border-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center w-full px-4 py-3 text-sm font-medium text-red-500 hover:bg-red-50 rounded-2xl transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">
            <!-- TOP BAR -->
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 flex justify-between items-center sticky top-0 z-10">
                <h2 class="text-lg font-semibold text*
