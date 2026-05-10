@extends('layouts.app')

@section('content')
<style>
    .hover-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .hover-card:hover {
        transform: translateY(-10px); /* Kartu naik 10px */
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important; /* Bayangan makin tebal */
    }
</style>

<div class="container-fluid py-4">
    
    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Saldo Kas</p>
                                <h5 class="font-weight-bolder">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="ni ni-money-coins text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pemasukan</p>
                                <h5 class="font-weight-bolder text-success">+ Rp {{ number_format($totalMasuk, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="ni ni-bold-up text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengeluaran</p>
                                <h5 class="font-weight-bolder text-danger">- Rp {{ number_format($totalKeluar, 0, ',', '.') }}</h5>

                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="ni ni-bold-down text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card border-start border-success border-4 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Sudah Lunas</p>
                                <h5 class="font-weight-bolder text-success">{{ $sudahBayar }} Murid</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="ni ni-check-bold text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card border-start border-warning border-4 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Belum Lunas</p>
                                <h5 class="font-weight-bolder text-warning">{{ $belumLunas }} Murid</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="ni ni-bulb-61 text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-4">
            <div class="card hover-card border-start border-danger border-4 shadow-sm">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Belum Bayar</p>
                                <h5 class="font-weight-bolder text-danger">{{ $belumBayar }} Murid</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="ni ni-fat-remove text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header pb-0">
                <h6>Riwayat Transaksi</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama/Keterangan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nominal</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksiTerakhir as $trx)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">
                                                @if($trx->id_murid)
                                                    {{ $trx->murid->nama }}
                                                @else
                                                    {{ $trx->tipe == 'masuk' ? 'Pemasukan Umum' : 'Pengeluaran Umum' }}
                                                @endif
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">{{ $trx->keterangan }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm {{ $trx->tipe == 'masuk' ? 'bg-gradient-success' : 'bg-gradient-danger' }}">
                                        {{ strtoupper($trx->tipe) }}
                                    </span>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($trx->nominal) }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $trx->tanggal_bayar }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection