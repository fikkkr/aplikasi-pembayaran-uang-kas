<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk mengamankan query database

class MuridController extends Controller
{
    // 1. Tampilkan Semua Murid Urut Berdasarkan Absen + Statistik Kas (Guru & Bendahara bisa LIHAT)
    public function index()
    {
        if (Gate::denies('lihat-murid')) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat daftar murid.');
        }

        $murids = Murid::orderByRaw('CAST(absen AS UNSIGNED) ASC')->get();      

        $totalLunas      = 0; 
        $totalBelumLunas = 0;
        $totalBelumBayar = 0;

        return view('murid.index', compact('murids', 'totalLunas', 'totalBelumLunas', 'totalBelumBayar'));
    }

   // 2. Simpan Data Murid Baru (Hanya Guru/Admin)
    public function store(Request $request)
    {
        if (Gate::denies('kelola-murid')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk menambahkan murid.');
        }

        $request->validate([
            'absen' => 'required|numeric',
            'nama'  => 'required|string|max:255',
        ]);

        $absenBaru = $request->absen;
        $kelasTarget = 'XI PPLG 1';

        DB::transaction(function () use ($absenBaru, $kelasTarget, $request) {
            
            // Cek apakah nomor absen sudah terpakai
            $absenExist = Murid::where('kelas', $kelasTarget)
                                ->where('absen', $absenBaru)
                                ->exists();

            if ($absenExist) {
                // Karena unique di DB udah dihapus, ini dijamin 1000% lancar tanpa error SQL!
                Murid::where('kelas', $kelasTarget)
                    ->where('absen', '>=', $absenBaru)
                    ->increment('absen');
            }

            // Langsung masukkan murid baru
            Murid::create([
                'absen' => $absenBaru,
                'nama'  => $request->nama,
                'kelas' => $kelasTarget,
            ]);
        });

        return redirect()->route('murid.index')->with('success', 'Murid baru berhasil ditambahkan dan urutan absen disesuaikan!');
    }

    // 3. Update Data Murid (Eksekusi dari modal edit - Hanya Guru/Admin)
    public function update(Request $request, $id_murid)
    {
        if (Gate::denies('kelola-murid')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk mengubah data murid.');
        }

        $request->validate([
            'absen' => 'required|numeric',
            'nama'  => 'required|string|max:255',
        ]);

        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $absenLama = (int)$murid->absen;
        $absenBaru = (int)$request->absen;
        $kelasTarget = 'XI PPLG 1';

        DB::transaction(function () use ($murid, $absenLama, $absenBaru, $kelasTarget, $request) {
            
            // Logika geser hanya jalan kalau user beneran ngerubah nomor absennya
            if ($absenLama != $absenBaru) {
                
                if ($absenBaru < $absenLama) {
                    /**
                     * KASUS 1: ABSEN MENGECIL (Misal: Absen 6 diubah ke Absen 5)
                     * Murid-murid yang punya absen dari rentang [Absen Baru] sampai [Absen Lama - 1]
                     * harus digeser TURUN (+1) biar absen 5 lama jadi absen 6.
                     */
                    Murid::where('kelas', $kelasTarget)
                        ->where('id_murid', '!=', $murid->id_murid)
                        ->whereBetween('absen', [$absenBaru, $absenLama - 1])
                        ->increment('absen');

                } else {
                    /**
                     * KASUS 2: ABSEN MEMBESAR (Misal: Absen 5 diubah ke Absen 8)
                     * Murid-murid yang punya absen dari rentang [Absen Lama + 1] sampai [Absen Baru]
                     * harus digeser NAIK (-1) buat ngisi kekosongan yang ditinggalin absen 5.
                     */
                    Murid::where('kelas', $kelasTarget)
                        ->where('id_murid', '!=', $murid->id_murid)
                        ->whereBetween('absen', [$absenLama + 1, $absenBaru])
                        ->decrement('absen');
                }
            }

            // Setelah jalanan di kiri-kanan rapi, baru update data si murid utama
            $murid->update([
                'absen' => $absenBaru,
                'nama'  => $request->nama,
            ]);
        });

        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diperbarui dan urutan absen otomatis rapi!');
    }

    // 4. Hapus data Murid dari tabel murids (Eksekusi dari modal delete - Hanya Guru/Admin)
    public function destroy($id_murid)
    {
        if (Gate::denies('kelola-murid')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk menghapus murid.');
        }

        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $murid->delete();

        return redirect()->route('murid.index')->with('success', 'Data murid telah berhasil dihapus dari sistem.');
    }
}