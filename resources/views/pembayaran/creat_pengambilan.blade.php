@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0 border-start border-danger border-4">
        <div class="card-header pb-0">
            <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-cloud-upload-96 text-danger text-sm opacity-10"></i>
                </div>
                <h6 class="font-weight-bold text-danger mb-0">Catat Pengambilan Uang (Pengeluaran)</h6>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipe" value="keluar">
                <!-- id_murid tidak dikirim (null) -->

                <div class="mb-3">
                    <label class="form-label">Nominal Pengeluaran (Rp)</label>
                    <input type="number" name="nominal" class="form-control border-danger" placeholder="Contoh: 20000" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keperluan / Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Beli sapu baru, bayar fotokopi absen, dll" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Keluar</label>
                    <input type="datetime-local" name="tanggal_bayar" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="/" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-danger">Simpan Pengeluaran 📉</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection