<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\pembayaran;
use App\Models\Periode;
use Illuminate\Support\Facades\Gate;

class PembayaranController extends Controller
{
    // Halaman Utama Monitoring Kas Mingguan (Tabel Lunas/Belum) - Semua level boleh lihat
    public function index(Request $request)
    {
        // 1. Ambil semua periode (Urutkan desc agar yang terbaru muncul paling atas di dropdown)
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        // 2. Tentukan Periode ID yang aktif / sedang dipilih
        if ($request->has('periode_id')) {
            $periodeId = $request->get('periode_id');
            session(['terakhir_periode_id' => $periodeId]);
        } else {
            $periodeId = session('terakhir_periode_id');
            
            // Cek apakah ID dari session beneran ada di DB? Kalau gak ada, ambil yang paling baru
            if (!$periodeId || !Periode::where('id', $periodeId)->exists()) {
                $periodeId = $semuaPeriode->first()?->id ?? null;
            }
        }

        // 3. Ambil data murid beserta pembayarannya sesuai periodeId
        $queryMurid = Murid::query();

        if ($periodeId) {
            $queryMurid->with(['pembayaran' => function($query) use ($periodeId) {
                $query->where('periode_id', $periodeId)->where('tipe', 'masuk');
            }]);
        }

        $murids = $queryMurid->orderBy('nama', 'asc')->get();

        // 4. Hitung Rekapan Card Atas
        $totalMasukMingguIni = $periodeId ? pembayaran::where('periode_id', $periodeId)->where('tipe', 'masuk')->sum('nominal') : 0;
        $muridLunasMingguIni = $periodeId ? pembayaran::where('periode_id', $periodeId)->where('tipe', 'masuk')->where('nominal', '>=', 5000)->count() : 0;
        
        $totalMurid = Murid::count();
        $muridBelumLunasMingguIni = $totalMurid - $muridLunasMingguIni;

        return view('pembayaran.index', compact(
            'murids', 
            'semuaPeriode', 
            'periodeId',
            'totalMasukMingguIni',
            'muridLunasMingguIni',
            'muridBelumLunasMingguIni'
        ));
    }

