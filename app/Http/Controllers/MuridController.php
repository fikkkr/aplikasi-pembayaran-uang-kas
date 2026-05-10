<?php

namespace App\Http\Controllers;

use App\Models\Murid;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class MuridController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $murids = Murid::with('pembayaran')->get();
        return view('murid.index', compact('murids'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nama' => 'required|string|max:255',
            'absen' => 'required|numeric|unique:murids,absen',
        ]);
        $data = $request->all();
        $data['kelas'] = 'XII PPLG 1';

        \App\Models\Murid::create($data);
        return redirect()->back()->with('success', 'Murid berhasil ditambahkan!');   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $murid = \App\Models\Murid::findOrFail($id);
        return view('murid.edit', compact('murid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_murid)
    {
        $request->validate([
            'nama' => 'required',
            'absen' => 'required|numeric|unique:murids,absen,'.$id_murid.',id_murid',
        ]);

        $murid = \App\Models\Murid::where('id_murid', $id_murid)->firstOrFail();
        $murid->update([
            'nama' => $request->nama,
            'absen' => $request->absen,
        ]);

        return redirect()->route('murid.index')->with('success', 'Data murid berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_murid)
    {
        //
        $murid = \App\Models\Murid::where('id_murid', $id_murid)->firstOrFail();
        $murid->delete();
        return redirect()->route('murid.index')->with('success', 'Murid berhasil dihapus!');
    }
}
