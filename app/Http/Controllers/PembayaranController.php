<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\pembayaran; // Pastikan model pembayaran sudah dibuat dan diimport

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
        // Kirim null untuk murid biar di view nggak error
        $murid = null; 
        return view('pembayaran.create_pembayaran', compact('tipe', 'murid'));
    }

    // INI FUNGSI SAKTI BUAT SIMPAN DATA
    public function store(Request $request)
{
    // 1. Validasi standar dulu
    $request->validate([
        'id_murid'      => 'nullable|exists:murids,id_murid', 
        'nominal'       => 'required|numeric|min:1',
        'tipe'          => 'required|in:masuk,keluar',
        'tanggal_bayar' => 'required',
        'keterangan'    => 'required|string',
    ]);

    // 2. CEK SALDO (Hanya kalau tipenya 'keluar')
    if ($request->tipe == 'keluar') {
        $totalMasuk = \App\Models\pembayaran::where('tipe', 'masuk')->sum('nominal');
        $totalKeluar = \App\Models\pembayaran::where('tipe', 'keluar')->sum('nominal');
        $saldoSekarang = $totalMasuk - $totalKeluar;

        if ($request->nominal > $saldoSekarang) {
            // Kalau duit gak cukup, balikin dengan pesan error
            return redirect()->back()
                ->withInput() // Biar formnya gak kosong 
                ->withErrors(['nominal' => 'Duit Kas Gak Cukup! Saldo sisa cuma Rp ' . number_format($saldoSekarang)]);
        }
    }

    // 3. Kalau lolos satpam (atau kalau tipenya 'masuk'), baru simpan
    \App\Models\pembayaran::create([
        'id_murid'      => $request->id_murid,
        'nominal'       => $request->nominal,
        'tipe'          => $request->tipe,
        'tanggal_bayar' => $request->tanggal_bayar,
        'keterangan'    => $request->keterangan,
    ]);

    return redirect('/murid')->with('success', 'Data kas berhasil dicatat! 🔥');

    }
}