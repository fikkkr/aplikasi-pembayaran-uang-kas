@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 col-md-5 mx-auto">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold text-dark">Tambah Periode Kas Baru</h6>
        </div>
        <div class="card-body">
            {{-- Informasi Periode Saat Ini --}}
            @if($periodeTerakhir)
                <div class="alert alert-info text-white text-xs mb-4 shadow-sm" style="border-radius: 0.5rem;">
                    <i class="ni ni-info-short me-1"></i> Periode terakhir yang terdaftar saat ini adalah: 
                    <strong>{{ $periodeTerakhir->nama_periode }}</strong>
                </div>
            @endif

            <form action="{{ route('periode.store') }}" method="POST">
                @csrf
                
                {{-- Input Nama Periode --}}
                <div class="form-group mb-4">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA PERIODE / MINGGUAN</label>
                    <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Minggu 5 atau Mei Minggu 2" required autofocus>
                    @error('nama_periode')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;">Batal</a>
                    <button type="submit" class="btn btn-sm btn-primary mb-0 shadow-sm" style="border-radius: 0.5rem;">
                        <i class="ni ni-check-bold text-xs me-1"></i> Simpan Periode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection