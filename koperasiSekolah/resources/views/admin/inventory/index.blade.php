<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Produk - Admin Koperasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-900">

    <!-- Sidebar Admin Konsisten -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <span class="text-lg font-bold text-blue-800">Admin Panel</span>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Dashboard</a>
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium">Data Produk</a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Kelola Kategori</a>
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
            <h1 class="text-xl font-semibold text-gray-800">Data Produk / Inventory</h1>
            <a href="{{ route('admin.inventory.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                + Tambah Barang Baru
            </a>
        </header>

        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Gambar</th>
                            <th class="py-4 px-6">Nama Barang</th>
                            <th class="py-4 px-6">Kategori</th>
                            <th class="py-4 px-6">Harga</th>
                            <th class="py-4 px-6">Stok</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <img src="{{ $product->image ? (str_starts_with($product->image, 'http') ? $product->image : asset('storage/'.$product->image)) : 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?auto=format&fit=crop&w=100&q=80' }}" 
                                     class="w-12 h-12 rounded-lg object-cover border">
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                            <td class="py-4 px-6 text-gray-900 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $product->stock > 5 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->stock }} unit
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.inventory.edit', $product->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('admin.inventory.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">Belum ada data barang. Silakan tambah barang baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </main>
</body>
</html>