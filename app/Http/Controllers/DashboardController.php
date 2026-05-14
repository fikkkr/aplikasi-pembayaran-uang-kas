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
        
        // Ganti nama jadi $riwayat dan tambahkan with('murid')
        $riwayat = \App\Models\Pembayaran::with('murid')->latest()->take(10)->get();
        
        $saldoAkhir = $totalMasuk - $totalKeluar;
        
        $targetKas = 5000; 

        $sudahBayar = \App\Models\Murid::whereHas('pembayaran', function($q) use ($targetKas) {
            $q->select('id_murid')
            ->groupBy('id_murid')
            ->havingRaw('SUM(nominal) >= ?', [$targetKas]);
        })->count();

        $belumLunas = \App\Models\Murid::whereHas('pembayaran', function($q) use ($targetKas) {
            $q->select('id_murid') 
            ->groupBy('id_murid')
            ->havingRaw('SUM(nominal) < ?', [$targetKas]);
        })->count();

        $belumBayar = \App\Models\Murid::doesntHave('pembayaran')->count();

        // Pastikan di sini namanya juga 'riwayat'
        return view('dashboard', compact(
            'totalMasuk', 
            'totalKeluar', 
            'saldoAkhir', 
            'sudahBayar', 
            'belumLunas', 
            'belumBayar', 
            'riwayat' 
        ));
    }

}