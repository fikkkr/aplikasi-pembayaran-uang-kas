@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header pb-0 d-flex justify-content-between">
                    <h6>Daftar Murid XI PPLG 1</h6>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Murid</button>
                </div>
                
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Murid</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No. Absen</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        @if ($errors->any())
                        <div class="alert alert-danger text-white mx-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                                <tbody>
                                    @foreach($murids as $murid)
                                    <tr>
                                        <td class="ps-4">
                                            <h6 class="text-sm mb-0">{{ $murid->nama }}</h6>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $murid->absen }}</p>
                                        </td>
                                        <td class="align-middle text-sm">
                                            @php
                                                // Hitung total uang yang sudah dibayar murid ini
                                                $totalBayar = $murid->pembayaran->where('tipe', 'masuk')->sum('nominal');
                                                $targetKas = 5000; // Contoh target kas per semester/bulan
                                            @endphp

                                            @if($totalBayar >= $targetKas)
                                                <span class="badge badge-sm bg-gradient-success">Sudah Lunas</span>
                                            @elseif($totalBayar > 0 && $totalBayar < $targetKas)
                                                <span class="badge badge-sm bg-gradient-warning">Belum Lunas (Rp {{ number_format($totalBayar) }})</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-danger">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('pembayaran.bayar', $murid->id_murid) }}" class="btn btn-sm btn-primary mb-0">
                                                <i class="ni ni-money-coins"></i> Bayar
                                            </a>
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
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('murid.store') }}" method="POST">
        @csrf
        <div class="modal-header"><h5>Tambah Murid Baru</h5></div>
        <div class="modal-body">
            <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap" required>
            <input type="text" name="absen" class="form-control" placeholder="Absen" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection