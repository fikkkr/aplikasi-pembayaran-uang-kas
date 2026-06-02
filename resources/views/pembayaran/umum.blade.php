@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 col-md-6 mx-auto">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="ni ni-cloud-download-95 text-success me-2"></i>Input Pemasukan Umum Kas
            </h6>
        </div>
        <div class="card-body">
            {{-- Alert Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success text-white text-xs mb-4 shadow-sm" style="border-radius: 0.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf
                {{-- Hidden input penanda bahwa ini pemasukan --}}
                <input type="hidden" name="tipe" value="masuk">

                {{-- Input Nominal --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">NOMINAL PEMASUKAN (Rp)</label>
                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 50000" style="border-radius: 0.5rem;" value="{{ old('nominal') }}" required>
                    @error('nominal')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror   
                </div>

                {{-- Input Tanggal --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">TANGGAL PEMASUKAN</label>
                    {{-- FIX: Mengubah format date ke Y-m-d yang valid untuk element input HTML --}}
                    <input type="date" name="tanggal_bayar" class="form-control" style="border-radius: 0.5rem;" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Input Keterangan --}}
                <div class="form-group mb-4">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">KETERANGAN / SUMBER</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Uang Lomba Drama" style="border-radius: 0.5rem;" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;">Batal</a>
                    <button type="submit" class="btn btn-sm btn-success mb-0 shadow-sm" style="border-radius: 0.5rem;">
                        <i class="ni ni-check-bold text-xs me-1"></i> Simpan Pemasukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection