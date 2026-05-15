<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\pembayaran;

class PembayaranController extends Controller
{
    public function bayarKhusus($id_murid)
    {
        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $tipe = 'masuk';
        return view('pembayaran.create_pembayaran', compact('murid', 'tipe'));
    }

    public function buatPengeluaran()
    {
        $tipe = 'keluar';
        $murid = null; 
        return view('pembayaran.create_pembayaran', compact('tipe', 'murid'));
    }

    // Fungsi baru untuk pemasukan di luar kas murid (misal: hadiah lomba)
    public function buatPemasukanLuar()
    {
        $tipe = 'masuk';
        $murid = null; 
        return view('pembayaran.create_pembayaran', compact('tipe', 'murid'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_murid'      => 'nullable|exists:murids,id_murid', 
            'nominal'       => 'required|numeric|min:1',
            'tipe'          => 'required|in:masuk,keluar',
            'tanggal_bayar' => 'required',
            'keterangan'    => 'required|string',
        ]);

        if ($request->tipe == 'keluar') {
            $totalMasuk = \App\Models\pembayaran::where('tipe', 'masuk')->sum('nominal');
            $totalKeluar = \App\Models\pembayaran::where('tipe', 'keluar')->sum('nominal');
            $saldoSekarang = $totalMasuk - $totalKeluar;

            if ($request->nominal > $saldoSekarang) {
                return redirect()->back()
                    ->withInput() 
                    ->withErrors(['nominal' => 'Saldo kas tidak mencukupi. Saldo saat ini: Rp ' . number_format($saldoSekarang, 0, ',', '.')]);
            }
        }

        \App\Models\pembayaran::create([
            'id_murid'      => $request->id_murid,
            'nominal'       => $request->nominal,
            'tipe'          => $request->tipe,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);

        // Jika ada id_murid, berarti pembayaran rutin murid, balik ke halaman murid
        if ($request->id_murid) {
            return redirect('/murid')->with('success', 'Data kas telah berhasil dicatat.');
        }

        // Jika id_murid null, tentukan pesan berdasarkan tipe (masuk/keluar) dan balik ke dashboard
        $pesan = ($request->tipe == 'masuk') 
            ? 'Data pemasukan umum telah berhasil dicatat.' 
            : 'Data pengeluaran telah berhasil dicatat.';

        return redirect('/dashboard')->with('success', $pesan);
    }
}