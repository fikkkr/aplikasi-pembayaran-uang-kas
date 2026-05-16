<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Pembayaran;

class MuridController extends Controller
{
    // 1. Tampilkan Semua Murid Urut Berdasarkan Absen + Statistik Kas
    public function index()
    {
        $murids = Murid::orderByRaw('CAST(absen AS UNSIGNED) ASC')->get();      

        // sesuaikan dengan status pembayaran yang ada di tabel pembayaran (Lunas/Belum Lunas/Belum Bayar)
        $totalLunas      = 0; 
        $totalBelumLunas = 0;
        $totalBelumBayar = 0;

        return view('murid.index', compact('murids', 'totalLunas', 'totalBelumLunas', 'totalBelumBayar'));
    }

    // 2. Simpan Data Murid Baru
    public function store(Request $request)
    {
        $request->validate([
            'absen' => 'required|numeric',
            'nama'  => 'required|string|max:255',
        ]);

        Murid::create([
            'absen' => $request->absen,
            'nama'  => $request->nama,
            'kelas' => 'XI PPLG 1', // Default kelas, bisa diubah sesuai kebutuhan
        ]);

        return redirect()->route('murid.index')->with('success', 'Murid baru berhasil ditambahkan ke kelas!');
    }

    // 3. Update Data Murid (Eksekusi dari modal edit)
    public function update(Request $request, $id_murid)
    {
        $request->validate([
            'absen' => 'required|numeric',
            'nama'  => 'required|string|max:255',
        ]);

        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $murid->update([
            'absen' => $request->absen,
            'nama'  => $request->nama,
        ]);

        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diperbarui!');
    }

    // 4. Hapus data Murid dari tabel murids (Eksekusi dari modal delete)
    public function destroy($id_murid)
    {
        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $murid->delete(); // Karena terhapus, otomatis tidak akan tampil lagi di kas mingguan

        return redirect()->route('murid.index')->with('success', 'Data murid telah berhasil dihapus dari sistem.');
    }
}