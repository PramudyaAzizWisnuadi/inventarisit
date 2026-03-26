@extends('layouts.app')
@section('title', 'Daftar Pengadaan Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Pengadaan Barang</h5>
    <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Buat Pengajuan Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nomor</th>
                    <th>Pemohon</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td class="ps-3"><span class="badge bg-light text-dark border">{{ $req->request_number }}</span></td>
                    <td>{{ $req->user->name }}</td>
                    <td>
                        @foreach($req->details as $detail)
                            <div class="fw-500 small mb-1">
                                {{ $detail->item_name }} <span class="text-muted">({{ $detail->qty }}x)</span>
                                @if($detail->specification) <br><span class="text-muted" style="font-size: 0.75rem;">Spec: {{ $detail->specification }}</span> @endif
                            </div>
                        @endforeach
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $req->details->sum('qty') }} Item</span></td>
                    <td><span class="fw-600">Rp {{ number_format($req->total_price, 0, ',', '.') }}</span></td>
                    <td>
                        <span class="badge {{ $req->status == 'completed' || $req->status == 'received' ? 'bg-success' : ($req->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                            {{ strtoupper(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') }}</td>
                    <td class="pe-3 text-end">
                        <a href="{{ route('purchase-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
