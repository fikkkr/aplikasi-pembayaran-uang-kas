<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

// Akses langsung ke Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Route Murid (CRUD)
Route::resource('murid', MuridController::class)->except(['create', 'edit', 'show']);

// Route untuk menampilkan form edit nominal kas murid
Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');

// Route untuk memproses update data nominal
Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');

// --- ROUTE PEMBAYARAN & PERIODE ---
// Halaman Monitoring Utama dengan Dropdown Periode
Route::get('/monitoring-kas', [PembayaranController::class, 'index'])->name('pembayaran.index');

// Route Bayar Kas Murid (Melempar id_murid)
Route::get('/pembayaran/bayar/{id_murid}', [PembayaranController::class, 'bayarKhusus'])->name('pembayaran.bayar');

// Route Pemasukan Umum & Pengeluaran
Route::get('/pemasukan-umum', [PembayaranController::class, 'buatPemasukanLuar'])->name('pembayaran.umum');
Route::get('/pengeluaran', [PembayaranController::class, 'buatPengeluaran'])->name('pembayaran.pengeluaran');

// Route untuk menampilkan halaman/form tambah periode (jika tidak pakai modal)
Route::get('/periode/create', [PembayaranController::class, 'createPeriode'])->name('periode.create');
// Route untuk memproses penyimpanan periode baru
Route::post('/periode/store', [PembayaranController::class, 'storePeriode'])->name('periode.store');

// Route Store (Proses Simpan)
Route::post('/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store');

// Route Auth
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');