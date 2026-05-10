<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/pembayaran/bayar/{id_murid}', [App\Http\Controllers\PembayaranController::class, 'bayarKhusus'])->name('pembayaran.bayar');
Route::get('/pembayaran/tambah', [App\Http\Controllers\PembayaranController::class, 'buatPengeluaran'])->name('pengeluaran.tambah');
Route::post('/pembayaran/store', [App\Http\Controllers\PembayaranController::class, 'store'])->name('pembayaran.store');
Route::resource('murid', MuridController::class);
