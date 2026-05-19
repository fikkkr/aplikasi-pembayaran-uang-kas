@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    
    {{-- CARD INFORMASI REKAPAN PER MINGGU (WITH HOVER EFFECT) --}}
    <div class="row mb-4">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow" 
                 style="border-radius: 1rem; transition: all 0.3s ease; cursor: pointer;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(0,0,0,0.175)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,0.15)'">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between" style="min-height: 65px;">
                        <div>
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Kas Masuk Minggu Ini</p>
                            <h5 class="font-weight-bolder mb-0 text-success mt-1">
                                Rp {{ number_format($totalMasukMingguIni ?? 0, 0, ',', '.') }}
                            </h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-success shadow border-radius-md" 
                             style="width: 48px; height: 48px; min-width: 48px; margin: 0; display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important;">
                            <i class="ni ni-money-coins text-lg opacity-10" style="top: 0; margin: 0; position: static;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card border-0 shadow" 
                 style="border-radius: 1rem; transition: all 0.3s ease; cursor: pointer;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(0,0,0,0.175)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,0.15)'">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between" style="min-height: 65px;">
                        <div>
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Lunas (Minggu Ini)</p>
                            <h5 class="font-weight-bolder mb-0 text-dark mt-1">
                                {{ $muridLunasMingguIni ?? 0 }} <span class="text-sm font-weight-normal text-secondary">Murid</span>
                            </h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-info shadow border-radius-md" 
                             style="width: 48px; height: 48px; min-width: 48px; margin: 0; display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important;">
                            <i class="ni ni-check-bold text-lg opacity-10" style="top: 0; margin: 0; position: static;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-sm-6">
            <div class="card border-0 shadow" 
                 style="border-radius: 1rem; transition: all 0.3s ease; cursor: pointer;"
                 onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 1rem 3rem rgba(0,0,0,0.175)'"
                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .5rem 1rem rgba(0,0,0,0.15)'">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between" style="min-height: 65px;">
                        <div>
                            <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Nunggak (Minggu Ini)</p>
                            <h5 class="font-weight-bolder mb-0 text-danger mt-1">
                                {{ $muridBelumLunasMingguIni ?? 0 }} <span class="text-sm font-weight-normal text-secondary">Murid</span>
                            </h5>
                        </div>
                        <div class="icon icon-shape bg-gradient-danger shadow border-radius-md" 
                             style="width: 48px; height: 48px; min-width: 48px; margin: 0; display: flex !important; align-items: center !important; justify-content: center !important; text-align: center !important;">
                            <i class="ni ni-fat-remove text-lg opacity-10" style="top: 0; margin: 0; position: static;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 border-0 shadow">
                {{-- CARD HEADER --}}
                <div class="card-header pb-0 bg-white d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="mb-0">
                            <i class="ni ni-money-coins text-warning me-2"></i>Kas Mingguan XI PPLG 1
                        </h6>
                        <small class="text-muted text-xxs blockquote-footer mt-1">Pembayaran lebih dari Rp 5.000 otomatis dialokasikan ke periode berikutnya</small>
                    </div>
                    
                    {{-- DROPDOWN MINGGUAN & BADGE STATUS GEMBOK PERIODE --}}
                    <div class="d-flex align-items-center gap-2">
                        <label for="pilih_minggu" class="text-xs font-weight-bold text-secondary mb-0 text-uppercase">Periode:</label>
                        
                        <div class="position-relative d-inline-block">
                            <select name="periode_id" id="pilih_minggu" 
                                    class="form-select form-select-sm shadow-sm border-secondary-subtle fw-semibold text-secondary" 
                                    style="border-radius: 0.5rem; width: 170px; font-size: 0.75rem; padding: 0.45rem 2rem 0.45rem 0.75rem; cursor: pointer; transition: all 0.2s;" 
                                    onchange="window.location.href='?periode_id='+this.value">
                                @forelse($semuaPeriode ?? [] as $p)
                                    <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                                        📅 {{ $p->nama_periode }}
                                    </option>
                                @empty
                                    <option value="">Belum Ada Periode</option>
                                @endforelse
                            </select>
                        </div>
                        
                        {{-- MENCARI OBJECT PERIODE SEKARANG UNTUK CEK STATUSNYA --}}
                        @php
                            $periodeSekarang = collect($semuaPeriode)->firstWhere('id', $periodeId);
                        @endphp

                        {{-- BADGE PENANDA STATUS PERIODE --}}
                        @if($periodeSekarang && ($periodeSekarang->status === 'aktif' || empty($periodeSekarang->status)))
                            <span class="badge bg-gradient-success text-xxs px-2.5 py-2 shadow-sm d-flex align-items-center mb-0" style="border-radius: 0.5rem; height: 32px;">
                                <i class="ni ni-key-25 me-1 text-xs"></i> Terbuka
                            </span>
                        @else
                            <span class="badge bg-gradient-danger text-xxs px-2.5 py-2 shadow-sm d-flex align-items-center mb-0" style="border-radius: 0.5rem; height: 32px;">
                                <i class="ni ni-lock-circle-open me-1 text-xs"></i> Terkunci
                            </span>
                        @endif
                    </div>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 10%;">No. Absen</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Nama Lengkap Murid</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 20%;">Nominal Bayar</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 20%;">Status Kas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($murids as $m)
                                <tr>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-sm font-weight-bold">{{ $m->absen ?? '-' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="ps-4">
                                            <h6 class="mb-0 text-sm font-weight-bold text-dark text-capitalize">{{ $m->nama }}</h6>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold text-dark">Rp {{ number_format($m->pembayaran->first()->nominal ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $nominalSkrg = $m->pembayaran->first()->nominal ?? 0;
                                        @endphp
                                        @if($nominalSkrg >= 5000)
                                            <span class="badge badge-sm bg-gradient-success" style="border-radius: 0.5rem;">Sudah Lunas</span>
                                        @elseif($nominalSkrg > 0 && $nominalSkrg < 5000)
                                            <span class="badge badge-sm bg-gradient-info" style="border-radius: 0.5rem;">Belum Lunas</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger" style="border-radius: 0.5rem;">Belum Bayar</span>
                                        @endif
                                    </td>
                                    
                                    {{-- Tombol Aksi Bersyarat --}}
                                    <td class="align-middle text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @can('kelola-kas')
                                                {{-- JIKA PERIODE AKTIF: Tampilkan Operasional Tambah/Edit Duit --}}
                                                @if($periodeSekarang && $periodeSekarang->status === 'aktif')
                                                    @if($nominalSkrg == 0)
                                                        <button type="button" class="btn btn-sm btn-success mb-0 px-3 py-1.5 text-xs font-weight-bold" style="border-radius: 0.5rem;" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalBayarKas"
                                                                data-id="{{ $m->id_murid }}"
                                                                data-nama="{{ $m->nama }}">
                                                            Bayar
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-warning mb-0 px-3 py-1.5 text-xs font-weight-bold" style="border-radius: 0.5rem;" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#modalEditMurid"
                                                                data-id="{{ $m->pembayaran->first()->id ?? '' }}"
                                                                data-absen="{{ $m->absen }}"
                                                                data-nama="{{ $m->nama }}"
                                                                data-nominal="{{ $nominalSkrg }}">
                                                            Edit
                                                        </button>
                                                    @endif
                                                {{-- JIKA PERIODE DITUTUP: Kunci Total Data Kas --}}
                                                @else
                                                    <span class="badge bg-secondary text-xxs text-white" style="border-radius: 0.5rem;">
                                                        <i class="ni ni-lock-circle-open text-xxs me-1"></i> Terkunci
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary text-xxs text-white shadow-sm" style="border-radius: 0.5rem;">Hanya Lihat</span>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <span class="text-xs font-weight-bold text-secondary">Belum ada data murid di kelas ini.</span>
                                    </td>
                                </tr>
                                @endempty
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- ==================== BAGIAN MODAL-MODAL ==================== --}}

