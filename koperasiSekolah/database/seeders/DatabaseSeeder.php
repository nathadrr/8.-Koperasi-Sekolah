<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::firstOrCreate(
            ['email' => 'admin@stagnes.sch.id'],
            [
                'name' => 'Admin Koperasi',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'siswa@stagnes.sch.id'],
            [
                'name' => 'Siswa St. Agnes',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // Kategori Produk Koperasi
        $categoriesData = [
            'Seragam & Atribut',
            'Alat Tulis & Kantor',
            'Buku & Modul',
            'Makanan & Minuman',
            'Perlengkapan Olahraga & Pramuka',
        ];

        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[$catName] = Category::firstOrCreate(['name' => $catName]);
        }

        // 30 Data Dummy Produk
        $products = [
            // --- Seragam & Atribut ---
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Seragam Putih Biru SMP (Atasan Lengan Pendek)',
                'price' => 75000,
                'stock' => 35,
                'description' => 'Baju seragam sekolah atasan putih bahan katun berkualitas, adem dan nyaman dipakai harian.',
                'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Celana Biru SMP Panjang',
                'price' => 85000,
                'stock' => 25,
                'description' => 'Celana seragam biru tua SMP bahan Drill tebal dan tidak mudah kusut.',
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Rok Biru SMP Rempel Panjang',
                'price' => 85000,
                'stock' => 28,
                'description' => 'Rok rempel biru SMP bahan berkwalitas tinggi dengan karet pinggang fleksibel.',
                'image' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Dasi Sekolah Logo St. Agnes',
                'price' => 15000,
                'stock' => 50,
                'description' => 'Dasi seragam bordir logo resmi SMPK St. Agnes Surabaya.',
                'image' => 'https://images.unsplash.com/photo-1589756823695-278bc923f962?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Topi Sekolah SMP Logo St. Agnes',
                'price' => 25000,
                'stock' => 40,
                'description' => 'Topi sekolah biru putih bahan Drill dengan bordir presisi St. Agnes.',
                'image' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Ikat Pinggang Hitam Logo OSIS',
                'price' => 20000,
                'stock' => 30,
                'description' => 'Sabuk sekolah gesper roda bahan sintetis tebal dan awet.',
                'image' => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Kaos Kaki Putih Logo St. Agnes (Pasang)',
                'price' => 12000,
                'stock' => 60,
                'description' => 'Kaos kaki putih di atas mata kaki bahan katun tebal tidak panas.',
                'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Seragam & Atribut']->id,
                'name' => 'Badge Bet Nama & OSIS Bordir (Set)',
                'price' => 10000,
                'stock' => 100,
                'description' => 'Paket badge lokasi sekolah, bendera merah putih, dan logo OSIS bordir.',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=500&auto=format&fit=crop&q=80',
            ],

            // --- Alat Tulis & Kantor ---
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Buku Tulis Sinar Dunia 38 Lembar (Pack 10 Pcs)',
                'price' => 38000,
                'stock' => 20,
                'description' => 'Buku tulis SIDU kualitas kertas putih dan tebal isi 10 buku per pack.',
                'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Buku Tulis Vision 58 Lembar (Pack 10 Pcs)',
                'price' => 45000,
                'stock' => 15,
                'description' => 'Buku tulis tebal 58 lembar cocok untuk catat pelajaran penuh semester.',
                'image' => 'https://images.unsplash.com/photo-1517842645767-c639042777db?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Pulpen Gel Joyko Black 0.5mm (Box 12 Pcs)',
                'price' => 30000,
                'stock' => 25,
                'description' => 'Pulpen gel hitam pekat tidak macet saat digunakan menulis cepat.',
                'image' => 'https://images.unsplash.com/photo-1585336261026-6757f5767b1b?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Pensil 2B Faber-Castell Original',
                'price' => 5000,
                'stock' => 100,
                'description' => 'Pensil kayu 2B standar ujian nasional, mudah diarsir dan tidak gampang patah.',
                'image' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Penghapus Faber-Castell EB-20',
                'price' => 4000,
                'stock' => 80,
                'description' => 'Penghapus pensil hitam lembut tanpa merusak lembar kertas.',
                'image' => 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Penggaris Plastik Transparan 30cm Joyko',
                'price' => 3500,
                'stock' => 45,
                'description' => 'Penggaris transparan dengan skala milimeter yang akurat.',
                'image' => 'https://images.unsplash.com/photo-1588072432836-e10032774350?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Correction Tape / Tipe-X Joyko',
                'price' => 8000,
                'stock' => 30,
                'description' => 'Pita koreksi tulisan kering tanpa perlu menunggu basah.',
                'image' => 'https://images.unsplash.com/photo-1600132806370-bf17e65e942f?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Binder B5 26 Ring Pastel Edition',
                'price' => 35000,
                'stock' => 12,
                'description' => 'Map binder catat pelajaran B5 warna pastel elegan dan kokoh.',
                'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Kertas Loose Leaf B5 100 Lembar',
                'price' => 18000,
                'stock' => 30,
                'description' => 'Isi ulang kertas binder B5 garis halus tebal 70 gsm.',
                'image' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Alat Tulis & Kantor']->id,
                'name' => 'Stabilo Boss Original Highlighter Pastel',
                'price' => 12000,
                'stock' => 40,
                'description' => 'Penanda teks warna pastel lembut tidak menembus kertas.',
                'image' => 'https://images.unsplash.com/photo-1516962215378-7fa2e137ae93?w=500&auto=format&fit=crop&q=80',
            ],

            // --- Buku & Modul ---
            [
                'category_id' => $categories['Buku & Modul']->id,
                'name' => 'Modul Pendamping Matematika Kelas 8 SMP',
                'price' => 55000,
                'stock' => 50,
                'description' => 'Buku modul latihan soal & pembahasan Matematika khusus Kurikulum Merdeka.',
                'image' => 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Buku & Modul']->id,
                'name' => 'Modul Bahasa Inggris SMP Kelas 7',
                'price' => 50000,
                'stock' => 45,
                'description' => 'Buku modul latihan tata bahasa dan pembacaan teks Inggris.',
                'image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Buku & Modul']->id,
                'name' => 'Kamus Bahasa Inggris - Indonesia (3 Milyar)',
                'price' => 45000,
                'stock' => 15,
                'description' => 'Kamus lengkap dengan tenses, regular irregular verbs untuk pelajar.',
                'image' => 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Buku & Modul']->id,
                'name' => 'Buku Gambar A4 Paperline',
                'price' => 8000,
                'stock' => 35,
                'description' => 'Buku menggambar ukuran A4 serat tebal bagus untuk pensil warna & krayon.',
                'image' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=500&auto=format&fit=crop&q=80',
            ],

            // --- Makanan & Minuman ---
            [
                'category_id' => $categories['Makanan & Minuman']->id,
                'name' => 'Air Mineral Aqua Botol 600ml',
                'price' => 4000,
                'stock' => 120,
                'description' => 'Air minum pegunungan murni siap menyegarkan dahaga saat istirahat.',
                'image' => 'https://images.unsplash.com/photo-1560023907-5f339617ea30?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Makanan & Minuman']->id,
                'name' => 'Roti Manis Cokelat Koperasi',
                'price' => 5000,
                'stock' => 30,
                'description' => 'Roti olahan fresh buatan harian dengan isian cokelat lumer lezat.',
                'image' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Makanan & Minuman']->id,
                'name' => 'Susu UHT Ultra Milk Cokelat 200ml',
                'price' => 6000,
                'stock' => 40,
                'description' => 'Susu sapi bernutrisi kaya kalsium dan rasa cokelat nikmat.',
                'image' => 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Makanan & Minuman']->id,
                'name' => 'Teh Botol Sosro 350ml',
                'price' => 5000,
                'stock' => 50,
                'description' => 'Minuman teh melati khas alami dalam kemasan botol segar.',
                'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=500&auto=format&fit=crop&q=80',
            ],

            // --- Perlengkapan Olahraga & Pramuka ---
            [
                'category_id' => $categories['Perlengkapan Olahraga & Pramuka']->id,
                'name' => 'Set Kacu & Ring Pramuka Penggalang',
                'price' => 15000,
                'stock' => 35,
                'description' => 'Set dukun / kacu merah putih beserta ring kaleng lambang Pramuka.',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623266010b?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Perlengkapan Olahraga & Pramuka']->id,
                'name' => 'Peluit ACME Pramuka Nyaring',
                'price' => 8000,
                'stock' => 40,
                'description' => 'Peluit tiup suara nyaring untuk latihan baris-berbaris dan kegiatan outdoor.',
                'image' => 'https://images.unsplash.com/photo-1511886929837-354d827aae26?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Perlengkapan Olahraga & Pramuka']->id,
                'name' => 'Tali Pramuka Marson 5 Meter',
                'price' => 12000,
                'stock' => 25,
                'description' => 'Tali tambang katun putih tebal untuk ikatan pionering pramuka.',
                'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'category_id' => $categories['Perlengkapan Olahraga & Pramuka']->id,
                'name' => 'Bola Voli Mikasa Training',
                'price' => 150000,
                'stock' => 5,
                'description' => 'Bola olahraga voli empuk berbahan kulit sintetis untuk latihan sekolah.',
                'image' => 'https://images.unsplash.com/photo-1612872087720-bb876e2e67d1?w=500&auto=format&fit=crop&q=80',
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}