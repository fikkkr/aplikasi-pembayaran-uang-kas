@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
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
                    
                    {{-- DROPDOWN MINGGUAN & TAMBAH PERIODE --}}
                    <div class="d-flex align-items-center gap-2">
                        <label for="pilih_minggu" class="text-xs font-weight-bold text-secondary mb-0 text-uppercase">Periode:</label>
                        {{-- FIX: Parameter diseragamkan menjadi 'periode_id' agar dibaca utuh oleh Controller --}}
                        <select name="periode_id" id="pilih_minggu" class="form-select form-select-sm shadow-sm" style="border-radius: 0.5rem; width: 140px; font-size: 0.75rem; padding: 0.4rem 0.5rem;" onchange="window.location.href='?periode_id='+this.value">
                            @forelse($semuaPeriode ?? [] as $p)
                                <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>{{ $p->nama_periode }}</option>
                            @empty
                                <option value="1">Minggu 1</option>
                                <option value="2">Minggu 2</option>
                                <option value="3">Minggu 3</option>
                                <option value="4">Minggu 4</option>
                            @endforelse
                        </select>
                        
                        @can('kelola-murid')
                        <button type="button" class="btn btn-sm btn-primary mb-0 px-3 py-2 shadow-sm d-flex align-items-center" style="border-radius: 0.5rem; font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#modalTambahPeriode">
                            <i class="ni ni-fat-add text-lg me-1"></i> Tambah Periode
                        </button>
                        @endcan
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
                                            @can('kelola-murid')
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
                                                            data-id="{{ $m->pembayaran->first()->id }}"
                                                            data-absen="{{ $m->absen }}"
                                                            data-nama="{{ $m->nama }}"
                                                            data-nominal="{{ $nominalSkrg }}">
                                                        Edit
                                                    </button>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary text-xxs" style="border-radius: 0.5rem;">Hanya Lihat</span>
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

{{-- ==================== BAGIAN MODAL-MODAL ==================== --}}

{{-- 1. MODAL TAMBAH PERIODE BARU --}}
<div class="modal fade" id="modalTambahPeriode" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Tambah Periode Kas Baru</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('periode.store') }}" method="POST">
                @csrf
                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA PERIODE / MINGGU KE-</label>
                        <input type="text" name="nama_periode" class="form-control" placeholder="Contoh: Minggu 5" style="border-radius: 0.5rem;" required>
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

{{-- 2. MODAL INPUT BAYAR KAS (Khusus yang Belum Bayar) --}}
<div class="modal fade" id="modalBayarKas" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="false" style="background: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 1rem;">
            <div class="modal-header border-0 py-3">
                <h6 class="modal-title font-weight-bold text-dark">Input Pembayaran Kas</h6>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBayarKas" method="POST" action="{{ route('pembayaran.store') }}">
                @csrf
                <input type="hidden" name="id_murid" id="bayar_id_murid">
                {{-- FIX: Konsisten lempar value parameter periodeId dari controller --}}
                <input type="hidden" name="periode_id" value="{{ $periodeId }}">
                {{-- Tambahan input required bawaan validasi store controller --}}
                <input type="hidden" name="tipe" value="masuk">
                <input type="hidden" name="tanggal_bayar" value="{{ date('Y-m-d') }}">
                <input type="hidden" name="keterangan" value="Pembayaran kas reguler">
                
                <div class="modal-body py-2">
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NAMA MURID</label>
                        <input type="text" id="bayar_nama" class="form-control bg-light" style="border-radius: 0.5rem;" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-control-label text-xs font-weight-bold text-secondary">NOMINAL BAYAR (Rp)</label>
                        <input type="number" name="nominal" class="form-control" placeholder="Contoh: 5000" style="border-radius: 0.5rem;" required min="1">
                        <small class="text-muted text-xxs mt-1 d-block text-danger">Pembayaran > Rp 5.000 otomatis dialokasikan ke periode berikutnya.</small>
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

{{-- 3. MODAL EDIT KAS MURID (Khusus yang Sudah Bayar) --}}
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
                {{-- Input hidden tambahan pelengkap validasi controller --}}
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

{{-- ==================== SCRIPT JAVASCRIPT VANILLA ==================== --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Handle Modal Input Bayar Kas
        const modalBayar = document.getElementById('modalBayarKas');
        if (modalBayar) {
            modalBayar.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-nama');
                
                document.getElementById('bayar_id_murid').value = id;
                document.getElementById('bayar_nama').value = nama;
            });
        }

        // Handle Modal Edit Kas Murid
        const modalEdit = document.getElementById('modalEditMurid');
        if (modalEdit) {
            modalEdit.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id'); // Ini menampung ID Pembayaran
                const absen = button.getAttribute('data-absen');
                const nama = button.getAttribute('data-nama');
                const nominal = button.getAttribute('data-nominal');
                
                document.getElementById('edit_absen').value = absen;
                document.getElementById('edit_nama').value = nama;
                document.getElementById('edit_nominal').value = nominal;
                
                // Form edit diarahkan ke route update pembayaran menggunakan ID Pembayarannya langsung
                document.getElementById('formEditMurid').action = `/pembayaran/${id}`;
            });
        }
    });
</script>