{{-- 1. MODAL INPUT BAYAR KAS --}}
<div class="modal fade" id="modalBayarKas" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Input Pembayaran Kas</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBayarKas" method="POST" action="{{ route('pembayaran.store') }}">
                @csrf
                <input type="hidden" name="id_murid" id="bayar_id_murid" value="{{ old('id_murid') }}">
                <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                <input type="hidden" name="tipe" value="masuk">
                <input type="hidden" name="tanggal_bayar" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="keterangan" value="Pembayaran kas reguler">
                
                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA MURID</label>
                        <input type="text" id="bayar_nama" class="form-control bg-light" value="{{ old('bayar_nama') }}" style="border-radius: 0.5rem;" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NOMINAL BAYAR (Rp)</label>
                        <input type="number" name="nominal" class="form-control @error('nominal') is-invalid @enderror" placeholder="Contoh: 5000" style="border-radius: 0.5rem;" required min="1" value="{{ old('nominal') }}">
                        
                        {{-- KOTAK PESAN ERROR DARI CONTROLLER --}}
                        @error('nominal')
                            <div class="invalid-feedback font-weight-bold d-block mt-2 text-sm" style="white-space: normal;">
                                ⚠️ {{ $message }}
                            </div>
                        @enderror

                        <small class="text-muted text-xxs mt-2 d-block text-danger">Pembayaran > Rp 5.000 otomatis dialokasikan ke periode berikutnya.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 py-3">
                    <button type="button" class="btn btn-sm btn-light mb-0" style="border-radius: 0.5rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success mb-0 shadow-sm" style="border-radius: 0.5rem;">Konfirmasi Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. MODAL EDIT KAS MURID --}}
