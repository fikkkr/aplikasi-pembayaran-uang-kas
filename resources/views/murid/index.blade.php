@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 font-weight-bold">Monitoring Kas Mingguan</h6>
            
            <form action="{{ route('pembayaran.index') }}" method="GET" id="filterForm">
                <select name="periode_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach($semuaPeriode as $p)
                        <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_periode }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-xs font-weight-bold opacity-7">Nama Murid</th>
                        <th class="text-center text-xs font-weight-bold opacity-7">Status</th>
                        <th class="text-center text-xs font-weight-bold opacity-7">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($murids as $m)
                    <tr>
                        <td><div class="ps-3 text-sm">{{ $m->nama }}</div></td>
                        <td class="text-center">
                            @if($m->pembayaran->count() > 0)
                                <span class="badge badge-sm bg-gradient-success">Lunas</span>
                            @else
                                <span class="badge badge-sm bg-gradient-danger">Belum Bayar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($m->pembayaran->count() == 0)
                                {{-- Tombol Bayar dengan melempar periode_id --}}
                                <a href="{{ route('pembayaran.create', ['id_murid' => $m->id_murid, 'periode_id' => $periodeId, 'tipe' => 'masuk']) }}" 
                                   class="btn btn-sm btn-primary py-1">Bayar</a>
                            @else
                                <i class="ni ni-check-bold text-success"></i>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection