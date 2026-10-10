<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Transaksi - Admin Koperasi</title>
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
            <a href="{{ route('admin.inventory.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Data Produk</a>
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-medium">Kelola Kategori</a>
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2 bg-blue-50 text-blue-700 rounded-lg font-medium">Data Transaksi</a>
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
            <h1 class="text-xl font-semibold text-gray-800">Laporan Penjualan & Transaksi</h1>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">Lihat Katalog Web</a>
        </header>

        <div class="p-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Ringkasan Laporan Penjualan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pendapatan (Selesai)</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalTransactions }} Pesanan</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Perlu Diproses (Pending)</p>
                    <p class="text-2xl font-bold text-amber-500 mt-2">{{ $pendingCount }} Pesanan</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi Selesai</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">{{ $completedCount }} Pesanan</p>
                </div>
            </div>

            <!-- Filter Transaksi -->
            <div class="bg-white p-4 rounded-2xl border border-gray-200 shadow-sm mb-6">
                <form action="{{ route('admin.reports') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status Transaksi</label>
                        <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas / Dibayar</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Filter Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white outline-none">
                    </div>

                    @if(request('status') || request('date'))
                        <div class="self-end">
                            <a href="{{ route('admin.reports') }}" class="text-xs text-red-600 hover:underline font-medium">Reset Filter</a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Tabel Data Transaksi -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="py-4 px-6">Kode & Tanggal</th>
                            <th class="py-4 px-6">Pembeli</th>
                            <th class="py-4 px-6">Produk</th>
                            <th class="py-4 px-6">Total Harga</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-right">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6">
                                <span class="font-bold text-gray-900 block">{{ $trx->transaction_code }}</span>
                                <span class="text-xs text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }} WIB</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900 block">{{ $trx->buyer_name ?? $trx->user->name ?? 'Guest' }}</span>
                                <span class="text-xs text-gray-500">{{ $trx->buyer_class ?? 'Siswa' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-900 block">{{ $trx->product->name ?? 'Produk Dihapus' }}</span>
                                <span class="text-xs text-gray-500">{{ $trx->quantity }} unit x Rp {{ number_format($trx->product->price ?? 0, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-4 px-6 font-bold text-gray-900">
                                Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($trx->status === 'pending')
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                @elseif($trx->status === 'paid')
                                    <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Lunas</span>
                                @elseif($trx->status === 'completed')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Batal</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.transactions.updateStatus', $trx->id) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-300 rounded-lg px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="pending" {{ $trx->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $trx->status === 'paid' ? 'selected' : '' }}>Lunas</option>
                                        <option value="completed" {{ $trx->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $trx->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">Belum ada transaksi recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </main>
</body>
</html>