<div class="modal fade" id="modalEditMurid" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Ubah Data Kas Murid</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditMurid" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="tanggal_bayar" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="keterangan" value="Perubahan nominal kas">

                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NOMOR ABSEN</label>
                        <input type="number" id="edit_absen" class="form-control bg-light" style="border-radius: 0.5rem;" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA LENGKAP</label>
                        <input type="text" id="edit_nama" class="form-control bg-light" style="border-radius: 0.5rem;" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">UBAH NOMINAL KAS (Rp)</label>
                        <input type="number" name="nominal" id="edit_nominal" class="form-control" style="border-radius: 0.5rem;" required>
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
        // Handle Modal Input Bayar Kas
        const modalBayar = document.getElementById('modalBayarKas');
        if (modalBayar) {
            modalBayar.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (button) {
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    
                    document.getElementById('bayar_id_murid').value = id;
                    document.getElementById('bayar_nama').value = nama;
                }
            });
        }

        // Handle Modal Edit Kas Murid
        const modalEdit = document.getElementById('modalEditMurid');
        if (modalEdit) {
            modalEdit.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (button) {
                    const id = button.getAttribute('data-id'); 
                    const absen = button.getAttribute('data-absen');
                    const nama = button.getAttribute('data-nama');
                    const nominal = button.getAttribute('data-nominal');
                    
                    document.getElementById('edit_absen').value = absen;
                    document.getElementById('edit_nama').value = nama;
                    document.getElementById('edit_nominal').value = nominal;
                    
                    document.getElementById('formEditMurid').action = `/pembayaran/${id}`;
                }
            });
        }

        @if($errors->has('nominal'))
            if (modalBayar) {
                document.getElementById('bayar_id_murid').value = "{{ old('id_murid') }}";
                document.getElementById('bayar_nama').value = "{{ old('bayar_nama') }}";
                
                var myModal = new bootstrap.Modal(modalBayar);
                myModal.show();
            }
        @endif
    });
</script>