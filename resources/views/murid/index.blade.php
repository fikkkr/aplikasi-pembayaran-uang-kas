@extends('layouts.app')

@section('content')
<style>
    .btn-outline-primary:focus, .btn-outline-primary:active {
        background-color: transparent !important;
        color: #5e72e4 !important; /* Warna asli outline primary */
        box-shadow: none !important;
    }

    .btn-outline-info:focus, .btn-outline-info:active {
        background-color: transparent !important;
        color: #11cdef !important; /* Warna asli outline info */
        box-shadow: none !important;
    }
    /* Perbaikan untuk tombol hapus */
    .btn-outline-danger:focus, .btn-outline-danger:active {
        background-color: transparent !important;
        color: #f5365c !important; /* Warna asli outline danger */
        box-shadow: none !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-white border-0 shadow-sm" role="alert" style="border-radius: 0.5rem; background-image: linear-gradient(310deg, #17ad37 0%, #98ec2d 100%);">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text ms-2"><strong>Berhasil!</strong> {{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show text-white border-0 shadow-sm" role="alert" style="border-radius: 0.5rem; background-image: linear-gradient(310deg, #ea0606 0%, #ff667c 100%);">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="alert-text ms-2"><strong>Gagal!</strong> Data tidak valid atau sudah ada.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="card mb-4 shadow-sm border-0" style="border-radius: 1rem;">
                <div class="card-header pb-0 bg-white d-flex justify-content-between align-items-center" style="border-radius: 1rem 1rem 0 0;">
                    <div>
                        <h6 class="font-weight-bolder mb-0">Daftar Murid XI PPLG 1</h6>
                        <p class="text-xs text-secondary mb-0">Target Kas: <span class="badge badge-sm bg-gradient-info">Rp 5.000</span></p>
                    </div>
                    <button class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus me-1"></i> Tambah Murid
                    </button>
                </div>
                
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nama Murid</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">No. Absen</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Nominal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($murids as $murid)
                                <tr>
                                    <td class="ps-4">
                                        <h6 class="text-sm mb-0">{{ $murid->nama }}</h6>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $murid->absen }}</p>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $totalBayar = $murid->pembayaran->where('tipe', 'masuk')->sum('nominal');
                                            $targetKas = 5000;
                                        @endphp
                                        <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($totalBayar, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @if($totalBayar >= $targetKas)
                                            <span class="badge badge-sm bg-gradient-success">SUDAH LUNAS</span>
                                        @elseif($totalBayar > 0)
                                            <span class="badge badge-sm bg-gradient-warning">BELUM LUNAS</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">BELUM BAYAR</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pembayaran.bayar', $murid->id_murid) }}" class="btn btn-sm btn-outline-primary mb-0">
                                            <i class="fas fa-money-bill-wave me-1"></i> Bayar
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-info mb-0" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $murid->id_murid }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>

                                        <form action="{{ route('murid.destroy', $murid->id_murid) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-0" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
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
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 1rem;">
            <form action="{{ route('murid.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder">Tambah Murid Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" placeholder="Nama Lengkap" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="text-xs font-weight-bold">No. Absen</label>
                        <input type="number" name="absen" class="form-control @error('absen') is-invalid @enderror" value="{{ old('absen') }}" placeholder="Contoh: 1" required>
                        @error('absen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Murid</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($murids as $murid)
<div class="modal fade" id="modalEdit{{ $murid->id_murid }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="border-radius: 1rem;">
            <form action="{{ route('murid.update', $murid->id_murid) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bolder">Edit Data Murid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="text-xs font-weight-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ $murid->nama }}" required>
                    </div>
                    <div class="form-group mt-3">
                        <label class="text-xs font-weight-bold">No. Absen</label>
                        <input type="number" name="absen" class="form-control" value="{{ $murid->absen }}" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection