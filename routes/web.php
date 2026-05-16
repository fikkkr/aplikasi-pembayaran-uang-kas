<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;


// --- HALAMAN UTAMA & DASHBOARD ---
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);


// --- MANAGEMENT DATA MURID (CRUD) ---
Route::resource('murid', MuridController::class)->except(['create', 'edit', 'show']);


// --- MANAGEMENT TRANSAKSI KAS & PEMBAYARAN ---
// 1. Halaman Monitoring Utama (Tabel Kas Mingguan)
Route::get('/monitoring-kas', [PembayaranController::class, 'index'])->name('pembayaran.index');

// 2. Form & Proses Bayar Kas Murid (Mendukung Rapelan)
Route::get('/pembayaran/bayar/{id_murid}', [PembayaranController::class, 'bayarKhusus'])->name('pembayaran.bayar');

// 3. Form Pemasukan Umum (Luar) & Form Catat Pengeluaran Kelas (Layout Terpisah)
Route::get('/pemasukan-umum', [PembayaranController::class, 'buatPemasukanLuar'])->name('pembayaran.umum');
Route::get('/pengeluaran', [PembayaranController::class, 'buatPengeluaran'])->name('pembayaran.pengeluaran');

// 4. Route Global Store untuk Memproses Semua Penyimpanan Transaksi (Masuk / Keluar)
Route::post('/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store');

// 5. Edit & Update Nominal Transaksi Kas
Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');


// --- MANAGEMENT PERIODE KAS ---
// Form Tambah Periode Baru & Proses Simpan Datanya
Route::get('/periode/create', [PembayaranController::class, 'createPeriode'])->name('periode.create');
Route::post('/periode/store', [PembayaranController::class, 'storePeriode'])->name('periode.store');


// --- SISTEM AUTENTIKASI (AUTH) ---
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');