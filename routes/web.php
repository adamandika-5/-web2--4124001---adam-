<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SewaAlatController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\SewaController as AdminSewaController;
use App\Http\Controllers\Admin\PengirimanController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PromoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StokController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\PengaturanController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::get('/promo', [BerandaController::class, 'promo'])->name('promo');
Route::get('/promo/{slug}', [BerandaController::class, 'promoShow'])->name('promo.show');
Route::get('/lacak', [PesananController::class, 'lacak'])->name('lacak');
Route::get('/lacak/{nomor}', [PesananController::class, 'lacakShow'])->name('lacak.show');

// Katalog
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog.index');
Route::get('/produk/{slug}', [ProdukController::class, 'show'])->name('produk.show');

// Sewa Alat
Route::get('/sewa-alat', [SewaAlatController::class, 'index'])->name('sewa.index');
Route::get('/sewa-alat/{slug}', [SewaAlatController::class, 'show'])->name('sewa.show');
Route::get('/sewa-alat/{slug}/kalkulasi', [SewaAlatController::class, 'kalkulasi'])->name('sewa.kalkulasi');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/masuk', [LoginController::class, 'login']);
    Route::get('/daftar', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register']);
    Route::get('/lupa-sandi', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/lupa-sandi', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-sandi/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-sandi', [ForgotPasswordController::class, 'reset'])->name('password.update');
    // Login via Google (OAuth)
    Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);
});

Route::post('/keluar', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| USER ROUTES (perlu login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:user,admin'])->group(function () {

    // Keranjang
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('/keranjang/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('/keranjang', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'proses'])->name('checkout.proses');
    Route::get('/checkout/selesai/{nomor}', [CheckoutController::class, 'selesai'])->name('checkout.selesai');
    Route::post('/checkout/ongkir', [CheckoutController::class, 'hitungOngkir'])->name('checkout.ongkir');
    Route::post('/checkout/voucher', [CheckoutController::class, 'cekVoucher'])->name('checkout.voucher');

    // Pesanan
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{nomor}', [PesananController::class, 'show'])->name('pesanan.show');
    Route::post('/pesanan/{nomor}/bayar', [PesananController::class, 'uploadBukti'])->name('pesanan.bayar');
    Route::post('/pesanan/{nomor}/batal', [PesananController::class, 'batal'])->name('pesanan.batal');
    Route::get('/pesanan/{nomor}/invoice', [PesananController::class, 'invoice'])->name('pesanan.invoice');

    // Sewa Alat (authenticated)
    Route::post('/sewa-alat/{slug}/booking', [SewaAlatController::class, 'booking'])->name('sewa.booking');
    Route::get('/sewa-saya', [SewaAlatController::class, 'riwayat'])->name('sewa.riwayat');

    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    // Profil
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/sandi', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('/profil/alamat', [ProfilController::class, 'alamat'])->name('profil.alamat');
    Route::post('/profil/alamat', [ProfilController::class, 'tambahAlamat'])->name('profil.alamat.store');
    Route::delete('/profil/alamat/{id}', [ProfilController::class, 'hapusAlamat'])->name('profil.alamat.delete');

});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('produk', AdminProdukController::class);
    Route::patch('produk/{produk}/toggle-aktif', [AdminProdukController::class, 'toggleAktif'])->name('produk.toggleAktif');
    Route::delete('produk/gambar/{gambar}', [AdminProdukController::class, 'hapusGambar'])->name('produk.hapusGambar');
    Route::get('produk/export', [AdminProdukController::class, 'export'])->name('produk.export');

    // Kategori
    Route::resource('kategori', AdminKategoriController::class);
    Route::resource('sub-kategori', AdminKategoriController::class . 'Sub');

    // Stok
    Route::get('stok', [StokController::class, 'index'])->name('stok.index');
    Route::post('stok/{produk}/tambah', [StokController::class, 'tambah'])->name('stok.tambah');
    Route::post('stok/{produk}/kurang', [StokController::class, 'kurang'])->name('stok.kurang');
    Route::get('stok/laporan', [StokController::class, 'laporan'])->name('stok.laporan');

    // Pesanan
    Route::resource('pesanan', AdminPesananController::class)->only(['index','show','update']);
    Route::patch('pesanan/{pesanan}/status', [AdminPesananController::class, 'updateStatus'])->name('pesanan.status');
    Route::get('pesanan/export', [AdminPesananController::class, 'export'])->name('pesanan.export');

    // Pembayaran
    Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
    Route::patch('pembayaran/{pembayaran}/konfirmasi', [AdminPembayaranController::class, 'konfirmasi'])->name('pembayaran.konfirmasi');
    Route::patch('pembayaran/{pembayaran}/tolak', [AdminPembayaranController::class, 'tolak'])->name('pembayaran.tolak');

    // Sewa Alat
    Route::resource('sewa', AdminSewaController::class);
    Route::patch('sewa/{alat}/toggle-status', [AdminSewaController::class, 'toggleStatus'])->name('sewa.toggleStatus');
    Route::get('sewa/booking', [AdminSewaController::class, 'booking'])->name('sewa.booking');
    Route::patch('sewa/booking/{booking}/selesai', [AdminSewaController::class, 'selesaiBooking'])->name('sewa.booking.selesai');
    Route::patch('sewa/booking/{booking}/denda', [AdminSewaController::class, 'catatDenda'])->name('sewa.booking.denda');

    // Pengiriman Armada
    Route::resource('pengiriman', PengirimanController::class)->only(['index','show','update']);
    Route::get('pengiriman/ongkir', [PengirimanController::class, 'ongkir'])->name('pengiriman.ongkir');
    Route::post('pengiriman/ongkir/simpan', [PengirimanController::class, 'simpanOngkir'])->name('pengiriman.ongkir.simpan');

    // Supplier
    Route::resource('supplier', SupplierController::class);

    // Promo & Voucher
    Route::resource('promo', PromoController::class);
    Route::resource('voucher', PromoController::class . 'Voucher');

    // Manajemen User
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-aktif', [UserController::class, 'toggleAktif'])->name('users.toggleAktif');
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');

    // Activity Log
    Route::get('activity-log', [ActivityLogController::class, 'index'])->name('activity-log');
    Route::delete('activity-log/hapus', [ActivityLogController::class, 'hapusSemua'])->name('activity-log.hapus');

    // Pengaturan
    Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    // Laporan
    Route::get('laporan', [DashboardController::class, 'laporan'])->name('laporan');
    Route::get('laporan/export-pdf', [DashboardController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('laporan/export-excel', [DashboardController::class, 'exportExcel'])->name('laporan.excel');

});