<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Pembayaran; 
use App\Models\Murid; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        // Ambil total seluruh murid di kelas buat pembanding nunggak
        $totalMuridKelas = Murid::count();
        $targetKasPerMinggu = 5000; 

        // AMBIL DATA UNTUK ACCORDION (Group By Bulan & Tahun)
        $periodeSemua = Periode::with(['pembayaran'])->orderBy('created_at', 'desc')->get();

        $laporanGrouped = $periodeSemua->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('F Y');
        })->map(function ($periodesInMonth) use ($totalMuridKelas, $targetKasPerMinggu) {
            
            $detailMingguan = $periodesInMonth->map(function ($periode) use ($totalMuridKelas, $targetKasPerMinggu) {
                // Hitung total cashflow mingguan
                $masukMinggu = $periode->pembayaran->where('tipe', 'masuk')->sum('nominal');
                $keluarMinggu = $periode->pembayaran->where('tipe', 'keluar')->sum('nominal');
                
                // BARU: Ambil kumpulan string keterangan unik dari list pembayaran di periode ini
                $keteranganList = $periode->pembayaran->pluck('keterangan')->filter()->unique()->implode(', ');

                // LOGIC HITUNG MURID NUNGGAK (Belum lunas / Belum bayar sama sekali)
                $pembayaranKasMurid = $periode->pembayaran->where('tipe', 'masuk')->whereNotNull('id_murid');

                // Hitung berapa murid yang status bayarnya udah LUNAS (>= 5000) di minggu ini
                $muridLunasCount = $pembayaranKasMurid->filter(function($pembayaran) use ($targetKasPerMinggu) {
                    return $pembayaran->nominal >= $targetKasPerMinggu;
                })->pluck('id_murid')->unique()->count();

                // Jumlah murid nunggak = Total Murid Kelas dikurangi Murid yang udah lunas
                $jumlahNunggak = max(0, $totalMuridKelas - $muridLunasCount);
                
                return [
                    'nama_periode' => $periode->nama_periode, 
                    'pemasukan' => $masukMinggu,
                    'pengeluaran' => $keluarMinggu,
                    'saldo' => $masukMinggu - $keluarMinggu,
                    'jumlah_nunggak' => $jumlahNunggak,
                    'keterangan' => $keteranganList // Dimasukkan ke array agar bisa dirender di Blade
                ];
            });

            // Hitung total keseluruhan satu bulan penuh
            $totalPemasukanBulan = $detailMingguan->sum('pemasukan');
            $totalPengeluaranBulan = $detailMingguan->sum('pengeluaran');

            return [
                'detail_mingguan' => $detailMingguan,
                'total_pemasukan' => $totalPemasukanBulan,
                'total_pengeluaran' => $totalPengeluaranBulan,
                'total_saldo' => $totalPemasukanBulan - $totalPengeluaranBulan
            ];
        });

        // LEMPAR DATA YANG SUDAH BERSIH KE VIEW
        return view('laporan.index', compact('laporanGrouped'));
    }
}