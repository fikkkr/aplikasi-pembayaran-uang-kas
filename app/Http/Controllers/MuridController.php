<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MuridController extends Controller
{
    public function index()
    {
        $murids = Murid::orderByRaw('CAST(absen AS UNSIGNED) ASC')->get();
        return view('murid.index', compact('murids'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255|unique:murids,nama',
            'absen' => 'required|numeric',
        ]);

        // AMBIL SEMUA DATA DULU KE MEMORI
        $allMurids = Murid::orderByRaw('CAST(absen AS UNSIGNED) ASC')->get();
        $newAbsen = (int) $request->absen;

        DB::transaction(function () use ($request, $allMurids, $newAbsen) {
            // Geser semua murid yang absennya >= absen baru
            foreach ($allMurids as $m) {
                if ((int)$m->absen >= $newAbsen) {
                    // Update langsung via Query Builder untuk bypass model events
                    DB::table('murids')
                        ->where('id_murid', $m->id_murid)
                        ->update(['absen' => (int)$m->absen + 1]);
                }
            }

            // Simpan murid baru
            Murid::create([
                'nama'  => $request->nama,
                'absen' => $newAbsen,
                'kelas' => 'XI PPLG 1', 
            ]);
        });

        return redirect()->back()->with('success', 'Murid berhasil ditambahkan!');   
    }

    public function update(Request $request, $id_murid)
    {
        $request->validate([
            'nama'  => 'required|string|max:255|unique:murids,nama,' . $id_murid . ',id_murid',
            'absen' => 'required|numeric',
        ]);

        $murid = Murid::findOrFail($id_murid);
        $absenLama = (int)$murid->absen;
        $absenBaru = (int)$request->absen;

        DB::transaction(function () use ($murid, $absenLama, $absenBaru, $request) {
            // Kosongkan sementara absen si murid agar tidak kena Unique Error saat pergeseran
            DB::table('murids')->where('id_murid', $murid->id_murid)->update(['absen' => 9999]);

            if ($absenBaru > $absenLama) {
                // Geser ke atas
                DB::table('murids')
                    ->whereBetween('absen', [$absenLama + 1, $absenBaru])
                    ->decrement('absen');
            } elseif ($absenBaru < $absenLama) {
                // Geser ke bawah
                DB::table('murids')
                    ->whereBetween('absen', [$absenBaru, $absenLama - 1])
                    ->increment('absen');
            }

            // Update final
            DB::table('murids')
                ->where('id_murid', $murid->id_murid)
                ->update([
                    'nama' => $request->nama,
                    'absen' => $absenBaru
                ]);
        });

        return redirect()->route('murid.index')->with('success', 'Data diperbarui!');
    }

    public function destroy($id_murid)
    {
        $murid = Murid::findOrFail($id_murid);
        $absenDihapus = (int) $murid->absen;

        DB::transaction(function () use ($murid, $absenDihapus) {
            $murid->delete();

            // Geser semua yang di bawahnya naik 1
            DB::table('murids')
                ->where('absen', '>', $absenDihapus)
                ->decrement('absen');
        });

        return redirect()->route('murid.index')->with('success', 'Data dihapus!');
    }
}