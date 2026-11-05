<!-- Sidebar Admin Panel -->
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
    <div class="h-16 flex items-center px-6 border-b border-gray-200">
        <span class="text-lg font-bold text-blue-800">Admin Panel</span>
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <!-- Data Produk -->
        <a href="{{ route('admin.inventory.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all {{ request()->routeIs('admin.inventory.*') || request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            📦 Data Produk
        </a>
        
        <!-- Kelola Kategori -->
        <a href="{{ route('admin.categories.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            🏷️ Kelola Kategori
        </a>
        
        <!-- Data Transaksi -->
        <a href="{{ route('admin.reports') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition-all {{ request()->routeIs('admin.reports') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
            📋 Data Transaksi
        </a>
    </nav>

    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg font-medium transition">
                🚪 Logout
            </button>
        </form>
    </div>
</aside>