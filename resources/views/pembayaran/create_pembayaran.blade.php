@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header {{ $tipe == 'masuk' ? 'bg-gradient-primary' : 'bg-gradient-danger' }} py-3">
        <h6 class="text-white mb-0 d-flex align-items-center">
            {{-- Ikon tanpa kotak putih, langsung tampil dengan warna putih --}}
            @if(isset($murid))
                <i class="ni ni-money-coins me-2 text-white opacity-10"></i>
            @elseif($tipe == 'masuk')
                <i class="ni ni-cloud-download-95 me-2 text-white opacity-10"></i>
            @else
                <i class="ni ni-cloud-upload-96 me-2 text-white opacity-10"></i>
            @endif
            
            @if(isset($murid))
                Form Bayar Kas: {{ $murid->nama }}
            @elseif($tipe == 'masuk')
                Catat Pemasukan Umum
            @else
                Catat Pengambilan Uang (Pengeluaran)
            @endif
        </h6>
    </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger text-white alert-dismissible fade show" role="alert" style="background-color: #f5365c;">
                            <span class="alert-text"><strong>Gagal!</strong> {{ $errors->first() }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('pembayaran.store') }}" method="POST">
                        @csrf
                        
                        {{-- ID Murid (nullable) & Tipe --}}
                        <input type="hidden" name="id_murid" value="{{ $murid->id_murid ?? '' }}">
                        <input type="hidden" name="tipe" value="{{ $tipe }}">

                        @if(isset($murid))
                            <div class="mb-3">
                                <label class="form-label text-xs font-weight-bold">Nama Murid</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                    <input type="text" class="form-control bg-light" value="{{ $murid->nama }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control" placeholder="Contoh: 5000" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Tanggal Transaksi</label>
                            <input type="datetime-local" name="tanggal_bayar" class="form-control" value="{{ now()->timezone('asia/jakarta')->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="{{ $tipe == 'masuk' ? 'Misal: Hadiah lomba kebersihan' : 'Misal: Beli Sapu & Ember' }}" required></textarea>
                        </div>

                        <hr class="horizontal dark">

                        <div class="d-flex justify-content-between">
                            <a href="{{ isset($murid) ? '/murid' : '/dashboard' }}" class="btn btn-link text-secondary">Batal</a>
                            <button type="submit" class="btn {{ $tipe == 'masuk' ? 'btn-primary' : 'btn-danger' }} shadow-sm">
                                {{ $tipe == 'masuk' ? 'Simpan Pembayaran' : 'Simpan Pengeluaran' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection