@extends('layouts.app')
@section('title', $software->software_name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">{{ $software->software_name }}</h5>
        <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('software.index') }}">Software</a></li>
            <li class="breadcrumb-item active">{{ $software->license_code }}</li>
        </ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('software.edit', $software) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <form method="POST" action="{{ route('software.destroy', $software) }}" onsubmit="deleteConfirm(event)">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-3">
                    <div style="width:72px;height:72px;background:#ede9fe;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto;">
                        <i class="bi bi-disc" style="font-size:2rem;color:#6366f1;"></i>
                    </div>
                </div>
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted small">Kode</td><td><code class="text-primary">{{ $software->license_code }}</code></td></tr>
                    <tr><td class="text-muted small">Publisher</td><td>{{ $software->publisher ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Versi</td><td>{{ $software->version ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Tipe</td><td><span class="badge bg-light text-dark">{{ $software->license_type }}</span></td></tr>
                    <tr><td class="text-muted small">Status</td><td><span class="badge {{ $software->status == 'Aktif' ? 'badge-aktif' : 'badge-expired' }}">{{ $software->status }}</span></td></tr>
                    <tr><td class="text-muted small">Kategori</td><td>{{ $software->category?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Vendor</td><td>{{ $software->vendor?->name ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Detail Lisensi</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Tanggal Pembelian</div>
                        <div>{{ $software->purchase_date?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Harga Pembelian</div>
                        <div class="fw-600 text-primary">{{ $software->purchase_price ? 'Rp '.number_format($software->purchase_price,0,',','.') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Berlaku Hingga</div>
                        <div class="{{ $software->isExpired() ? 'text-danger fw-500' : 'text-success' }}">
                            {{ $software->expiry_date?->format('d/m/Y') ?? 'Permanen' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Penggunaan</div>
                        <div>{{ $software->used_users }} / {{ $software->max_users }} pengguna</div>
                        <div class="progress mt-1" style="height:6px;">
                            <div class="progress-bar {{ $software->used_users >= $software->max_users ? 'bg-danger' : 'bg-success' }}"
                                style="width:{{ $software->max_users ? min(100,$software->used_users/$software->max_users*100) : 0 }}%"></div>
                        </div>
                    </div>
                    @if($software->license_key)
                    <div class="col-12">
                        <div class="text-muted small">License Key</div>
                        <div class="bg-light p-2 rounded font-monospace small">{{ $software->license_key }}</div>
                    </div>
                    @endif
                    @if($software->notes)
                    <div class="col-12">
                        <div class="text-muted small">Catatan</div>
                        <div class="small">{{ $software->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
