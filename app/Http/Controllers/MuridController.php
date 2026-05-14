<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use Illuminate\Http\Request;

class MuridController extends Controller
{
    /**
     * Tampilkan daftar murid dan modal-modalnya.
     */
    public function index()
    {
        // Pake orderBy biar absennya urut pas ditampilin
        $murids = Murid::with('pembayaran')->orderBy('absen', 'asc')->get();
        return view('murid.index', compact('murids'));
    }

    /**
     * Simpan murid baru (Via Modal di Index)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'absen' => 'required|numeric|unique:murids,absen',
        ]);

        Murid::create([
            'nama'  => $request->nama,
            'absen' => $request->absen,
            'kelas' => 'XI PPLG 1', 
        ]);

        return redirect()->back()->with('success', 'Murid berhasil ditambahkan!');   
    }

    /**
     * Update data murid (Via Modal di Index)
     */
    public function update(Request $request, $id_murid)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'absen' => 'required|numeric|unique:murids,absen,' . $id_murid . ',id_murid',
        ]);

        $murid = Murid::findOrFail($id_murid);
        $murid->update($request->only(['nama', 'absen']));

        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diperbarui!');
    }

    /**
     * Hapus data murid
     */
    public function destroy($id_murid)
    {
        $murid = Murid::findOrFail($id_murid);
        $murid->delete();

        return redirect()->route('murid.index')->with('success', 'Murid berhasil dihapus!');
    }

}