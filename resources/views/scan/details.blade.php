@extends('layouts.app')

@section('title', 'Detail Aset')

@section('content')
<div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeInUp">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-700">Detail Aset</h6>
        <button type="button" class="btn-close" onclick="resetScanner()"></button>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12 text-center mb-2">
                @if($asset->photo)
                    <img src="{{ asset('storage/' . $asset->photo) }}" class="rounded-3 img-fluid border" style="max-height: 180px; object-fit: cover;" alt="{{ $asset->name }}">
                @else
                    <div class="rounded-3 d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; background: #f1f5f9;">
                        <i class="bi bi-pc-display text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
            </div>
            
            <div class="col-12">
                <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                    <div class="text-muted small mb-1">Kode Aset</div>
                    <div class="h5 fw-700 mb-0"><code class="text-primary">{{ $asset->asset_code }}</code></div>
                    @if($asset->serial_number)
                        <div class="text-muted extra-small mt-1">S/N: {{ $asset->serial_number }}</div>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small px-0" style="width: 100px;">Nama Barang</td>
                        <td class="fw-600 px-0">{{ $asset->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small px-0">Merek/Model</td>
                        <td class="small px-0">{{ $asset->brand }} {{ $asset->model }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small px-0">Kategori</td>
                        <td class="px-0">{{ $asset->category?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small px-0">Kondisi</td>
                        <td class="px-0"><span class="badge {{ $asset->condition == 'Baik' ? 'badge-tersedia' : 'badge-perbaikan' }}">{{ $asset->condition }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted small px-0">Status</td>
                        <td class="px-0"><span class="badge {{ $asset->status == 'Tersedia' ? 'badge-tersedia' : ($asset->status == 'Digunakan' ? 'badge-digunakan' : 'badge-perbaikan') }}">{{ $asset->status }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted small px-0">Lokasi</td>
                        <td class="px-0"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $asset->location->name ?? '-' }}</td>
                    </tr>
                    @if($asset->user)
                    <tr>
                        <td class="text-muted small px-0">Pengguna</td>
                        <td class="px-0"><i class="bi bi-person me-1"></i> {{ $asset->user->name }}</td>
                    </tr>
                    @endif
                    @if($asset->notes)
                    <tr>
                        <td class="text-muted small px-0">Catatan</td>
                        <td class="px-0 small text-dark">{{ $asset->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- History Summary (Optional/Compact) -->
            @if($asset->assignments->count() > 0)
            <div class="col-12 mt-2">
                <h6 class="small fw-700 border-bottom pb-1 mb-2">Penugasan Terakhir</h6>
                @php $lastAsgn = $asset->assignments->last(); @endphp
                <div class="d-flex justify-content-between align-items-center small">
                    <div>
                        <div class="fw-500">{{ $lastAsgn->assigned_to }}</div>
                        <div class="text-muted extra-small">{{ $lastAsgn->assigned_at->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge {{ $lastAsgn->status == 'Aktif' ? 'badge-aktif' : 'badge-dihapus' }}">{{ $lastAsgn->status }}</span>
                </div>
            </div>
            @endif

            <div class="col-12 pt-3">
                <a href="{{ route('assets.show', $asset) }}" class="btn btn-primary w-100 py-2 rounded-3 fw-600">
                    <i class="bi bi-eye me-2"></i> Lihat Detail Lengkap
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .extra-small { font-size: 0.72rem; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .fw-500 { font-weight: 500; }
    .animate__animated { animation-duration: 0.4s; }
</style>
