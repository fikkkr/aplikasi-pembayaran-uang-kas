<?php

namespace App\Http\Controllers;

use App\Models\Periode; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Ditambahkan untuk handle slug ID Accordion

class PeriodeController extends Controller
{
    // 1. Tampilkan halaman utama kelola periode dengan grouping BULAN
    public function index()
    {
        // Ambil semua periode, urutkan dari yang terbaru dibuat
        $periodes = Periode::orderBy('created_at', 'desc')->get();

        // Trik Laravel Collection: Kelompokkan berdasarkan Bulan dan Tahun (Contoh: "May 2026")
        $periodeGrouped = $periodes->groupBy(function($item) {
            return $item->created_at->format('F Y'); 
        });

        return view('periode.index', compact('periodeGrouped'));
    }

    // 2. Simpan Periode Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:50|unique:periodes,nama_periode',
        ], [
            'nama_periode.unique' => 'Nama periode ini sudah ada loh ya! Pake format lain.',
        ]);

        // Secara default, periode baru langsung berstatus 'aktif'
        Periode::create([
            'nama_periode' => $request->nama_periode,
            'status' => 'aktif'
        ]);

        return redirect()->route('periode.index')->with('success', 'Periode baru berhasil ditambahkan!');
    }

    // 3. Ubah Status Periode (Aktif / Ditutup)
    public function toggleStatus($id)
    {
        $periode = Periode::findOrFail($id);
        
        // Ganti status bolak-balik
        if ($periode->status === 'aktif') {
            $periode->status = 'ditutup';
            $pesan = "Periode {$periode->nama_periode} berhasil DITUTUP. Kas di minggu ini dikunci!";
        } else { 
            $periode->status = 'aktif';
            $pesan = "Periode {$periode->nama_periode} sekarang AKTIF kembali.";
        }

        $periode->save();

        return redirect()->route('periode.index')->with('success', $pesan);
    }
}