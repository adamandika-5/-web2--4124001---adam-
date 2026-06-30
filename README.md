# Sinar Alam Material

Sinar Alam Material adalah aplikasi web toko material bangunan berbasis Laravel yang digunakan untuk membantu pelanggan melihat katalog produk, melakukan pemesanan material, menyewa alat bangunan, menggunakan promo/voucher, serta memantau status pesanan secara online.

Website ini juga menyediakan panel administrator untuk mengelola produk, kategori, stok, pesanan, pembayaran, promo, sewa alat, supplier, pengguna, dan pengaturan toko.

URL Live

Website dapat diakses melalui:

https://sinaralammaterial.my.id

Fitur Utama
 
Fitur Pengguna
- Melihat halaman beranda toko
- Melihat katalog produk material bangunan
- Melihat detail produk
- Menambahkan produk ke keranjang
- Menambahkan produk ke wishlist
- Melakukan checkout pesanan
- Melacak status pesanan
- Melihat promo dan voucher
- Melakukan pemesanan sewa alat bangunan
- Mengelola profil pengguna
- Mengelola alamat pengiriman

Fitur Admin
- Dashboard ringkasan data toko
- Manajemen produk
- Manajemen kategori dan sub-kategori
- Manajemen stok dan gudang
- Manajemen pesanan
- Verifikasi pembayaran
- Manajemen promo dan voucher
- Manajemen sewa alat
- Manajemen supplier
- Manajemen user
- Activity log
- Pengaturan toko, kontak, pembayaran, pengiriman, notifikasi, SEO, dan tampilan

Teknologi yang Digunakan

- Laravel 12
- PHP 8.2
- MySQL
- Blade Template
- HTML
- CSS
- JavaScript
- Nginx / Web Server Hosting
- Git dan GitHub

Cara Menjalankan Project di Lokal

1. Clone Repository

```bash
git clone https://github.com/adamandika-5/-web2--4124001---adam-.git

cd -web2--4124001---adam-
```

2. Install Dependency Laravel

```bash
composer install
```

3. Salin File Environment

```bash
cp .env.example .env
```

4. Generate Application Key

```bash
php artisan key:generate
```

5. Atur Database

Buat database MySQL baru, lalu sesuaikan konfigurasi pada file `.env`:

.env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=c211sinar_alam
DB_USERNAME=c211user367546
DB_PASSWORD=Adamandika1


6. Jalankan Migrasi dan Seeder

```bash
php artisan migrate --seed
```

7. Jalankan Storage Link

```bash
php artisan storage:link
```

8. Jalankan Server Lokal

```bash
php artisan serve
```

Akses aplikasi melalui:

http://127.0.0.1:8000


Akun Demo

Admin

Email    : adam@sinaralam.id
Password : password 

User

Email    : user@sinaralam.id
Password : Adamandika1


Ringkasan Deployment

Aplikasi dideploy pada layanan hosting dengan domain aktif:

https://sinaralammaterial.my.id

Langkah deployment yang dilakukan:

1. Mengunggah project Laravel ke server hosting.
2. Mengatur folder public Laravel sebagai document root.
3. Mengatur file `.env` untuk mode production.
4. Menghubungkan aplikasi dengan database MySQL hosting.
5. Menjalankan instalasi dependency menggunakan Composer.
6. Menjalankan optimasi konfigurasi Laravel.
7. Mengaktifkan SSL/HTTPS.
8. Menguji halaman user dan admin agar dapat diakses secara online.

Kendala Deployment

Beberapa kendala yang ditemukan saat deployment:

* Error 500 karena path Laravel belum sesuai.
* Folder `vendor` belum lengkap.
* Asset CSS tidak tampil karena konfigurasi URL belum sesuai HTTPS.
* Perlu penyesuaian responsive mobile pada beberapa halaman.

Solusi Deployment

Solusi yang dilakukan:

* Memperbaiki path `public/index.php`.
* Menjalankan ulang instalasi dependency dengan Composer.
* Mengatur `APP_URL` dan `ASSET_URL` menggunakan HTTPS.
* Membersihkan cache Laravel dengan perintah:

```bash
php artisan optimize:clear
```

* Memperbaiki tampilan responsive melalui file Blade dan CSS.

Struktur Role

Aplikasi memiliki dua role utama:

1. Admin
   Mengelola data toko, produk, pesanan, pembayaran, promo, stok, user, dan pengaturan.

2. User
   Melihat produk, melakukan pemesanan, menyewa alat, menyimpan wishlist, dan melacak pesanan.

Pengembang

Project ini dibuat untuk memenuhi tugas akhir mata kuliah Pemrograman Web berbasis Laravel.