    public function bayarKhusus(Request $request, $id_murid)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk memasukkan data kas.');
        }

        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $tipe = 'masuk';
        
        $periode_id = $request->get('periode_id', session('terakhir_periode_id')); 
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.create_pembayaran', compact('murid', 'tipe', 'periode_id', 'semuaPeriode'));
    }

    public function buatPengeluaran()
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencatat pengeluaran.');
        }

        $tipe = 'keluar';
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.pengeluaran', compact('tipe', 'semuaPeriode'));
    }

    public function buatPemasukanLuar()
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mencatat pemasukan umum.');
        }

        $tipe = 'masuk';
        $semuaPeriode = Periode::orderBy('id', 'desc')->get();
        
        return view('pembayaran.umum', compact('tipe', 'semuaPeriode'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk mengelola kas.');
        }

        $request->validate([
            'id_murid'      => 'nullable|exists:murids,id_murid', 
            'periode_id'    => 'nullable|exists:periodes,id', 
            'nominal'       => 'required|numeric|min:1',
            'tipe'          => 'required|in:masuk,keluar',
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'required|string',
        ]);

        // Simpan periode_id ke session agar view tidak melompat halaman
        session(['terakhir_periode_id' => $request->periode_id]);

        // =====================================================================
        // 1. LOGIKA PENGELUARAN KAS
        // =====================================================================
        if ($request->tipe === 'keluar') {
            $totalMasuk = pembayaran::where('tipe', 'masuk')->sum('nominal');
            $totalKeluar = pembayaran::where('tipe', 'keluar')->sum('nominal');
            $saldoSekarang = $totalMasuk - $totalKeluar;

            if ($request->nominal > $saldoSekarang) {
                return redirect()->back()
                    ->withInput() 
                    ->withErrors(['nominal' => 'Saldo kas tidak mencukupi. Saldo saat ini: Rp ' . number_format($saldoSekarang, 0, ',', '.')]);
            }

            pembayaran::create([
                'periode_id'    => $request->periode_id,
                'nominal'       => $request->nominal,
                'tipe'          => 'keluar',
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan,
            ]);

            return redirect()->route('pembayaran.pengeluaran')->with('success', 'Data pengeluaran kelas telah berhasil dicatat.');
        }

        // =====================================================================
        // 2. LOGIKA PEMASUKAN UMUM (Tipe Masuk, tapi ID Murid Kosong)
        // =====================================================================
        if ($request->tipe === 'masuk' && empty($request->id_murid)) {
            pembayaran::create([
                'periode_id'    => $request->periode_id,
                'nominal'       => $request->nominal,
                'tipe'          => 'masuk',
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan,
            ]);
            
            return redirect()->route('pembayaran.umum')->with('success', 'Data pemasukan umum telah berhasil dicatat.');
        }

        // =====================================================================
        // 3. LOGIKA KAS REGULER MURID (Tipe Masuk, dan ID Murid Ada)
        // =====================================================================
        if ($request->tipe === 'masuk' && !empty($request->id_murid)) {
            
            $targetKasPerMinggu = 5000; 
            $uangDibayar = $request->nominal;

            $semuaPeriodeUrut = Periode::orderBy('id', 'asc')->get();
            
            $indexPeriodeSekarang = $semuaPeriodeUrut->search(function ($item) use ($request) {
                return (int)$item->id === (int)$request->periode_id;
            });

            if ($indexPeriodeSekarang === false) {
                $indexPeriodeSekarang = 0;
            }

            // Validasi Sisa Slot Periode
            $totalSlotTersedia = 0;
            for ($i = $indexPeriodeSekarang; $i < $semuaPeriodeUrut->count(); $i++) {
                $pPeriode = $semuaPeriodeUrut->get($i);
                $pembayaranAda = pembayaran::where('id_murid', $request->id_murid)
                                            ->where('periode_id', $pPeriode->id)
                                            ->where('tipe', 'masuk')
                                            ->first();
                $sudahBayar = $pembayaranAda ? $pembayaranAda->nominal : 0;
                $totalSlotTersedia += max(0, $targetKasPerMinggu - $sudahBayar);
            }

            if ($uangDibayar > $totalSlotTersedia) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nominal' => 'Gagal! Pembayaran sebesar Rp ' . number_format($uangDibayar, 0, ',', '.') . ' melampaui batas periode yang tersedia.']);
            }

            // Loop Rapelan Otomatis
            while ($uangDibayar > 0) {
                $periodeTarget = $semuaPeriodeUrut->get($indexPeriodeSekarang);

                if (!$periodeTarget) {
                    break; 
                }

                $pembayaranAda = pembayaran::where('id_murid', $request->id_murid)
                                            ->where('periode_id', $periodeTarget->id)
                                            ->where('tipe', 'masuk')
                                            ->first();

                $sudahBayarDiPeriodeIni = $pembayaranAda ? $pembayaranAda->nominal : 0;
                $kekuranganPeriodeIni = $targetKasPerMinggu - $sudahBayarDiPeriodeIni;

                if ($kekuranganPeriodeIni <= 0) {
                    $indexPeriodeSekarang++;
                    continue;
                }

                $nominalDicatat = ($uangDibayar >= $kekuranganPeriodeIni) ? $kekuranganPeriodeIni : $uangDibayar;

                if ($pembayaranAda) {
                    $pembayaranAda->increment('nominal', $nominalDicatat);
                } else {
                    pembayaran::create([
                        'id_murid'      => $request->id_murid,
                        'periode_id'    => $periodeTarget->id,
                        'nominal'       => $nominalDicatat,
                        'tipe'          => 'masuk',
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'keterangan'    => $request->keterangan . " (Periode: " . $periodeTarget->nama_periode . ")",
                    ]);
                }

                $uangDibayar -= $nominalDicatat;
                $indexPeriodeSekarang++;
            }

            return redirect()->route('pembayaran.index', ['periode_id' => $request->periode_id])
                            ->with('success', 'Pembayaran kas (termasuk rapelan otomatis) berhasil dicatat!');
        }

        // Fallback safety redirect jika parameter ga memenuhi syarat manapun
        return redirect()->back()->withErrors(['nominal' => 'Sistem mendeteksi inkonsistensi struktur data input data kas.']);
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk memperbarui kas.');
        }

        $request->validate([
            'nominal'       => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
            'keterangan'    => 'required|string',
        ]);

        $pembayaran = pembayaran::findOrFail($id);
        $targetKasPerMinggu = 5000;

        // JIKA YANG DI-EDIT ADALAH KAS MURID
        if ($pembayaran->id_murid) {
            $uangDibayar = $request->nominal;
            $idMurid = $pembayaran->id_murid;
            
            $semuaPeriodeUrut = Periode::orderBy('id', 'asc')->get();
            
            $indexPeriodeSekarang = $semuaPeriodeUrut->search(function ($item) use ($pembayaran) {
                return (int)$item->id === (int)$pembayaran->periode_id;
            });

            if ($indexPeriodeSekarang === false) { $indexPeriodeSekarang = 0; }

            // Validasi Kapasitas Slot saat Edit
            $totalSlotTersedia = 0;
            for ($i = $indexPeriodeSekarang; $i < $semuaPeriodeUrut->count(); $i++) {
                $pPeriode = $semuaPeriodeUrut->get($i);
                $pembayaranAda = pembayaran::where('id_murid', $idMurid)
                                            ->where('periode_id', $pPeriode->id)
                                            ->where('tipe', 'masuk')
                                            ->first();
                
                $sudahBayar = ($pembayaranAda && $pembayaranAda->id != $pembayaran->id) ? $pembayaranAda->nominal : 0;
                $totalSlotTersedia += max(0, $targetKasPerMinggu - $sudahBayar);
            }

            if ($uangDibayar > $totalSlotTersedia) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['nominal' => 'Gagal mengubah data! Nominal melebihi kapasitas alokasi periode.']);
            }

            // Jalankan distribusi ulang alokasi uang kas
            while ($uangDibayar > 0) {
                $periodeTarget = $semuaPeriodeUrut->get($indexPeriodeSekarang);

                if (!$periodeTarget) { break; }

                $pembayaranAda = pembayaran::where('id_murid', $idMurid)
                                            ->where('periode_id', $periodeTarget->id)
                                            ->where('tipe', 'masuk')
                                            ->first();

                $sudahBayarDiPeriodeIni = ($pembayaranAda && $pembayaranAda->id != $pembayaran->id) ? $pembayaranAda->nominal : 0;
                $kekuranganPeriodeIni = $targetKasPerMinggu - $sudahBayarDiPeriodeIni;

                if ($kekuranganPeriodeIni <= 0) {
                    $indexPeriodeSekarang++;
                    continue;
                }

                $nominalDicatat = ($uangDibayar >= $kekuranganPeriodeIni) ? $kekuranganPeriodeIni : $uangDibayar;

                if ($pembayaranAda) {
                    if ($pembayaranAda->id == $pembayaran->id) {
                        $pembayaran->update([
                            'nominal'       => $nominalDicatat,
                            'tanggal_bayar' => $request->tanggal_bayar,
                            'keterangan'    => $request->keterangan,
                        ]);
                    } else {
                        $pembayaranAda->increment('nominal', $nominalDicatat);
                    }
                } else {
                    pembayaran::create([
                        'id_murid'      => $idMurid,
                        'periode_id'    => $periodeTarget->id,
                        'nominal'       => $nominalDicatat,
                        'tipe'          => 'masuk',
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'keterangan'    => $request->keterangan . " (Alokasi edit)",
                    ]);
                }

                $uangDibayar -= $nominalDicatat;
                $indexPeriodeSekarang++;
            }

            return redirect()->route('pembayaran.index', ['periode_id' => $pembayaran->periode_id])
                            ->with('success', 'Data kas berhasil disesuaikan!');
        }

        // JIKA YANG DI-EDIT ADALAH TRANSAKSI UMUM (NON-MURID)
        $pembayaran->update([
            'nominal'       => $request->nominal,
            'tanggal_bayar' => $request->tanggal_bayar,
            'keterangan'    => $request->keterangan,
        ]);

        return redirect('/dashboard')->with('success', 'Data transaksi umum berhasil diperbarui!');
    }
    
    public function createPeriode()
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuat periode kas baru.');
        }

        $periodeTerakhir = Periode::orderBy('id', 'desc')->first();
        return view('pembayaran.create_periode', compact('periodeTerakhir'));
    }

    public function storePeriode(Request $request)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk menyimpan periode.');
        }

        $request->validate([
            'nama_periode' => 'required|string|max:255|unique:periodes,nama_periode',
        ]);

        $periodeBaru = Periode::create([
            'nama_periode' => $request->nama_periode
        ]);

        session(['terakhir_periode_id' => $periodeBaru->id]);

        return redirect()->route('pembayaran.index', ['periode_id' => $periodeBaru->id])
                        ->with('success', 'Periode baru (' . $request->nama_periode . ') berhasil ditambahkan!');
    }
}