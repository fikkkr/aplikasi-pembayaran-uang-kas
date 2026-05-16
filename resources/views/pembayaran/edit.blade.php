@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-warning py-3">
                    <h6 class="text-white mb-0 d-flex align-items-center">
                        <i class="ni ni-settings-gear-65 me-2 text-white opacity-10"></i> Edit Nominal Pembayaran
                    </h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if(isset($murid))
                            <div class="mb-3">
                                <label class="form-label text-xs font-weight-bold">Nama Murid</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ni ni-single-02"></i></span>
                                    <input type="text" class="form-control bg-light" value="{{ $murid->nama }}" readonly>
                                </div>
                            </div>
                            @if($periode_id)
                                <p class="text-xs text-primary font-weight-bold mb-3">Periode: {{ \App\Models\Periode::find($periode_id)->nama_periode }}</p>
                            @endif
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Nominal Baru (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control" value="{{ $pembayaran->nominal }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Tanggal Transaksi</label>
                            <input type="datetime-local" name="tanggal_bayar" class="form-control" value="{{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-xs font-weight-bold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" required>{{ $pembayaran->keterangan }}</textarea>
                        </div>

                        <hr class="horizontal dark">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pembayaran.index', ['periode_id' => $periode_id]) }}" class="btn btn-link text-secondary">Batal</a>
                            <button type="submit" class="btn btn-warning shadow-sm text-white">
                                <i class="ni ni-check-bold me-1"></i> Perbarui Nominal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection