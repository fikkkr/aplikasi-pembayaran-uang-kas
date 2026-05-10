<?php

namespace App\Http\Controllers;
use App\Models\Pembayaran; 
use App\Models\Murid;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
        public function index()
    {
        $totalMasuk = \App\Models\Pembayaran::where('tipe', 'masuk')->sum('nominal');
        $totalKeluar = \App\Models\Pembayaran::where('tipe', 'keluar')->sum('nominal');
        $transaksiTerakhir = \App\Models\Pembayaran::latest()->take(5)->get();
        $saldoAkhir = $totalMasuk - $totalKeluar;
        
        // target kas yg harus dibayar per murid, bisa disesuaikan dengan kebutuhan
        $targetKas = 5000; 

        // Kategori: Sudah Lunas (artinya sudah mencapai atau melebihi target)
        $sudahBayar = \App\Models\Murid::whereHas('pembayaran', function($q) use ($targetKas) {
            $q->select('id_murid')
            ->groupBy('id_murid')
            ->havingRaw('SUM(nominal) >= ?', [$targetKas]);
        })->count();

        // Kategori: Belum Lunas (artinya sudah ada catatan pembayaran tapi belum mencapai target)
        $belumLunas = \App\Models\Murid::whereHas('pembayaran', function($q) use ($targetKas) {
            $q->select('id_murid') 
            ->groupBy('id_murid')
            ->havingRaw('SUM(nominal) < ?', [$targetKas]);
        })->count();

        // Kategori: Belum Bayar (artinya belum ada catatan pembayaran sama sekali)
        $belumBayar = \App\Models\Murid::doesntHave('pembayaran')->count();
        return view('dashboard', compact('totalMasuk', 'totalKeluar', 'saldoAkhir', 'sudahBayar', 'belumLunas', 'belumBayar', 'transaksiTerakhir'));
    }

}