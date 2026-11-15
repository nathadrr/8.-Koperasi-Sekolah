<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = [
            'Seragam' => [
                ['name' => 'Kemeja Seragam Putih', 'description' => 'Kemeja seragam sekolah putih.', 'price' => 95000, 'stock' => 20],
                ['name' => 'Celana Seragam Abu-Abu', 'description' => 'Celana seragam sekolah abu-abu.', 'price' => 110000, 'stock' => 15],
                ['name' => 'Rok Seragam Abu-Abu', 'description' => 'Rok seragam sekolah abu-abu.', 'price' => 105000, 'stock' => 15],
                ['name' => 'Baju Olahraga Sekolah', 'description' => 'Kaos olahraga untuk kegiatan sekolah.', 'price' => 85000, 'stock' => 20],
            ],
            'Buku & Modul' => [
                ['name' => 'Buku Tulis 38 Lembar', 'description' => 'Buku tulis untuk mencatat pelajaran.', 'price' => 5000, 'stock' => 100],
                ['name' => 'Buku Tulis 58 Lembar', 'description' => 'Buku tulis dengan halaman lebih banyak.', 'price' => 8000, 'stock' => 80],
                ['name' => 'Buku Gambar', 'description' => 'Buku gambar untuk pelajaran seni.', 'price' => 12000, 'stock' => 40],
                ['name' => 'Buku Agenda Siswa', 'description' => 'Buku agenda untuk mencatat tugas dan kegiatan.', 'price' => 18000, 'stock' => 30],
            ],
            'Alat Tulis' => [
                ['name' => 'Pensil 2B', 'description' => 'Pensil untuk menulis dan menggambar.', 'price' => 4000, 'stock' => 100],
                ['name' => 'Pulpen Biru', 'description' => 'Pulpen tinta biru untuk keperluan sekolah.', 'price' => 5000, 'stock' => 100],
                ['name' => 'Penghapus', 'description' => 'Penghapus pensil untuk alat tulis sehari-hari.', 'price' => 3000, 'stock' => 80],
                ['name' => 'Penggaris 30 cm', 'description' => 'Penggaris plastik 30 cm.', 'price' => 6000, 'stock' => 50],
                ['name' => 'Pensil Warna 12 Warna', 'description' => 'Set pensil warna untuk kegiatan seni.', 'price' => 25000, 'stock' => 25],
            ],
            'Atribut Sekolah' => [
                ['name' => 'Kaos Kaki Putih', 'description' => 'Kaos kaki putih untuk seragam sekolah.', 'price' => 18000, 'stock' => 30],
                ['name' => 'Ikat Pinggang Hitam', 'description' => 'Ikat pinggang hitam untuk seragam sekolah.', 'price' => 25000, 'stock' => 20],
                ['name' => 'Topi Sekolah', 'description' => 'Topi untuk kegiatan dan upacara sekolah.', 'price' => 35000, 'stock' => 20],
            ],
            'Makanan & Minuman' => [
                ['name' => 'Air Mineral 600 ml', 'description' => 'Air mineral dalam kemasan botol 600 ml.', 'price' => 5000, 'stock' => 48],
                ['name' => 'Roti Cokelat', 'description' => 'Roti isi cokelat untuk camilan.', 'price' => 7000, 'stock' => 30],
                ['name' => 'Biskuit Kemasan', 'description' => 'Biskuit kemasan untuk camilan di sekolah.', 'price' => 6000, 'stock' => 30],
            ],
        ];

        foreach ($catalog as $categoryName => $products) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($products as $product) {
                Product::firstOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $product['name'],
                    ],
                    [
                        ...$product,
                        'image' => null,
                    ],
                );
            }
        }
    }
}
