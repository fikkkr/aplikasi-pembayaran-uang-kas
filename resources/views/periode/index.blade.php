@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    {{-- BARIS UTAMA --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow">
                
                {{-- Card Header --}}
                <div class="card-header pb-3 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0">
                            <i class="ni ni-calendar-grid-58 text-warning me-2"></i>Manajemen Periode Kas Kelas
                        </h6>
                        <p class="text-xs text-secondary mb-0 mt-1">
                            Atur status aktif atau kunci transaksi kas mingguan di sini.
                        </p>
                    </div>
                    
                    {{-- HANYA ADMIN & BENDAHARA YANG BISA TAMBAH PERIODE --}}
                    @can('kelola-kas')
                        <button type="button" class="btn btn-sm btn-primary mb-0 px-3 py-2 shadow-sm" style="border-radius: 0.5rem;" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                            <i class="ni ni-fat-add text-lg me-1"></i> Tambah Periode Baru
                        </button>
                    @endcan
                </div>

                {{-- Alert Sukses --}}
                @if(session('success'))
                    <div class="mx-4 mt-3 alert alert-success text-white text-xs" style="border-radius: 0.5rem;">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Card Body --}}
                <div class="card-body px-4 pt-0 pb-2 mt-3">
                    
                    {{-- IMPLEMENTASI ACCORDION BOOTSTRAP --}}
                    <div class="accordion" id="accordionPeriode">
                        @forelse($periodeGrouped as $bulanTahun => $daftarPeriode)
                            @php
                                $idBulan = Str::slug($bulanTahun);
                                $isFirst = $loop->first;
                            @endphp

                            <div class="accordion-item mb-3 border border-gray-100 shadow-sm" style="border-radius: 0.75rem; overflow: hidden;">
                                {{-- Header Accordion (Nama Bulan) --}}
                                <h2 class="accordion-header" id="heading-{{ $idBulan }}">
                                    <button class="accordion-button font-weight-bold text-dark p-3 {{ $isFirst ? '' : 'collapsed' }}" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#collapse-{{ $idBulan }}" 
                                            aria-expanded="{{ $isFirst ? 'true' : 'false' }}" 
                                            aria-controls="collapse-{{ $idBulan }}"
                                            style="background-color: #f8f9fa; font-size: 0.85rem;">
                                        <i class="ni ni-folder-17 text-primary me-2 text-sm"></i>
                                        {{ $bulanTahun }} 
                                        <span class="badge bg-gradient-secondary text-xxs ms-2" style="border-radius: 0.25rem;">{{ $daftarPeriode->count() }} Minggu</span>
                                    </button>
                                </h2>

                                {{-- Isi Accordion (Tabel Daftar Minggu) --}}
                                <div id="collapse-{{ $idBulan }}" 
                                     class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" 
                                     aria-labelledby="heading-{{ $idBulan }}" 
                                     data-bs-parent="#accordionPeriode">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive p-0">
                                            <table class="table align-items-center mb-0">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nama Periode Kas</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 25%;">Status</th>
                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 25%;">Aksi Ubah Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($daftarPeriode as $p)
                                                    <tr>
                                                        {{-- Nama Periode --}}
                                                        <td class="align-middle">
                                                            <div class="ps-3">
                                                                <h6 class="mb-0 text-sm font-weight-bold text-dark">{{ $p->nama_periode }}</h6>
                                                                <span class="text-xxs text-secondary">Dibuat: {{ $p->created_at->format('d M Y, H:i') }}</span>
                                                            </div>
                                                        </td>
                                                        
                                                        {{-- Status Badge --}}
                                                        <td class="align-middle text-center">
                                                            @if($p->status === 'aktif')
                                                                <span class="badge badge-sm bg-gradient-success" style="border-radius: 0.5rem; font-size: 0.7rem;">AKTIF (Bisa Input)</span>
                                                            @else
                                                                <span class="badge badge-sm bg-gradient-danger" style="border-radius: 0.5rem; font-size: 0.7rem;">DITUTUP (Terkunci)</span>
                                                            @endif
                                                        </td>
                                                        
                                                        {{-- Akses Tombol / Badge Keterangan --}}
                                                        <td class="align-middle text-center">
                                                            @can('kelola-kas')
                                                                {{-- Jika Admin/Bendahara: Bisa Klik Ubah Status Gembok --}}
                                                                <form action="{{ route('periode.toggle', $p->id) }}" method="POST" class="mb-0">
                                                                    @csrf
                                                                    @if($p->status === 'aktif')
                                                                        <button type="submit" class="btn btn-xs btn-outline-danger mb-0 px-3 py-1.5" style="border-radius: 0.5rem;">
                                                                            <i class="ni ni-lock-circle-open me-1"></i> Tutup Periode
                                                                        </button>
                                                                    @else
                                                                        <button type="submit" class="btn btn-xs btn-outline-success mb-0 px-3 py-1.5" style="border-radius: 0.5rem;">
                                                                            <i class="ni ni-key-25 me-1"></i> Buka Periode
                                                                        </button>
                                                                    @endif
                                                                </form>
                                                            @else
                                                                {{-- Jika Akun Guru: Cuma Muncul Keterangan Read-Only --}}
                                                                <span class="badge bg-light text-secondary text-xxs border" style="border-radius: 0.5rem; font-size: 0.65rem;">
                                                                    <i class="ni ni-bold-right me-1"></i>Hanya Lihat (Read-Only)
                                                                </span>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <span class="text-sm font-weight-bold text-secondary">Belum ada periode kas yang terdaftar, wok.</span>
                            </div>
                        @endforelse
                    </div> {{-- END ACCORDION --}}

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PERIODE (HANYA DI-RENDER UNTUK ADMIN/BENDAHARA AGAR AMAN) --}}
    @can('kelola-kas')
    <div class="modal fade" id="modalTambahPeriode" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 1rem;">
                <div class="modal-header border-0 py-3">
                    <h6 class="modal-title font-weight-bold text-dark">Tambah Periode Baru</h6>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('periode.store') }}" method="POST">
                    @csrf
                    <div class="modal-body py-2">
                        @error('nama_periode')
                            <div class="alert alert-danger text-white text-xs p-2 mb-3" style="border-radius: 0.5rem;">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-group mb-2">
                            <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA PERIODE KAS</label>
                            <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Minggu 1 - Mei 2026" style="border-radius: 0.5rem;" required value="{{ old('nama_periode') }}">
                            <small class="text-muted text-xxs mt-1 d-block">Saran format: <strong>Minggu X - [Bulan] [Tahun]</strong> agar tidak ambigu di db nya.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 py-3">
                        <button type="button" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-sm btn-primary mb-0 shadow-sm" style="border-radius: 0.5rem;">Simpan Periode</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

</div>

{{-- Trigger Modal Otomatis Kalau Validasi Error --}}
@if($errors->has('nama_periode'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalTambahPeriode'));
        myModal.show();
    });
</script>
@endif

@endsection