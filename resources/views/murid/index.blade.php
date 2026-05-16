@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        {{-- Card 1: Total Siswa --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body p-3">
                    <div class="custom-card-container">
                        <div>
                            <p class="text-sm mb-0 text-uppercase font-weight-bold text-secondary">Total Siswa</p>
                            <h5 class="font-weight-bolder mb-0 mt-1">
                                {{ $murids->count() }} <span class="text-sm font-weight-normal text-secondary">Orang</span>
                            </h5>
                        </div>
                        <div class="rounded-circle bg-gradient-warning shadow-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="ni ni-single-02 text-white" style="font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Sudah Lunas --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body p-3">
                    <div class="custom-card-container">
                        <div>
                            <p class="text-sm mb-0 text-uppercase font-weight-bold text-secondary">Sudah Lunas</p>
                            <h5 class="font-weight-bolder mb-0 mt-1 text-success">
                                {{ $totalLunas ?? 0 }} <span class="text-sm font-weight-normal text-secondary">Murid</span>
                            </h5>
                        </div>
                        <div class="rounded-circle bg-gradient-success shadow-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="ni ni-check-bold text-white" style="font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Belum Lunas --}}
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body p-3">
                    <div class="custom-card-container">
                        <div>
                            <p class="text-sm mb-0 text-uppercase font-weight-bold text-secondary">Belum Lunas</p>
                            <h5 class="font-weight-bolder mb-0 mt-1 text-info">
                                {{ $totalBelumLunas ?? 0 }} <span class="text-sm font-weight-normal text-secondary">Murid</span>
                            </h5>
                        </div>
                        <div class="rounded-circle bg-gradient-info shadow-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="ni ni-money-coins text-white" style="font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Belum Bayar --}}
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow">
                <div class="card-body p-3">
                    <div class="custom-card-container">
                        <div>
                            <p class="text-sm mb-0 text-uppercase font-weight-bold text-secondary">Belum Bayar</p>
                            <h5 class="font-weight-bolder mb-0 mt-1 text-danger">
                                {{ $totalBelumBayar ?? 0 }} <span class="text-sm font-weight-normal text-secondary">Murid</span>
                            </h5>
                        </div>
                        <div class="rounded-circle bg-gradient-danger shadow-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="ni ni-fat-remove text-white" style="font-size: 1.2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS TABEL MURID --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow">
                {{-- Card Header --}}
                <div class="card-header pb-0 bg-white d-flex align-items-center justify-content-between">
                    <h6 class="mb-0">
                        <i class="ni ni-bullet-list-67 text-warning me-2"></i>Manajemen Data Murid XI PPLG 1
                    </h6>
                    <button type="button" class="btn btn-sm btn-primary mb-0 px-3 py-2 shadow-sm" style="border-radius: 0.5rem;" data-bs-toggle="modal" data-bs-target="#modalTambahMurid">
                        <i class="ni ni-fat-add text-lg me-1"></i> Tambah Murid Baru
                    </button>
                </div>

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mx-4 mt-3 alert alert-success text-white text-xs" style="border-radius: 0.5rem;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Card Body --}}
                <div class="card-body px-0 pt-0 pb-2 mt-3">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 15%;">No. Absen</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nama Lengkap Murid</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 25%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($murids as $m)
                                <tr>
                                    {{-- No Absen --}}
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-sm font-weight-bold">{{ $m->absen ?? '-' }}</span>
                                    </td>
                                    
                                    {{-- Nama Murid --}}
                                    <td class="align-middle">
                                        <div class="ps-4">
                                            <h6 class="mb-0 text-sm font-weight-bold text-dark text-capitalize">{{ $m->nama }}</h6>
                                        </div>
                                    </td>
                                    
                                    {{-- Tombol Aksi --}}
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-warning mb-0 px-3 py-2" style="border-radius: 0.5rem;" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEditMurid"
                                                    data-id="{{ $m->id_murid }}"
                                                    data-absen="{{ $m->absen }}"
                                                    data-nama="{{ $m->nama }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('murid.destroy', $m->id_murid) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus murid bernama {{ $m->nama }}?')" class="mb-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-0 px-3 py-2" style="border-radius: 0.5rem;">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <span class="text-xs font-weight-bold text-secondary">Belum ada data murid di kelas ini.</span>
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
</div>
@endsection

{{-- Modals berada di luar section utama --}}
<div class="modal fade" id="modalTambahMurid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Tambah Murid Baru</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('murid.store') }}" method="POST">
                @csrf
                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NOMOR ABSEN</label>
                        <input type="number" name="absen" class="form-control" placeholder="Contoh: 1" style="border-radius: 0.5rem;" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA LENGKAP</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama murid..." style="border-radius: 0.5rem;" required>
                    </div>
                </div>
                <div class="modal-footer border-0 py-3">
                    <button type="button" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary mb-0 shadow-sm" style="border-radius: 0.5rem;">Simpan Murid</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditMurid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Ubah Data Murid</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMurid" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NOMOR ABSEN</label>
                        <input type="number" name="absen" id="edit_absen" class="form-control" style="border-radius: 0.5rem;" required value="">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA LENGKAP</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" style="border-radius: 0.5rem;" required value="">
                    </div>
                </div>
                <div class="modal-footer border-0 py-3">
                    <button type="button" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-warning text-white mb-0 shadow-sm" style="border-radius: 0.5rem;">Perbarui Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modalEdit = document.getElementById('modalEditMurid');
        if (modalEdit) {
            modalEdit.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const absen = button.getAttribute('data-absen');
                const nama = button.getAttribute('data-nama');
                
                document.getElementById('edit_absen').value = absen;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('formEditMurid').action = `/murid/${id}`;
            });
        }
    });
</script>