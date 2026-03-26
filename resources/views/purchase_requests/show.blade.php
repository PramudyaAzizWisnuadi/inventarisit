@extends('layouts.app')
@section('title', 'Detail Pengadaan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Detail Pengajuan: {{ $purchase_request->request_number }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('purchase-requests.print', $purchase_request) }}" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak Surat
        </a>
        <a href="{{ route('purchase-requests.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-600">Rincian Barang</h6></div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr><td class="text-muted w-25">Status</td><td>
                            <span class="badge {{ str_contains($purchase_request->status, 'reject') ? 'bg-danger' : 'bg-primary' }}">
                                {{ strtoupper(str_replace('_', ' ', $purchase_request->status)) }}
                            </span>
                        </td></tr>
                        <tr><td class="text-muted">Pemohon</td><td class="fw-500">{{ $purchase_request->user->name }}</td></tr>
                        <tr><td class="text-muted align-middle">Daftar Barang</td>
                            <td>
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light small text-muted">
                                        <tr>
                                            <th>Nama Barang</th>
                                            <th>Spesifikasi</th>
                                            <th>Merk</th>
                                            <th class="text-center">Qty</th>
                                            <th>Harga Satuan</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchase_request->details as $detail)
                                        <tr class="small">
                                            <td class="fw-500">{{ $detail->item_name }}</td>
                                            <td>{{ $detail->specification ?? '-' }}</td>
                                            <td>{{ $detail->brand ?? '-' }}</td>
                                            <td class="text-center">{{ $detail->qty }}</td>
                                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr><td class="text-muted">Total Keseluruhan</td><td class="fw-600 text-success" style="font-size: 1.1rem;">Rp {{ number_format($purchase_request->total_price, 0, ',', '.') }}</td></tr>
                        <tr><td class="text-muted">Tanggal Diajukan</td><td>{{ \Carbon\Carbon::parse($purchase_request->request_date)->format('d F Y') }}</td></tr>
                        <tr><td class="text-muted">Catatan</td><td>{{ $purchase_request->notes ?? '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0 fw-600">Aksi Proses</h6></div>
            <div class="card-body">
                @if($purchase_request->status == 'pending')
                    <form action="{{ route('purchase-requests.approve-manager', $purchase_request) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-success w-100 btn-sm">Setuju (Manager)</button>
                    </form>
                @endif
                
                @if($purchase_request->status == 'approved_manager')
                    <form action="{{ route('purchase-requests.approve-director', $purchase_request) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-success w-100 btn-sm">Setuju (Direksi)</button>
                    </form>
                @endif
                
                @if($purchase_request->status == 'approved_director')
                    <form action="{{ route('purchase-requests.process', $purchase_request) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-primary w-100 btn-sm">Proses Pembelian</button>
                    </form>
                @endif
                
                @if($purchase_request->status == 'purchasing')
                    <form action="{{ route('purchase-requests.complete', $purchase_request) }}" method="POST" class="mb-2">
                        @csrf
                        <button class="btn btn-success w-100 btn-sm">Barang Diterima Selesai</button>
                    </form>
                @endif

                @if(!in_array($purchase_request->status, ['rejected', 'received']))
                    <hr>
                    <form action="{{ route('purchase-requests.reject', $purchase_request) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <input type="text" name="reason" class="form-control form-control-sm" placeholder="Alasan penolakan" required>
                        </div>
                        <button class="btn btn-danger w-100 btn-sm">Tolak Pengajuan</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
