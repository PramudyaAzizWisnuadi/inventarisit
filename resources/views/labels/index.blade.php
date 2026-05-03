@extends('layouts.app')
@section('title', 'Cetak Label Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Cetak Label Aset</h5>
        <p class="text-muted small mb-0">Pilih aset lalu cetak label QR Code</p>
    </div>
</div>

<form action="{{ route('labels.print') }}" method="POST" id="labelForm">
@csrf
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Pilih Aset</h6>
                <div class="form-check mb-0">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label small" for="selectAll">Pilih Semua</label>
                </div>
            </div>
            <div class="card-body p-0" style="max-height:500px;overflow-y:auto;">
                @foreach($assets as $asset)
                <label class="d-flex align-items-center px-3 py-2 border-bottom cursor-pointer" style="cursor:pointer;" for="asset_{{ $asset->id }}">
                    <input type="checkbox" name="ids[]" value="{{ $asset->id }}" id="asset_{{ $asset->id }}" class="form-check-input me-3 asset-cb">
                    <div class="flex-grow-1">
                        <div class="small fw-500">{{ $asset->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;"><code>{{ $asset->asset_code }}</code> • {{ $asset->category?->name }} • {{ $asset->location?->branch?->name ?? '-' }} / {{ $asset->location?->name }}</div>
                    </div>
                    <span class="badge {{ $asset->status=='Digunakan'?'badge-digunakan':($asset->status=='Tersedia'?'badge-tersedia':'badge-perbaikan') }}">{{ $asset->status }}</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Opsi Cetak</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-500 small">Format Output</label>
                    <div class="d-flex gap-2">
                        <div class="form-check flex-fill">
                            <input class="form-check-input" type="radio" name="format" value="html" id="fmtHtml" checked>
                            <label class="form-check-label small" for="fmtHtml"><i class="bi bi-globe me-1"></i>Preview HTML</label>
                        </div>
                        <div class="form-check flex-fill">
                            <input class="form-check-input" type="radio" name="format" value="pdf" id="fmtPdf">
                            <label class="form-check-label small" for="fmtPdf"><i class="bi bi-filetype-pdf me-1"></i>PDF</label>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info p-2 small">
                    <i class="bi bi-info-circle me-1"></i>Label berisi: nama aset, kode, QR code, lokasi, dan tanggal cetak
                </div>
            </div>
        </div>
        <div class="card mb-3" style="border: 2px dashed #e2e8f0;">
            <div class="card-body text-center py-3">
                <div class="text-muted small mb-1">Preview Label</div>
                <div style="background:#f8fafc;border-radius:8px;padding:12px;display:inline-block;min-width:160px;">
                    <div style="font-size:.6rem;font-weight:700;color:#1e293b;margin-bottom:6px;">ASSET MD GROUP</div>
                    <div style="width:60px;height:60px;background:#e2e8f0;border-radius:4px;margin:0 auto 6px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-qr-code" style="font-size:2rem;color:#6366f1;"></i>
                    </div>
                    <div style="font-size:.65rem;font-weight:700;color:#1e293b;">HW-2024-0001</div>
                    <div style="font-size:.55rem;color:#64748b;">Nama Aset</div>
                    <div style="font-size:.5rem;color:#94a3b8;">Cabang</div>
                    <div style="font-size:.5rem;color:#94a3b8;">Lokasi</div>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-printer me-2"></i>Cetak Label</button>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.asset-cb').forEach(c => c.checked = this.checked);
});

document.getElementById('labelForm').addEventListener('submit', function(e) {
    const checked = document.querySelectorAll('.asset-cb:checked').length;
    if (checked === 0) {
        e.preventDefault();
        Toast.fire({
            icon: 'warning',
            title: 'Pilih minimal satu aset untuk dicetak'
        });
    }
});
</script>
@endpush
