@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header pb-0">
                    <h6>Edit Data Murid: {{ $murid->nama }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('murid.update', $murid->id_murid) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $murid->nama }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Absen</label>
                            <input type="number" name="absen" class="form-control @error('absen') is-invalid @enderror" value="{{ $murid->absen }}" required>

                            @error('absen')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label>Kelas</label>
                            <input type="text" class="form-control" value="{{ $murid->kelas }}" disabled>
                            <small class="text-muted">*Kelas tidak dapat diubah (Default: XI PPLG 1)</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('murid.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary text-white">Simpan Perubahan</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection