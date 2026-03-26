@extends('layouts.app')
@section('title', 'Inventaris Hardware')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Inventaris Hardware</h5>
        <p class="text-muted small mb-0">Kelola semua perangkat hardware IT</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assets.index', array_merge(request()->all(), ['format'=>'excel'])) }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Excel
        </a>
        <a href="{{ route('labels.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-tag me-1"></i>Tag
        </a>
        <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Tambah
        </a>
    </div>
</div>


<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Kode</th>
                        <th>Nama / Merek</th>
                        <th>Kategori</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Garansi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $asset)
                    <tr>
                        <td><input type="checkbox" class="asset-check" value="{{ $asset->id }}"></td>
                        <td><code class="text-primary small">{{ $asset->asset_code }}</code></td>
                        <td>
                            <div class="fw-500 small">{{ $asset->name }}</div>
                            <div class="text-muted" style="font-size:.72rem;">{{ $asset->brand }} {{ $asset->model }}</div>
                        </td>
                        <td><span class="small">{{ $asset->category?->name ?? '-' }}</span></td>
                        <td>
                            <div class="small fw-500">{{ $asset->location?->name ?? '-' }}</div>
                            <div class="text-muted" style="font-size:.7rem;">{{ $asset->location?->branch?->name ?? 'Tanpa Cabang' }}</div>
                        </td>
                        <td>
                            @php $cond = $asset->condition; @endphp
                            <span class="badge {{ $cond == 'Baik' ? 'badge-tersedia' : ($cond == 'Rusak' ? 'badge-perbaikan' : 'badge-dipinjam') }}">{{ $cond }}</span>
                        </td>
                        <td>
                            @php $st = $asset->status; @endphp
                            <span class="badge {{ $st == 'Tersedia' ? 'badge-tersedia' : ($st == 'Digunakan' ? 'badge-digunakan' : ($st == 'Dipinjam' ? 'badge-dipinjam' : ($st == 'Dalam Perbaikan' ? 'badge-perbaikan' : 'badge-dihapus'))) }}">{{ $st }}</span>
                        </td>
                        <td>
                            @if($asset->warranty_expiry)
                                <span class="small {{ $asset->isWarrantyExpired() ? 'text-danger' : 'text-success' }}">
                                    {{ $asset->warranty_expiry->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-xs btn-sm btn-outline-primary py-0 px-2" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('assets.edit', $asset) }}" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('assets.label', $asset) }}" class="btn btn-xs btn-sm btn-outline-success py-0 px-2" title="Label"><i class="bi bi-tag"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bulk label print -->
<div id="bulkBar" class="fixed-bottom p-3 bg-dark text-white d-none justify-content-between align-items-center" style="border-radius:12px 12px 0 0;left:var(--sidebar-width);">
    <span><span id="selectedCount">0</span> aset dipilih</span>
    <form method="POST" action="{{ route('labels.print') }}" id="bulkForm">
        @csrf
        <div id="bulkInputs"></div>
        <button type="submit" class="btn btn-light btn-sm"><i class="bi bi-tag me-1"></i>Cetak Label Terpilih</button>
    </form>
</div>
@endsection
@push('scripts')
<script>
const checks = document.querySelectorAll('.asset-check');
const bulkBar = document.getElementById('bulkBar');
const bulkInputs = document.getElementById('bulkInputs');
const countEl = document.getElementById('selectedCount');
document.getElementById('checkAll').addEventListener('change', function() {
    checks.forEach(c => c.checked = this.checked);
    updateBar();
});
checks.forEach(c => c.addEventListener('change', updateBar));
function updateBar() {
    const sel = [...checks].filter(c => c.checked);
    bulkInputs.innerHTML = sel.map(c => `<input type="hidden" name="ids[]" value="${c.value}">`).join('');
    countEl.textContent = sel.length;
    bulkBar.classList.toggle('d-none', sel.length === 0);
    bulkBar.classList.toggle('d-flex', sel.length > 0);
}

// Branch & Location Filtering for Search
const branchFilter = document.getElementById('branchFilter');
const locationFilter = document.getElementById('locationFilter');
const locationOptions = [...locationFilter.options];

function filterLocations() {
    const branchId = branchFilter.value;
    const currentLocId = locationFilter.value;
    
    locationFilter.innerHTML = '';
    
    const filtered = locationOptions.filter(opt => {
        return opt.value === '' || opt.dataset.branch === branchId || branchId === '';
    });
    
    filtered.forEach(opt => locationFilter.appendChild(opt));
    
    if (filtered.some(opt => opt.value === currentLocId)) {
        locationFilter.value = currentLocId;
    } else if (branchId !== '') {
        locationFilter.value = '';
    }
}

branchFilter.addEventListener('change', filterLocations);
window.addEventListener('DOMContentLoaded', filterLocations);
</script>
@endpush
