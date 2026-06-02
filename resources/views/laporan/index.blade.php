@extends('layouts.app')

@section('content')
<style>
    /* Animasi halus saat Accordion dibuka */
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #2d3748 !important;
    }

    /* FIX: Menghilangkan efek "nyala terus/focused" setelah tombol print diklik */
    .btn-outline-success:focus, 
    .btn-outline-success:active,
    .btn-outline-success:visited {
        background-color: transparent !important;
        color: #2dce89 !important; /* Warna asli success Argon */
        box-shadow: none !important;
    }
    .btn-outline-success:hover {
        background-color: #2dce89 !important;
        color: #fff !important;
    }

    /* OPTIMASI PRINT: Menyembunyikan komponen gak penting saat cetak PDF */
    @media print {
        /* Sembunyikan Sidebar, Navbar, dan Tombol Cetak */
        .navbar,
        .sidenav,
        #sidenav-main,
        .btn,
        footer {
            display: none !important;
        }
        /* Lebarkan konten utama agar penuh selembar kertas */
        .main-content,
        .container-fluid {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card {
            box-shadow: none !important;
            border: 0 !important;
        }
    }
</style>

<div class="container-fluid py-4">

    {{-- TABEL LAPORAN ACCORDION DYNAMIC --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow">
                <div class="card-header pb-3 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0"><i class="ni ni-collection text-success me-2"></i>Rekapitulasi Keuangan Per Bulan</h6>
                        <p class="text-xs text-secondary mb-0 mt-1">Laporan transparan kas masuk dan keluar kelas XI PPLG 1.</p>
                    </div>
                    {{-- Tombol Cetak Global --}}
                    <button class="btn btn-sm btn-outline-success mb-0 px-3 py-2" style="border-radius: 0.5rem;" onclick="this.blur(); window.print();">
                        <i class="fa fa-print me-1"></i> Cetak / Print Halaman
                    </button>
                </div>

                <div class="card-body px-4 pt-0 pb-2 mt-3">
                    <div class="accordion" id="accordionLaporan">
                        
                        {{-- PROSES LOOPING UTAMA BERDASARKAN BULAN --}}
                        @foreach($laporanGrouped as $bulan => $data)
                        <div class="accordion-item mb-3 border border-gray-100 shadow-sm" style="border-radius: 0.75rem; overflow: hidden;">
                            <h2 class="accordion-header" id="heading{{ Str::slug($bulan) }}">
                                <button class="accordion-button font-weight-bold text-dark p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ Str::slug($bulan) }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                    <i class="ni ni-folder-17 text-success me-2 text-sm"></i> {{ $bulan }}
                                </button>
                            </h2>
                            <div id="collapse{{ Str::slug($bulan) }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#accordionLaporan">
                                <div class="accordion-body p-0">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Minggu / Periode</th>
                                                    <th class="text-uppercase text-warning text-xxs font-weight-bolder opacity-7 text-center">Tunggakan Murid</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan</th>
                                                    <th class="text-uppercase text-success text-xxs font-weight-bolder opacity-7 text-center">Pemasukan</th>
                                                    <th class="text-uppercase text-danger text-xxs font-weight-bolder opacity-7 text-center">Pengeluaran</th>
                                                    <th class="text-uppercase text-primary text-xxs font-weight-bolder opacity-7 text-center">Sisa Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- LOOPING DETAIL MINGGUAN DI DALAM BULAN TERKAIT --}}
                                                @foreach($data['detail_mingguan'] as $minggu)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="text-sm font-weight-bold text-dark">{{ $minggu['nama_periode'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($minggu['jumlah_nunggak'] > 0)
                                                            <span class="badge bg-gradient-warning text-xxs px-2 py-1" style="border-radius: 0.5rem;">
                                                                <i class="fa fa-users me-1"></i> {{ $minggu['jumlah_nunggak'] }} Belum Bayar
                                                            </span>
                                                        @else
                                                            <span class="badge bg-gradient-success text-xxs px-2 py-1" style="border-radius: 0.5rem;">
                                                                <i class="fa fa-check me-1"></i> Lunas Semua
                                                            </span>
                                                        @endif
                                                    </td>
                                                    {{-- BARU: Kolom Keterangan Kas Mingguan --}}
                                                    <td style="max-width: 250px; white-space: normal;">
                                                        <span class="text-xs text-secondary">
                                                            {{ $minggu['keterangan'] ?: '-' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-sm text-success font-weight-bold">
                                                        Rp {{ number_format($minggu['pemasukan'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center text-sm text-danger font-weight-bold">
                                                        Rp {{ number_format($minggu['pengeluaran'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center text-sm text-primary font-weight-bold">
                                                        Rp {{ number_format($minggu['saldo'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @endforeach

                                                {{-- BARIS TOTALAN AKHIR BULAN --}}
                                                <tr class="bg-gray-50 font-weight-bold">
                                                    <td class="ps-4" colspan="3">
                                                        <span class="text-xs font-weight-bolder text-uppercase">Total {{ $bulan }}</span>
                                                    </td>
                                                    <td class="text-center text-sm text-success font-weight-bolder">
                                                        Rp {{ number_format($data['total_pemasukan'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center text-sm text-danger font-weight-bolder">
                                                        Rp {{ number_format($data['total_pengeluaran'], 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center text-sm text-primary font-weight-bolder">
                                                        Rp {{ number_format($data['total_saldo'], 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div> {{-- END ACCORDION --}}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection