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
        $semuaPeriode = Periode::orderBy('id', 'asc')->get();
        
        // FIX: Cek apakah ada request dari dropdown (?periode_id=...)
        if ($request->has('periode_id')) {
            $periodeId = $request->get('periode_id');
            // Simpan periode id yang dipilih ke dalam session biar diingat sistem
            session(['terakhir_periode_id' => $periodeId]);
        } else {
            // Kalau gak ada request (baru dari menu lain/dashboard), ambil dari session.
            // Jika session masih kosong, baru fallback ke periode terakhir di database.
            $periodeId = session('terakhir_periode_id', $semuaPeriode->last()->id ?? null);
        }

        // Ambil murid beserta status bayarnya KHUSUS di periode yang dipilih
        $murids = Murid::with(['pembayaran' => function($query) use ($periodeId) {
            $query->where('periode_id', $periodeId)->where('tipe', 'masuk');
        }])->orderBy('nama', 'asc')->get();

    // 1. Hitung total uang masuk KHUSUS di minggu ini
    $totalMasukMingguIni = Pembayaran::where('periode_id', $periodeId)
                                     ->where('tipe', 'masuk')
                                     ->sum('nominal');

    // 2. Hitung berapa murid yang SUDAH LUNAS di minggu ini (bayar >= 5000)
    $muridLunasMingguIni = Pembayaran::where('periode_id', $periodeId)
                                     ->where('tipe', 'masuk')
                                     ->where('nominal', '>=', 5000)
                                     ->count();

    // 3. Hitung berapa murid yang BELUM BAYAR / BELUM LUNAS di minggu ini
    // Caranya: Total semua murid dikurangi yang sudah lunas
    $totalMurid = Murid::count();
    $muridBelumLunasMingguIni = $totalMurid - $muridLunasMingguIni;

    // Kirim semua variabel ke view index
    return view('pembayaran.index', compact(
        'murids', 
        'semuaPeriode', 
        'periodeId',
        'totalMasukMingguIni',
        'muridLunasMingguIni',
        'muridBelumLunasMingguIni'
    ));
        return view('pembayaran.index', compact('murids', 'semuaPeriode', 'periodeId'));
    }

    public function bayarKhusus(Request $request, $id_murid)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk memasukkan data kas.');
        }

        $murid = Murid::where('id_murid', $id_murid)->firstOrFail();
        $tipe = 'masuk';
        
        // Ambil periode dari request, kalau kosong ambil dari session biar form input tahu bendahara lagi di minggu berapa
        $periode_id = $request->get('periode_id', session('terakhir_periode_id')); 
        
        // AMBIL DATA PERIODE UNTUK DROPDOWN DI FORM INPUT
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
            'periode_id'    => 'required|exists:periodes,id', 
            'nominal'       => 'required|numeric|min:1',
            'tipe'          => 'required|in:masuk,keluar',
            'tanggal_bayar' => 'required',
            'keterangan'    => 'required|string',
        ]);

        // Simpan periode_id yang sedang diproses ke session biar setelah redirect, halaman index gak melompat
        session(['terakhir_periode_id' => $request->periode_id]);

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

            pembayaran::create([
                'periode_id'    => $request->periode_id,
                'nominal'       => $request->nominal,
                'tipe'          => 'keluar',
                'tanggal_bayar' => $request->tanggal_bayar,
                'keterangan'    => $request->keterangan,
            ]);

            return redirect()->route('pembayaran.pengeluaran')->with('success', 'Data pengeluaran kelas telah berhasil dicatat.');
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
            
            return redirect()->route('pembayaran.umum')->with('success', 'Data pemasukan umum telah berhasil dicatat.');
        }

        // 3. JIKA YANG DIINPUT ADALAH KAS MURID (LOGIKA RAPELAN OTOMATIS)
        $targetKasPerMinggu = 5000; 
        $uangDibayar = $request->nominal;

        $semuaPeriodeUrut = Periode::orderBy('id', 'asc')->get();
        
        // Paksa request periode_id jadi Integer biar pencarian indeks Collection akurat
        $indexPeriodeSekarang = $semuaPeriodeUrut->search(function ($item) use ($request) {
            return (int)$item->id === (int)$request->periode_id;
        });

        if ($indexPeriodeSekarang === false) {
            $indexPeriodeSekarang = 0;
        }

        while ($uangDibayar > 0) {
            $periodeTarget = $semuaPeriodeUrut->get($indexPeriodeSekarang);

            if (!$periodeTarget) {
                $periodeTerakhirId = $semuaPeriodeUrut->last()->id;
                
                $pembayaranSisa = pembayaran::where('id_murid', $request->id_murid)
                                            ->where('periode_id', $periodeTerakhirId)
                                            ->where('tipe', 'masuk')
                                            ->first();
                if ($pembayaranSisa) {
                    $pembayaranSisa->increment('nominal', $uangDibayar);
                } else {
                    pembayaran::create([
                        'id_murid'      => $request->id_murid,
                        'periode_id'    => $periodeTerakhirId, 
                        'nominal'       => $uangDibayar, 
                        'tipe'          => 'masuk',
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'keterangan'    => $request->keterangan . " (Sisa rapelan luar periode)",
                    ]);
                }
                break;
            }

            $pembayaranAda = pembayaran::where('id_murid', $request->id_murid)
                                        ->where('periode_id', $periodeTarget->id)
                                        ->where('tipe', 'masuk')
                                        ->first();

            $sudahBayarDiPeriodeIni = $pembayaranAda ? $pembayaranAda->nominal : 0;
            $kekuranganPeriodeIni = $targetKasPerMinggu - $sudahBayarDiPeriodeIni;

            // Jika di periode target ini ternyata dia udah lunas (>= 5000), skip langsung maju ke minggu depannya
            if ($kekuranganPeriodeIni <= 0) {
                $indexPeriodeSekarang++;
                continue;
            }

            if ($uangDibayar >= $kekuranganPeriodeIni) {
                $nominalDicatat = $kekuranganPeriodeIni;
            } else {
                $nominalDicatat = $uangDibayar; 
            }

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

        // Redirect dengan membawa parameter ?periode_id= dari input terakhir agar sinkron dengan session
        return redirect()->route('pembayaran.index', ['periode_id' => $request->periode_id])
                         ->with('success', 'Pembayaran kas (termasuk rapelan otomatis) berhasil dicatat!');
    }

    public function edit($id)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data kas.');
        }

        $pembayaran = pembayaran::with('murid')->findOrFail($id);
        
        $tipe = $pembayaran->tipe;
        $murid = $pembayaran->murid;
        $periode_id = $pembayaran->periode_id;

        return view('pembayaran.edit', compact('pembayaran', 'tipe', 'murid', 'periode_id'));
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('kelola-kas')) {
            abort(403, 'Tindakan ilegal: Anda tidak memiliki hak akses untuk memperbarui kas.');
        }

        $request->validate([
            'nominal'       => 'required|numeric|min:1',
            'tanggal_bayar' => 'required',
            'keterangan'    => 'required|string',
        ]);

        $pembayaran = pembayaran::findOrFail($id);
        $targetKasPerMinggu = 5000;

        // JIKA YANG DI-EDIT ADALAH KAS MURID (Ada id_murid-nya)
        if ($pembayaran->id_murid) {
            $uangDibayar = $request->nominal;
            $idMurid = $pembayaran->id_murid;
            
            // Ambil semua periode dari yang paling awal untuk proses pelacakan berurutan
            $semuaPeriodeUrut = Periode::orderBy('id', 'asc')->get();
            
            // Mulai pelacakan dari periode data yang sedang diedit ini
            $indexPeriodeSekarang = $semuaPeriodeUrut->search(function ($item) use ($pembayaran) {
                return (int)$item->id === (int)$pembayaran->periode_id;
            });

            if ($indexPeriodeSekarang === false) { $indexPeriodeSekarang = 0; }

            // Loop untuk mendistribusikan nominal baru ini ke periode saat ini dan berikutnya
            while ($uangDibayar > 0) {
                $periodeTarget = $semuaPeriodeUrut->get($indexPeriodeSekarang);

                // Skenario jika periode di database sudah habis tapi uang masih sisa
                if (!$periodeTarget) {
                    $periodeTerakhirId = $semuaPeriodeUrut->last()->id;
                    
                    $pembayaranSisa = pembayaran::where('id_murid', $idMurid)
                                                ->where('periode_id', $periodeTerakhirId)
                                                ->where('tipe', 'masuk')
                                                ->first();
                    if ($pembayaranSisa) {
                        // Jika baris yang sedang diedit kebetulan adalah baris terakhir, update langsung
                        if ($pembayaranSisa->id == $pembayaran->id) {
                            $pembayaran->update(['nominal' => $pembayaran->nominal + $uangDibayar]);
                        } else {
                            $pembayaranSisa->increment('nominal', $uangDibayar);
                        }
                    }
                    break;
                }

                // Cek apakah data pembayaran di periode target ini sudah ada di DB
                $pembayaranAda = pembayaran::where('id_murid', $idMurid)
                                            ->where('periode_id', $periodeTarget->id)
                                            ->where('tipe', 'masuk')
                                            ->first();

                // Hitung kekurangan di periode target ini
                // Khusus untuk periode asal ($pembayaran->id), kita anggap dia belum bayar agar bisa menampung nominal baru secara bersih
                $sudahBayarDiPeriodeIni = ($pembayaranAda && $pembayaranAda->id != $pembayaran->id) ? $pembayaranAda->nominal : 0;
                $kekuranganPeriodeIni = $targetKasPerMinggu - $sudahBayarDiPeriodeIni;

                if ($kekuranganPeriodeIni <= 0) {
                    $indexPeriodeSekarang++;
                    continue;
                }

                // Tentukan nominal yang akan dialokasikan ke periode ini
                if ($uangDibayar >= $kekuranganPeriodeIni) {
                    $nominalDicatat = $kekuranganPeriodeIni;
                } else {
                    $nominalDicatat = $uangDibayar;
                }

                // Eksekusi penyimpanan data pembayaran
                if ($pembayaranAda) {
                    if ($pembayaranAda->id == $pembayaran->id) {
                        // Jika ini adalah data asal yang sedang diedit, gunakan update biasa
                        $pembayaran->update([
                            'nominal'       => $nominalDicatat,
                            'tanggal_bayar' => $request->tanggal_bayar,
                            'keterangan'    => $request->keterangan,
                        ]);
                    } else {
                        // Jika ini record periode di depannya yang sudah ada, tambahkan nominalnya
                        $pembayaranAda->increment('nominal', $nominalDicatat);
                    }
                } else {
                    // Jika periode di depannya masih kosong, buat baris baru
                    pembayaran::create([
                        'id_murid'      => $idMurid,
                        'periode_id'    => $periodeTarget->id,
                        'nominal'       => $nominalDicatat,
                        'tipe'          => 'masuk',
                        'tanggal_bayar' => $request->tanggal_bayar,
                        'keterangan'    => $request->keterangan . " (Alokasi edit dari " . $pmu = Periode::find($pembayaran->periode_id)->nama_periode . ")",
                    ]);
                }

                $uangDibayar -= $nominalDicatat;
                $indexPeriodeSekarang++;
            }

            session(['terakhir_periode_id' => $pembayaran->periode_id]);
            return redirect()->route('pembayaran.index', ['periode_id' => $pembayaran->periode_id])
                             ->with('success', 'Data kas berhasil disesuaikan dan sisa saldo otomatis disalurkan ke minggu berikutnya!');
        }

        // JIKA YANG DI-EDIT ADALAH PENGELUARAN ATAU PEMASUKAN UMUM (NON-MURID)
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