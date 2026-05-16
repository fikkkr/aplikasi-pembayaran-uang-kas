@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        {{-- Card Header --}}
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">Monitoring Kas Mingguan</h6>
            
            {{-- Wrapper Dropdown Periode Sejajar ke Kanan + Tombol Tambah --}}
            <div class="d-flex align-items-center gap-2">
                <span class="text-xs font-weight-bold text-secondary">Periode:</span>
                <form action="{{ route('pembayaran.index') }}" method="GET" class="mb-0">
                    <select name="periode_id" class="form-select form-select-sm" style="border-radius: 0.5rem; min-width: 160px;" onchange="this.form.submit()">
                        @foreach($semuaPeriode as $p)
                            <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </form>
                
                {{-- TOMBOL TAMBAH PERIODE BARU --}}
                <a href="{{ route('periode.create') }}" class="btn btn-sm btn-outline-primary mb-0 px-2 py-1 shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 0.5rem; height: 31px;" title="Tambah Periode Baru">
                    <i class="ni ni-fat-add text-lg"></i> Tambah Periode
                </a>
            </div>
        </div> 
        
        {{-- Tabel Kas Mingguan --}}
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-xs font-weight-bold opacity-7 text-center" style="width: 12%;">NO. ABSEN</th>
                        <th class="text-xs font-weight-bold opacity-7">NAMA MURID</th>
                        <th class="text-center text-xs font-weight-bold opacity-7">NOMINAL BAYAR</th>
                        <th class="text-center text-xs font-weight-bold opacity-7">STATUS</th>
                        <th class="text-center text-xs font-weight-bold opacity-7" style="width: 15%;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Target kas mingguan kelas XI PPLG 1
                        $targetKas = 5000; 
                    @endphp

                    @foreach($murids as $m)
                        @php
                            // Ambil total nominal kas murid pada periode terpilih (mendukung sistem split rapelan)
                            $totalBayarMingguIni = \App\Models\pembayaran::where('id_murid', $m->id_murid)
                                                    ->where('periode_id', $periodeId)
                                                    ->where('tipe', 'masuk')
                                                    ->sum('nominal');

                            // Ambil salah satu data transaksi untuk mengambil ID utama link edit
                            $pembayaranManual = \App\Models\pembayaran::where('id_murid', $m->id_murid)
                                                    ->where('periode_id', $periodeId)
                                                    ->where('tipe', 'masuk')
                                                    ->first();

                            $idBayar = $pembayaranManual ? ($pembayaranManual->id_pembayaran ?? $pembayaranManual->id) : null;
                        @endphp
                    <tr>
                        {{-- No Absen --}}
                        <td class="text-center align-middle">
                            <span class="text-sm font-weight-bold text-secondary">{{ $m->absen ?? '-' }}</span>
                        </td>
                        
                        {{-- Nama Murid --}}
                        <td class="align-middle">
                            <div class="ps-3 text-sm font-weight-bold text-dark">{{ $m->nama }}</div>
                        </td>
                        
                        {{-- Nominal Bayar --}}
                        <td class="text-center align-middle">
                            <span class="text-sm font-weight-bold text-secondary">
                                Rp {{ number_format($totalBayarMingguIni, 0, ',', '.') }}
                            </span>
                        </td>
                        
                        {{-- Status Logika --}}
                        <td class="text-center align-middle">
                            @if($totalBayarMingguIni >= $targetKas)
                                <span class="badge badge-sm bg-gradient-success">SUDAH LUNAS</span>
                            @elseif($totalBayarMingguIni > 0 && $totalBayarMingguIni < $targetKas)
                                <span class="badge badge-sm bg-gradient-warning text-white">BELUM LUNAS</span>
                            @else
                                <span class="badge badge-sm bg-gradient-danger">BELUM BAYAR</span>
                            @endif
                        </td>
                        
                        {{-- Kolom Aksi Dinamis --}}
                        <td class="text-center align-middle">
                            @if($totalBayarMingguIni == 0)
                                <a href="{{ route('pembayaran.bayar', [$m->id_murid, 'periode_id' => $periodeId]) }}" 
                                   class="btn btn-sm btn-primary mb-0 shadow-sm px-3 py-2" style="border-radius: 0.5rem;">
                                    <i class="ni ni-money-coins text-xs me-1"></i> Bayar
                                </a>
                            @else
                                <a href="{{ route('pembayaran.edit', $idBayar) }}" 
                                   class="btn btn-sm btn-warning text-white mb-0 shadow-sm px-3 py-2" style="border-radius: 0.5rem;">
                                    <i class="ni ni-mode-edit text-xs me-1"></i> Edit Nominal
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection