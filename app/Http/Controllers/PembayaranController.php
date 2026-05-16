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

    // 1. JIKA YANG DIINPUT ADALAH PENGELUARAN KAS
    if ($request->tipe == 'keluar') {
        $totalMasuk = pembayaran::where('tipe', 'masuk')->sum('nominal');
        $totalKeluar = pembayaran::where('tipe', 'keluar')->sum('nominal');
        $saldoSekarang = $totalMasuk - $totalKeluar;

        if ($request->nominal > $saldoSekarang) {
            return redirect()->back()
                ->withInput() 
                ->withErrors(['nominal' => 'Saldo kas tidak mencukupi. Saldo saat ini: Rp ' . number_format($saldoSekarang, 0, ',', '.')]);
        }

        // Simpan pengeluaran biasa
        pembayaran::create([
            'periode_id'    => $request->periode_id,
            'nominal'       => $request->nominal,
            'tipe'          => 'keluar',
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect('/dashboard')->with('success', 'Data pengeluaran telah berhasil dicatat.');
    }

    // 2. JIKA YANG DIINPUT ADALAH PEMASUKAN UMUM (BUKAN MURID)
    if (!$request->id_murid) {
        pembayaran::create([
            'periode_id'    => $request->periode_id,
            'nominal'       => $request->nominal,
            'tipe'          => 'masuk',
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);
        return redirect('/dashboard')->with('success', 'Data pemasukan umum telah berhasil dicatat.');
    }

    // 3. JIKA YANG DIINPUT ADALAH KAS MURID (LOGIKA RAPELAN OTOMATIS)
    $targetKasPerMinggu = 5000; // Sesuaikan dengan tarif kas kelasmu
    $uangDibayar = $request->nominal;

    // Ambil daftar semua periode dari yang paling lama ke baru (id urut asc)
    // agar kita bisa tahu urutan minggu berikutnya setelah periode_id saat ini
    $semuaPeriodeUrut = Periode::orderBy('id', 'asc')->get();
    
    // Cari posisi index periode saat ini di dalam database
    $indexPeriodeSekarang = $semuaPeriodeUrut->search(function ($item) use ($request) {
        return $item->id == $request->periode_id;
    });

    // Mulai memotong uang untuk dimasukkan ke periode demi periode
    while ($uangDibayar > 0) {
        // Ambil objek periode berdasarkan index saat ini
        $periodeTarget = $semuaPeriodeUrut->get($indexPeriodeSekarang);

        // Jika periode di masa depan belum dibuat di database oleh bendahara, 
        // sisa uangnya dimasukkan semua ke periode terakhir yang tersedia.
        if (!$periodeTarget) {
            pembayaran::create([
                'id_murid'      => $request->id_murid,
                'periode_id'    => $semuaPeriodeUrut->get($indexPeriodeSekarang - 1)->id, // periode terakhir yang ada
                'nominal'       => $uangDibayar, // masukkan sisa semua uangnya
                'tipe'          => 'masuk',
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan . " (Sisa rapelan)",
            ]);
            break;
        }

        // Tentukan berapa nominal yang dicatat di periode ini
        if ($uangDibayar >= $targetKasPerMinggu) {
            $nominalDicatat = $targetKasPerMinggu;
        } else {
            $nominalDicatat = $uangDibayar; // Kalau nanggung (misal sisa 2000), masukin sisanya
        }

        // Simpan baris pembayaran untuk periode ini
        pembayaran::create([
            'id_murid'      => $request->id_murid,
            'periode_id'    => $periodeTarget->id,
            'nominal'       => $nominalDicatat,
            'tipe'          => 'masuk',
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan . " (Periode: " . $periodeTarget->nama_periode . ")",
        ]);

        // Kurangi sisa uang, lalu naikkan index ke minggu berikutnya
        $uangDibayar -= $nominalDicatat;
        $indexPeriodeSekarang++;
    }

    return redirect()->route('pembayaran.index', ['periode_id' => $request->periode_id])
                     ->with('success', 'Pembayaran kas (termasuk rapelan otomatis) berhasil dicatat!');
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

    public function createPeriode()
{
    // Mengambil periode terakhir untuk informasi bendahara di view
    $periodeTerakhir = Periode::orderBy('id', 'desc')->first();
    return view('pembayaran.create_periode', compact('periodeTerakhir'));
}

    public function storePeriode(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255|unique:periodes,nama_periode',
        ]);

        // Membuat periode baru di database
        $periodeBaru = Periode::create([
            'nama_periode' => $request->nama_periode
        ]);

        // Setelah sukses, langsung lempar bendahara ke halaman kas minggu yang baru dibuat itu
        return redirect()->route('pembayaran.index', ['periode_id' => $periodeBaru->id])
                        ->with('success', 'Periode baru (' . $request->nama_periode . ') berhasil ditambahkan!');
    }
}