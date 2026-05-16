@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 col-md-6 mx-auto">
        <div class="card-header bg-white py-3">
            {{-- Pengaman: Cek apakah ini kas murid atau transaksi umum --}}
            <h6 class="mb-0 font-weight-bold">
                {{ $murid ? 'Bayar Kas: ' . $murid->nama : 'Input Transaksi Umum' }}
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf
                
                {{-- Input Hidden Data Pembayaran --}}
                <input type="hidden" name="id_murid" value="{{ $murid ? $murid->id_murid : '' }}">
                <input type="hidden" name="tipe" value="{{ $tipe }}">

                {{-- Pilih Periode (Otomatis memilih periode aktif atau bisa diganti bendahara) --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold">Periode Pembayaran</label>
                    <select name="periode_id" class="form-select">
                        @foreach($semuaPeriode as $p)
                            <option value="{{ $p->id }}" {{ $periode_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nominal Uang Kas --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold">Nominal (Rp)</label>
                    <input type="number" name="nominal" class="form-control" value="5000" min="1" required>
                    @error('nominal')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tanggal Bayar --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold">Tanggal</label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                {{-- Keterangan --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" required>{{ $murid ? 'Bayar kas minggu ini' : '' }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-light mb-0">Batal</a>
                    <button type="submit" class="btn btn-sm btn-primary mb-0">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection