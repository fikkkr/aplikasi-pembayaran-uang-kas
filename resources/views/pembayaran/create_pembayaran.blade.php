@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Warna Header dinamis: Biru buat Masuk, Merah buat Keluar --}}
            <div class="card shadow border-0">
                <div class="card-header {{ $tipe == 'masuk' ? 'bg-gradient-primary' : 'bg-gradient-danger' }}">
                    <h6 class="text-white mb-0">
                        @if($tipe == 'masuk')
                            <i class="ni ni-money-coins me-2"></i> Form Bayar Kas: {{ $murid->nama }}
                        @else
                            <i class="ni ni-fat-remove me-2"></i> Catat Pengambilan Uang (Pengeluaran)
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <!-- logic buat nampilin error validation form, misal nominal gak diisi atau bukan angka, atau tanggal gak valid, dll -->
                        @if ($errors->any())
                            <div class="alert alert-danger text-white alert-dismissible fade show" role="alert">
                                <span class="alert-text"><strong>Gagal!</strong> {{ $errors->first() }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                    <form action="{{ route('pembayaran.store') }}" method="POST">
                        @csrf
                        
                        {{-- ID Murid & Tipe disembunyiin --}}
                        @if(isset($murid))
                            <input type="hidden" name="id_murid" value="{{ $murid->id_murid }}">
                        @endif
                        <input type="hidden" name="tipe" value="{{ $tipe }}">

                        {{-- Input Nama Murid (Hanya muncul kalau Tipe Masuk) --}}
                        @if($tipe == 'masuk' && isset($murid))
                            <div class="mb-3">
                                <label class="form-label">Nama Murid</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                    <input type="text" class="form-control bg-light" value="{{ $murid->nama }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control" placeholder="Contoh: 5000" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Transaksi</label>
                            <input type="datetime-local" name="tanggal_bayar" class="form-control" value="{{ now()->timezone('asia/jakarta')->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="{{ $tipe == 'masuk' ? 'Misal: bayar kas hari selasa bulan mei' : 'Misal: Beli Sapu & Ember' }}" required></textarea>
                        </div>

                        <hr class="horizontal dark">

                        <div class="d-flex justify-content-between">
                            <a href="/murid" class="btn btn-link text-secondary">Batal</a>
                            <button type="submit" class="btn {{ $tipe == 'masuk' ? 'btn-primary' : 'btn-danger' }} shadow-sm">
                                {{ $tipe == 'masuk' ? 'Simpan Pembayaran 💸' : 'Simpan Pengeluaran 📉' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection