<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\pembayaran;
use App\Models\Periode;

class PembayaranController extends Controller
{
    // Halaman Utama Monitoring Kas Mingguan (Tabel Lunas/Belum)
    public function index(Request $request)
    {
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        // Ambil periode dari dropdown, jika kosong ambil yang paling baru
        $periodeId = $request->get('periode_id', $semuaPeriode->first()->id ?? null);

        // Ambil murid beserta status bayarnya KHUSUS di periode yang dipilih
        $murids = Murid::with(['pembayaran' => function($query) use ($periodeId) {
            $query->where('periode_id', $periodeId)->where('tipe', 'masuk');
        }])->orderBy('nama', 'asc')->get();

        return view('pembayaran.index', compact('murids', 'semuaPeriode', 'periodeId'));
    }

    public function bayarKhusus(Request $request, $id_murid)
    {
        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $tipe = 'masuk';
        $periode_id = $request->get('periode_id'); 
        
        // AMBIL DATA PERIODE UNTUK DROPDOWN DI FORM INPUT
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.create_pembayaran', compact('murid', 'tipe', 'periode_id', 'semuaPeriode'));
    }

    public function buatPengeluaran()
    {
        $tipe = 'keluar';
        $murid = null; 
        $periode_id = null;
        
        // AMBIL DATA PERIODE UNTUK DROPDOWN DI FORM INPUT
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.create_pembayaran', compact('tipe', 'murid', 'periode_id', 'semuaPeriode'));
    }

    public function buatPemasukanLuar()
    {
        $tipe = 'masuk';
        $murid = null; 
        $periode_id = null;
        
        // AMBIL DATA PERIODE UNTUK DROPDOWN DI FORM INPUT
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.create_pembayaran', compact('tipe', 'murid', 'periode_id', 'semuaPeriode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_murid'      => 'nullable|exists:murids,id_murid', 
            'periode_id'    => 'required|exists:periodes,id',
            'nominal'       => 'required|numeric|min:1',
            'tipe'          => 'required|in:masuk,keluar',
            'tanggal_bayar' => 'required',
            'keterangan'    => 'required|string',
        ]);

        if ($request->tipe == 'keluar') {
            $totalMasuk = pembayaran::where('tipe', 'masuk')->sum('nominal');
            $totalKeluar = pembayaran::where('tipe', 'keluar')->sum('nominal');
            $saldoSekarang = $totalMasuk - $totalKeluar;

            if ($request->nominal > $saldoSekarang) {
                return redirect()->back()
                    ->withInput() 
                    ->withErrors(['nominal' => 'Saldo kas tidak mencukupi. Saldo saat ini: Rp ' . number_format($saldoSekarang, 0, ',', '.')]);
            }
        }

        pembayaran::create([
            'id_murid'      => $request->id_murid,
            'periode_id'    => $request->periode_id,
            'nominal'       => $request->nominal,
            'tipe'          => $request->tipe,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);

        if ($request->id_murid) {
            return redirect()->route('pembayaran.index', ['periode_id' => $request->periode_id])
                            ->with('success', 'Data kas telah berhasil dicatat.');
        }

        $pesan = ($request->tipe == 'masuk') 
            ? 'Data pemasukan umum telah berhasil dicatat.' 
            : 'Data pengeluaran telah berhasil dicatat.';

        return redirect('/dashboard')->with('success', $pesan);
    }

    public function edit($id)
    {
        // Jika model menggunakan id_pembayaran, Eloquent otomatis akan mencari berdasarkan kolom tersebut.
        $pembayaran = pembayaran::with('murid')->findOrFail($id);
        
        $tipe = $pembayaran->tipe;
        $murid = $pembayaran->murid;
        $periode_id = $pembayaran->periode_id;

        return view('pembayaran.edit', compact('pembayaran', 'tipe', 'murid', 'periode_id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nominal'       => 'required|numeric|min:1',
            'tanggal_bayar' => 'required',
            'keterangan'    => 'required|string',
        ]);

        $pembayaran = pembayaran::findOrFail($id);

        // Update data nominal transaksi
        $pembayaran->update([
            'nominal'       => $request->nominal,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);

        // Jika ini kas murid, balik ke halaman monitoring mingguan sesuai periodenya
        if ($pembayaran->id_murid) {
            return redirect()->route('pembayaran.index', ['periode_id' => $pembayaran->periode_id])
                            ->with('success', 'Nominal kas berhasil diperbarui!');
        }

        return redirect('/dashboard')->with('success', 'Data transaksi berhasil diperbarui!');
    }
}