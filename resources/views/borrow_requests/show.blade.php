@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Detail Peminjaman: {{ $borrow_request->request_number }}</h5>
    <div class="d-flex gap-2">
        @if($borrow_request->status == 'approved')
        <a href="{{ route('borrow-requests.print-bast', $borrow_request) }}" target="_blank" class="btn btn-outline-success btn-sm">
            <i class="bi bi-printer me-1"></i>Cetak BAST
        </a>
        @endif
        <a href="{{ route('borrow-requests.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-600">Informasi Pengajuan</h6></div>
            <div class="card-body p-0">
                <table class="table table-borderless mb-0">
                    <tbody>
                        <tr><td class="text-muted w-25">Status</td><td>
                            <span class="badge {{ $borrow_request->status == 'rejected' ? 'bg-danger' : 'bg-primary' }}">
                                {{ strtoupper($borrow_request->status) }}
                            </span>
                        </td></tr>
                        <tr><td class="text-muted">Akun Pemohon</td><td>{{ $borrow_request->user->name ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Nama Peminjam (Sesuai BAST)</td><td class="fw-bold text-primary">{{ $borrow_request->borrower_name }}</td></tr>
                        <tr><td class="text-muted">Tanggal Diajukan</td><td>{{ \Carbon\Carbon::parse($borrow_request->request_date)->format('d F Y') }}</td></tr>
                        <tr><td class="text-muted">Catatan Pemohon</td><td>{{ $borrow_request->notes ?? '-' }}</td></tr>
                        
                        <tr><td colspan="2"><hr class="my-1"></td></tr>
                        <tr><td colspan="2">
                            <div class="fw-600 mb-2">Daftar Aset Terkait:</div>
                            <ul class="list-group list-group-flush border align-middle mb-0">
                                @foreach($borrow_request->details as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                    <div>
                                        <div class="fw-600 text-dark">{{ $detail->asset->name }}</div>
                                        <div class="small text-muted">{{ $detail->asset->asset_code }} • {{ $detail->asset->brand }}</div>
                                    </div>
                                    @if($detail->returned_at)
                                        <span class="badge bg-secondary">Dikembalikan</span>
                                    @else
                                        @if($borrow_request->status == 'approved')
                                            <span class="badge bg-success">Dipinjam</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-white"><h6 class="mb-0 fw-600">Aksi Proses (Admin)</h6></div>
            <div class="card-body">
                @if($borrow_request->status == 'approved')
                    <form action="{{ route('borrow-requests.return', $borrow_request) }}" method="POST">
                        @csrf
                        <div class="alert alert-warning py-2 small">Aksi ini akan menandai seluruh barang peminjaman ini sebagai telah dikembalikan dan mengubah status aset menjadi Tersedia.</div>
                        <button class="btn btn-primary w-100 btn-sm text-white">Terima Pengembalian Aset</button>
                    </form>
                @endif
                
                @if(in_array($borrow_request->status, ['returned', 'completed']))
                    <div class="text-center text-muted small">Aset sudah dikembalikan.<br>Status: {{ strtoupper($borrow_request->status) }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
