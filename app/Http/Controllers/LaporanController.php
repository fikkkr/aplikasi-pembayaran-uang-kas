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
        // 1. HITUNG CARD SUMMARY (Khusus Bulan & Tahun Berjalan Sekarang)
        $bulanSekarang = Carbon::now()->month;
        $tahunSekarang = Carbon::now()->year;

        $pemasukanBulanIni = Pembayaran::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->where('tipe', 'masuk') 
            ->sum('nominal');

        $pengeluaranBulanIni = Pembayaran::whereMonth('created_at', $bulanSekarang)
            ->whereYear('created_at', $tahunSekarang)
            ->where('tipe', 'keluar') 
            ->sum('nominal');

        $saldoBulanIni = $pemasukanBulanIni - $pengeluaranBulanIni;

        // Ambil total seluruh murid di kelas buat pembanding nunggak
        $totalMuridKelas = Murid::count();
        $targetKasPerMinggu = 5000; 


        // 2. AMBIL DATA UNTUK ACCORDION (Group By Bulan & Tahun)
        $periodeSemua = Periode::with(['pembayaran'])->orderBy('created_at', 'desc')->get();

        $laporanGrouped = $periodeSemua->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('F Y');
        })->map(function ($periodesInMonth) use ($totalMuridKelas, $targetKasPerMinggu) {
            
            $detailMingguan = $periodesInMonth->map(function ($periode) use ($totalMuridKelas, $targetKasPerMinggu) {
                // Hitung total cashflow mingguan
                $masukMinggu = $periode->pembayaran->where('tipe', 'masuk')->sum('nominal');
                $keluarMinggu = $periode->pembayaran->where('tipe', 'keluar')->sum('nominal');
                
                // LOGIC HITUNG MURID NUNGGAK (Belum lunas / Belum bayar sama sekali)
                // 1. Ambil semua pembayaran bertipe 'masuk' yang punya id_murid di periode ini
                $pembayaranKasMurid = $periode->pembayaran->where('tipe', 'masuk')->whereNotNull('id_murid');

                // 2. Hitung berapa murid yang status bayarnya udah LUNAS (>= 5000) di minggu ini
                $muridLunasCount = $pembayaranKasMurid->filter(function($pembayaran) use ($targetKasPerMinggu) {
                    return $pembayaran->nominal >= $targetKasPerMinggu;
                })->pluck('id_murid')->unique()->count();

                // 3. Jumlah murid nunggak = Total Murid Kelas dikurangi Murid yang udah lunas
                $jumlahNunggak = max(0, $totalMuridKelas - $muridLunasCount);
                
                return [
                    'nama_periode' => $periode->nama_periode, 
                    'pemasukan' => $masukMinggu,
                    'pengeluaran' => $keluarMinggu,
                    'saldo' => $masukMinggu - $keluarMinggu,
                    'jumlah_nunggak' => $jumlahNunggak 
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

        // 3. LEMPAR VARIABEL KE VIEW
        return view('laporan.index', compact(
            'pemasukanBulanIni', 
            'pengeluaranBulanIni', 
            'saldoBulanIni', 
            'laporanGrouped'
        ));
    }
}