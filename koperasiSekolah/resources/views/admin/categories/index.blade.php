<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Kategori - Admin Koperasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-900">

    <!-- Sidebar Admin -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <span class="text-lg font-bold text-blue-800">Admin Panel</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Dashboard</a>
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Data Produk</a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium">Kelola Kategori</a>
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Data Transaksi</a>
        </nav>
        <div class="p-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-gray-50">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shrink-0">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen Kategori Produk</h1>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Lihat Katalog Web</a>
        </header>

        <div class="p-8 max-w-5xl">
            
            <!-- Alert Notifikasi -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Form Tambah Kategori -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm h-fit">
                    <h2 class="text-base font-bold text-gray-900 mb-4">Tambah Kategori Baru</h2>
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                            <input type="text" name="name" required placeholder="misal: Seragam, Snack..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 text-sm shadow-sm">
                            + Simpan Kategori
                        </button>
                    </form>
                </div>

                <!-- Tabel Daftar Kategori -->
                <div class="md:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="py-4 px-6">Nama Kategori</th>
                                <th class="py-4 px-6">Jumlah Produk</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($categories as $category)
                            <tr x-data="{ editing: false, name: '{{ $category->name }}' }" class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-medium text-gray-900">
                                    <span x-show="!editing">{{ $category->name }}</span>
                                    <form x-show="editing" action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" x-model="name" class="px-2 py-1 text-xs border rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <button type="submit" class="text-xs bg-green-600 text-white px-2 py-1 rounded">Simpan</button>
                                        <button type="button" @click="editing = false" class="text-xs bg-gray-300 text-gray-700 px-2 py-1 rounded">Batal</button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-gray-500">
                                    <span class="px-2.5 py-1 bg-gray-100 rounded-full text-xs font-medium">{{ $category->products_count }} item</span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-3">
                                    <button type="button" @click="editing = true" class="text-blue-600 hover:underline font-medium text-xs">Edit</button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-medium text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-gray-500">Belum ada kategori. Buat kategori pertama Anda di samping!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="p-4 border-t border-gray-100">
                        {{ $categories->links() }}
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>