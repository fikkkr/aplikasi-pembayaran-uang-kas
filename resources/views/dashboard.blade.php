@extends('layouts.app')

@section('content')


<style>
    /* Styling Card agar seragam, rapi, dan responsif */
    .card-stats {
        border-radius: 1rem;
        border: none;
        background: #ffffff;
        transition: transform 0.2s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        height: 100%; /* Biar tinggi card sama rata dalam satu baris */
    }
    .card-stats:hover { transform: translateY(-5px); }
    
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    /* Border khusus untuk kategori Status Murid */
    .border-lunas { border-left: 5px solid #2dce89 !important; }
    .border-belum-lunas { border-left: 5px solid #fb6340 !important; }
    .border-belum-bayar { border-left: 5px solid #f5365c !important; }

    /* Atur padding atas biar gak nempel navbar */
    .dashboard-container {
        padding-top: 40px;
    }

    /* --- EDITAN KHUSUS LAYOUT HP --- */
    @media (max-width: 575.98px) {
        .dashboard-container {
            padding-top: 10px;
        }
        /* Perkecil angka nominal & teks */
        h4.font-weight-bolder {
            font-size: 0.85rem !important; 
        }
        .text-xs {
            font-size: 0.6rem !important;
            letter-spacing: 0px !important;
        }
        /* Perkecil icon biar gak menutupi teks */
        .icon-box {
            width: 32px !important;
            height: 32px !important;
        }
        .icon-box i {
            font-size: 0.75rem !important;
        }
        /* Kurangi padding card biar lebih slim */
        .card-stats {
            padding: 10px !important;
        }
        /* Jarak antar card dipersempit */
        .mb-4 {
            margin-bottom: 12px !important;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-xl-4 col-md-6 col-6 mb-4">
            <div class="card card-stats p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Saldo Kas</p>
                        <h4 class="font-weight-bolder mb-0">Rp {{ number_format($saldoAkhir ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div class="icon-box bg-gradient-primary shadow-primary">
                        <i class="ni ni-money-coins text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-6 mb-4">
            <div class="card card-stats p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</p>
                        <h4 class="font-weight-bolder text-success mb-0">+ Rp {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div class="icon-box bg-gradient-success shadow-success">
                        <i class="ni ni-bold-up text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 col-12 mb-4">
            <div class="card card-stats p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</p>
                        <h4 class="font-weight-bolder text-danger mb-0">- Rp {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</h4>
                    </div>
                    <div class="icon-box bg-gradient-danger shadow-danger">
                        <i class="ni ni-bold-down text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 col-6 mb-4">
            <div class="card card-stats border-lunas p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Sudah Lunas</p>
                        <h4 class="font-weight-bolder text-success mb-0">{{ $sudahBayar ?? 0 }} Murid</h4>
                    </div>
                    <div class="icon-box bg-gradient-success shadow-success">
                        <i class="ni ni-check-bold text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 col-6 mb-4">
            <div class="card card-stats border-belum-lunas p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Belum Lunas</p>
                        <h4 class="font-weight-bolder text-warning mb-0">{{ $belumLunas ?? 0 }} Murid</h4>
                    </div>
                    <div class="icon-box bg-gradient-warning shadow-warning">
                        <i class="ni ni-bulb-61 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12 col-12 mb-4">
            <div class="card card-stats border-belum-bayar p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-xs font-weight-bold text-muted text-uppercase mb-1">Belum Bayar</p>
                        <h4 class="font-weight-bolder text-danger mb-0">{{ $belumBayar ?? 0 }} Murid</h4>
                    </div>
                    <div class="icon-box bg-gradient-danger shadow-danger">
                        <i class="ni ni-fat-remove text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 1rem;">
            <div class="card-header pb-0 bg-white" style="border-radius: 1rem 1rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bolder">Riwayat Transaksi Terakhir</h6>
                    <span class="badge badge-sm bg-gradient-secondary">10 Data Terbaru</span>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Absen</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama / Keperluan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Keterangan bayar</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $item)
                            <tr style="border-bottom: 1px solid #f8f9fa;">
                                <td class="ps-3">
                                    <h6 class="mb-0 text-xs">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</h6>
                                </td>

                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">
                                        {{ $item->murid->absen ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $item->murid->nama ?? 'Pengeluaran Umum' }}
                                    </p>
                                </td>

                                <td style="max-width: 200px; white-space: normal;">
                                    <p class="text-xs text-muted mb-0">
                                        {{ $item->keterangan ?? '-' }}
                                    </p>
                                </td>

                                <td class="align-middle text-center">
                                    @if($item->tipe == 'masuk')
                                        <span class="badge badge-sm bg-gradient-success">MASUK</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-danger">KELUAR</span>
                                    @endif
                                </td>

                                <td class="align-middle text-center">
                                    <span class="text-sm font-weight-bold {{ $item->tipe == 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $item->tipe == 'masuk' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-sm font-weight-bold mb-0">Belum ada data transaksi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection