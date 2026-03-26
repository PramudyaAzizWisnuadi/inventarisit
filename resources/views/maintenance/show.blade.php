@extends('layouts.app')
@section('title', 'Detail Pemeliharaan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Detail Pemeliharaan</h5>
    <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
</div>
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><td class="text-muted small fw-500" width="180">Aset</td><td class="fw-500">{{ $maintenance->asset?->name }} <span class="text-muted small">({{ $maintenance->asset?->asset_code }})</span></td></tr>
            <tr><td class="text-muted small fw-500">Tipe</td><td><span class="badge bg-light text-dark">{{ $maintenance->type }}</span></td></tr>
            <tr><td class="text-muted small fw-500">Status</td>
                <td><span class="badge {{ $maintenance->status=='Selesai'?'badge-aktif':($maintenance->status=='Dijadwalkan'?'badge-digunakan':'badge-dipinjam') }}">{{ $maintenance->status }}</span></td></tr>
            <tr><td class="text-muted small fw-500">Teknisi</td><td>{{ $maintenance->technician ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Vendor Service</td><td>{{ $maintenance->vendor_service ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Jadwal</td><td>{{ $maintenance->scheduled_at?->format('d/m/Y') ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Selesai</td><td>{{ $maintenance->completed_at?->format('d/m/Y') ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Biaya</td><td class="fw-600">{{ $maintenance->cost ? 'Rp '.number_format($maintenance->cost,0,',','.') : '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Deskripsi Masalah</td><td>{{ $maintenance->problem_description ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Tindakan</td><td>{{ $maintenance->action_taken ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Catatan</td><td>{{ $maintenance->notes ?? '-' }}</td></tr>
            <tr><td class="text-muted small fw-500">Dibuat Oleh</td><td>{{ $maintenance->creator?->name ?? '-' }}, {{ $maintenance->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
</div>
</div>
@endsection
