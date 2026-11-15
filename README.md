# 8.-Koperasi-Sekolah

## Pembayaran Midtrans

Atur `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, dan `MIDTRANS_SIGNATURE_KEY` pada `koperasiSekolah/.env` dari dashboard Midtrans. `MIDTRANS_SIGNATURE_KEY` adalah Signature Secret Key untuk verifikasi notifikasi. Mulai dengan `MIDTRANS_IS_PRODUCTION=false`; gunakan `true` hanya setelah beralih ke kredensial produksi. Setelah mengubah `.env`, jalankan `php artisan config:clear` dari direktori `koperasiSekolah`.

Atur URL notifikasi pada dashboard Midtrans ke `https://<domain>/midtrans/notification`. Endpoint token pembayaran hanya tersedia untuk pengguna login. Transaksi online dibuat dari isi keranjang dan statusnya diperbarui melalui notifikasi Midtrans.

## Data katalog contoh

Seeder katalog menambahkan contoh barang dan kategori koperasi sekolah. Jalankan `php artisan db:seed --class=CatalogSeeder` dari direktori `koperasiSekolah`. Seeder dapat dijalankan berulang tanpa membuat produk contoh duplikat atau menimpa perubahan produk yang sudah ada; harga dan stok dapat disesuaikan dari panel admin.