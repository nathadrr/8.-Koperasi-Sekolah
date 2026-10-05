<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk - Admin Koperasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased py-10">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
            <h1 class="text-xl font-bold text-gray-900 mb-6">Edit Produk: {{ $product->name }}</h1>

            <form action="{{ route('admin.inventory.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="name" value="{{ $product->name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stock" value="{{ $product->stock }}" required min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ $product->price }}" required min="0" step="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">{{ $product->description }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Gambar (Opsional)</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/'.$product->image) }}" class="w-16 h-16 rounded-lg object-cover border">
                        </div>
                    @endif
                    <input type="file" name="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 shadow-sm">Perbarui Produk</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>