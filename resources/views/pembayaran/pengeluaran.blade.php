@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 col-md-6 mx-auto">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="ni ni-cloud-upload-96 text-danger me-2"></i>Input Pengeluaran Kas Kelas
            </h6>
        </div>
        <div class="card-body">
            {{-- Alert Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success text-white text-xs mb-4 shadow-sm" style="border-radius: 0.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Alert Jika Saldo Kurang (Dari Validasi Controller) --}}
            @error('nominal')
                @if(str_contains($message, 'Saldo kas tidak mencukupi'))
                    <div class="alert alert-danger text-white text-xs mb-4 shadow-sm" style="border-radius: 0.5rem;">
                        <i class="ni ni-bold-cancel me-1"></i> {{ $message }}
                    </div>
                @endif
            @enderror

            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf
                {{-- Hidden input penanda bahwa ini pengeluaran --}}
                <input type="hidden" name="tipe" value="keluar">

                {{-- Dropdown Pilihan Periode --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">PERIODE KAS</label>
                    <select name="periode_id" class="form-select form-control" style="border-radius: 0.5rem;" required>
                        <option value="" disabled selected>-- Pilih Periode --</option>
                        @foreach($semuaPeriode as $p)
                            <option value="{{ $p->id }}" {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                    @error('periode_id')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Input Nominal --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">NOMINAL PENGELUARAN (Rp)</label>
                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 15000" style="border-radius: 0.5rem;" value="{{ old('nominal') }}" required>
                    @if($errors->has('nominal') && !str_contains($errors->first('nominal'), 'Saldo kas'))
                        <small class="text-danger text-xs mt-1 d-block">{{ $errors->first('nominal') }}</small>
                    @endif
                </div>

                {{-- Input Tanggal --}}
                <div class="form-group mb-3">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">TANGGAL PENGELUARAN</label>
                    <input type="date" name="tanggal_bayar" class="form-control" style="border-radius: 0.5rem;" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                    @error('tanggal_bayar')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Input Keterangan --}}
                <div class="form-group mb-4">
                    <label class="form-control-label text-xs font-weight-bold text-secondary">KEPERLUAN / KETERANGAN</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Beli spidol hitam 2 biji & sapu lidi baru" style="border-radius: 0.5rem;" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <small class="text-danger text-xs mt-1 d-block">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;">Batal</a>
                    <button type="submit" class="btn btn-sm btn-danger mb-0 shadow-sm" style="border-radius: 0.5rem;">
                        <i class="ni ni-check-bold text-xs me-1"></i> Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection