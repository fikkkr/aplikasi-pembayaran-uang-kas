<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;


// --- ROUTE AUTENTIKASI (GUEST / BELUM LOGIN) ---
Route::middleware('guest')->group(function () {
    // Akses awal dialihkan ke halaman login jika belum autentikasi
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    // Halaman Login & Proses Masuk
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProses'])->name('login.proses');
});


// --- ROUTE APLIKASI TERPROTEKSI (MUST AUTH / SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {

    // 1. Proses Keluar Aplikasi (Logout)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // 2. Halaman Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 3. Management Data Murid (CRUD)
    Route::resource('murid', MuridController::class)->except(['create', 'edit', 'show']);

    // 4. Management Transaksi Kas & Pembayaran
    // - Halaman Monitoring Utama (Tabel Kas Mingguan)
    Route::get('/monitoring-kas', [PembayaranController::class, 'index'])->name('pembayaran.index');

    // - Form & Proses Bayar Kas Murid (Mendukung Rapelan)
    Route::get('/pembayaran/bayar/{id_murid}', [PembayaranController::class, 'bayarKhusus'])->name('pembayaran.bayar');

    // - Form Pemasukan Umum (Luar) & Form Catat Pengeluaran Kelas (Layout Terpisah)
    Route::get('/pemasukan-umum', [PembayaranController::class, 'buatPemasukanLuar'])->name('pembayaran.umum');
    Route::get('/pengeluaran', [PembayaranController::class, 'buatPengeluaran'])->name('pembayaran.pengeluaran');

    // - Route Global Store untuk Memproses Semua Penyimpanan Transaksi (Masuk / Keluar)
    Route::post('/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store');

    // - Edit & Update Nominal Transaksi Kas
    Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');

    // 5. Management Periode Kas
    // Route Tampilkan Tabel Riwayat Periode (Bisa dibuka oleh Admin, Bendahara, dan GURU)
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');

    // 6. Laporan Kas Bulanan & Mingguan (Bisa dibuka oleh Admin, Bendahara, dan GURU)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Group Route Khusus Eksekusi Data (HANYA ADMIN & BENDAHARA, Guru Dilarang Masuk)
    Route::middleware(['can:kelola-kas'])->group(function () {
        // Proses simpan periode baru ke DB
        Route::post('/periode/store', [PeriodeController::class, 'store'])->name('periode.store');
        
        // Proses buka/tutup gembok otomatis
        Route::post('/periode/toggle/{id}', [PeriodeController::class, 'toggleStatus'])->name('periode.toggle');
    });

}); 