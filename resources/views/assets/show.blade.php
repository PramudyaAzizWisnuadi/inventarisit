@extends('layouts.app')
@section('title', $asset->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">{{ $asset->name }}</h5>
        <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Hardware</a></li>
            <li class="breadcrumb-item active">{{ $asset->asset_code }}</li>
        </ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assets.label', $asset) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-tag me-1"></i>Cetak Label
        </a>
        <a href="{{ route('assets.edit', $asset) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="deleteConfirm(event)">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        @if($asset->photo)
        <img src="{{ asset('storage/' . $asset->photo) }}" class="rounded-3 w-100 mb-3" style="max-height:220px;object-fit:cover;">
        @else
        <div class="rounded-3 mb-3 d-flex align-items-center justify-content-center" style="height:180px;background:#f1f5f9;">
            <i class="bi bi-pc-display text-muted" style="font-size:4rem;"></i>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted small">Kode Aset</td><td><code class="text-primary fw-600">{{ $asset->asset_code }}</code></td></tr>
                    <tr><td class="text-muted small">Kategori</td><td>{{ $asset->category?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Cabang</td><td>{{ $asset->location?->branch?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Lokasi</td><td>{{ $asset->location?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Vendor</td><td>{{ $asset->vendor?->name ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Kondisi</td>
                        <td><span class="badge {{ $asset->condition == 'Baik' ? 'badge-tersedia' : 'badge-perbaikan' }}">{{ $asset->condition }}</span></td></tr>
                    <tr><td class="text-muted small">Status</td>
                        <td><span class="badge {{ $asset->status == 'Tersedia' ? 'badge-tersedia' : ($asset->status == 'Digunakan' ? 'badge-digunakan' : ($asset->status == 'Dipinjam' ? 'badge-dipinjam' : ($asset->status == 'Dalam Perbaikan' ? 'badge-perbaikan' : 'badge-dihapus'))) }}">{{ $asset->status }}</span></td></tr>
                    <tr><td class="text-muted small">Serial</td><td class="small">{{ $asset->serial_number ?? '-' }}</td></tr>
                    <tr><td class="text-muted small">Dibuat</td><td class="small">{{ $asset->created_at->format('d/m/Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Info Utama -->
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Detail Aset</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">Merek & Model</div>
                        <div class="fw-500">{{ $asset->brand }} {{ $asset->model }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Tanggal Pembelian</div>
                        <div>{{ $asset->purchase_date?->format('d/m/Y') ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Harga Pembelian</div>
                        <div class="fw-600 text-primary">{{ $asset->purchase_price ? 'Rp '.number_format($asset->purchase_price,0,',','.') : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">Garansi</div>
                        <div class="{{ $asset->isWarrantyExpired() ? 'text-danger' : 'text-success' }}">
                            {{ $asset->warranty_expiry?->format('d/m/Y') ?? '-' }}
                            @if($asset->isWarrantyExpired()) <span class="badge badge-perbaikan ms-1">Expired</span> @endif
                        </div>
                    </div>
                    @if($asset->specifications)
                    <div class="col-12">
                        <div class="text-muted small">Spesifikasi</div>
                        <div class="small bg-light p-2 rounded">{{ $asset->specifications }}</div>
                    </div>
                    @endif
                    @if($asset->notes)
                    <div class="col-12">
                        <div class="text-muted small">Catatan</div>
                        <div class="small">{{ $asset->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- History Assignment -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Riwayat Penugasan</h6>
                <a href="{{ route('assignments.create') }}?asset_id={{ $asset->id }}" class="btn btn-sm btn-outline-primary py-0 px-2">+ Tugaskan</a>
            </div>
            <div class="card-body p-0">
                @forelse($asset->assignments as $asgn)
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small fw-500">{{ $asgn->assigned_to }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $asgn->department }} • {{ $asgn->assigned_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($asgn->returned_at)
                        <span class="text-muted small">Kembali: {{ $asgn->returned_at->format('d/m/Y') }}</span>
                        @endif
                        <span class="badge {{ $asgn->status == 'Aktif' ? 'badge-aktif' : 'badge-dihapus' }}">{{ $asgn->status }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">Belum ada riwayat penugasan</div>
                @endforelse
            </div>
        </div>

        <!-- History Maintenance -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Riwayat Pemeliharaan</h6>
                <a href="{{ route('maintenance.create') }}?asset_id={{ $asset->id }}" class="btn btn-sm btn-outline-warning py-0 px-2">+ Jadwalkan</a>
            </div>
            <div class="card-body p-0">
                @forelse($asset->maintenances as $m)
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small fw-500">{{ $m->type }}</div>
                        <div class="text-muted" style="font-size:.72rem;">Teknisi: {{ $m->technician ?? '-' }} • {{ $m->scheduled_at?->format('d/m/Y') }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($m->cost) <span class="small">Rp {{ number_format($m->cost,0,',','.') }}</span> @endif
                        <span class="badge {{ $m->status == 'Selesai' ? 'badge-aktif' : ($m->status == 'Dijadwalkan' ? 'badge-digunakan' : 'badge-perbaikan') }}">{{ $m->status }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">Belum ada riwayat pemeliharaan</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
