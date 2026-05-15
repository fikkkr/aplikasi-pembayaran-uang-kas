<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

// Akses langsung ke Dashboard (Tanpa Login)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index']);

// Route Murid (CRUD)
Route::resource('murid', MuridController::class)->except(['create', 'edit', 'show']);

// Route Pembayaran & Pengeluaran
Route::get('/pembayaran/bayar/{id_murid}', [PembayaranController::class, 'bayarKhusus'])->name('pembayaran.bayar');
Route::get('/pengeluaran', [PembayaranController::class, 'buatPengeluaran'])->name('pengeluaran.tambah');
Route::get('/pemasukan-umum', [PembayaranController::class, 'buatPemasukanLuar'])->name('pembayaran.umum');
Route::get('/pengeluaran', [PembayaranController::class, 'buatPengeluaran'])->name('pembayaran.pengeluaran');
Route::post('/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store');

// Route Auth (Dibiarkan ada, tapi tidak mengunci route lain)
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');