@extends('layouts.app')
@section('title', 'Dashboard')
@push('styles')
<style>
    .stat-card-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); }
    .stat-card-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-card-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .stat-card-danger  { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    .stat-card-info    { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .stat-card-dark    { background: linear-gradient(135deg, #475569 0%, #334155 100%); }
</style>
@endpush
@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-primary">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['total_hardware'] }}</div>
                    <div class="stat-label">Total Hardware</div>
                </div>
                <i class="bi bi-pc-display stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['total_software'] }}</div>
                    <div class="stat-label">Lisensi Software</div>
                </div>
                <i class="bi bi-disc stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-success">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['digunakan'] }}</div>
                    <div class="stat-label">Aset Digunakan</div>
                </div>
                <i class="bi bi-check-circle stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-danger">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['dalam_perbaikan'] }}</div>
                    <div class="stat-label">Dalam Perbaikan</div>
                </div>
                <i class="bi bi-tools stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-warning">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['sw_expired'] }}</div>
                    <div class="stat-label">Lisensi Expired</div>
                </div>
                <i class="bi bi-exclamation-triangle stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card stat-card-dark">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ $stats['tersedia'] }}</div>
                    <div class="stat-label">Aset Tersedia</div>
                </div>
                <i class="bi bi-archive stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card h-100 border-0" style="background:linear-gradient(135deg,#1e293b,#334155);color:#fff;">
            <div class="card-body d-flex align-items-center gap-3">
                <i class="bi bi-currency-dollar" style="font-size:2.5rem;opacity:.7;"></i>
                <div>
                    <div style="font-size:1.6rem;font-weight:700;">Rp {{ number_format($stats['total_nilai'],0,',','.') }}</div>
                    <div style="font-size:.8rem;opacity:.8;">Total Nilai Inventaris Hardware</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4">
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600">Aset per Kategori</h6>
            </div>
            <div class="card-body" style="max-height:280px;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-600">Distribusi Status</h6>
            </div>
            <div class="card-body" style="max-height:280px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-600">Aset per Cabang & Lokasi</h6>
            </div>
            <div class="card-body p-0" style="overflow-y:auto;max-height:260px;">
                @foreach($assetsByLocation as $item)
                <div class="px-3 py-2 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <div style="font-size: .65rem; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                {{ $item->branch_name ?? 'Tanpa Cabang' }}
                            </div>
                            <div class="small text-truncate fw-500 text-dark">{{ $item->name }}</div>
                        </div>
                        <span class="badge bg-primary rounded-pill ms-2">{{ $item->total }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- BOTTOM ROW -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600"><i class="bi bi-clock-history me-2 text-primary"></i>Aset Terbaru</h6>
                <a href="{{ route('assets.index') }}" class="btn btn-xs btn-outline-primary btn-sm py-0 px-2">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($recentAssets as $asset)
                <a href="{{ route('assets.show', $asset) }}" class="text-decoration-none">
                    <div class="d-flex align-items-center px-3 py-2 border-bottom hover-bg">
                        <div class="me-3">
                            <div style="width:36px;height:36px;border-radius:8px;background:#ede9fe;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-pc-display text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="small fw-500 text-dark text-truncate" style="max-width:160px;">{{ $asset->name }}</div>
                            <div class="text-muted" style="font-size:.72rem;">{{ $asset->asset_code }} • {{ $asset->category?->name }}</div>
                        </div>
                        <span class="badge" style="font-size:.65rem;background:{{ $asset->status == 'Digunakan' ? '#dbeafe' : ($asset->status == 'Tersedia' ? '#dcfce7' : '#fee2e2') }};color:{{ $asset->status == 'Digunakan' ? '#1d4ed8' : ($asset->status == 'Tersedia' ? '#16a34a' : '#dc2626') }}">{{ $asset->status }}</span>
                    </div>
                </a>
                @empty
                <div class="text-center text-muted py-4 small">Belum ada aset</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600"><i class="bi bi-tools me-2 text-warning"></i>Jadwal Pemeliharaan</h6>
                <a href="{{ route('maintenance.index') }}" class="btn btn-sm btn-outline-warning py-0 px-2">Lihat</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingMaintenance as $m)
                <div class="px-3 py-2 border-bottom">
                    <div class="small fw-500 text-dark">{{ $m->asset?->name }}</div>
                    <div class="text-muted d-flex justify-content-between" style="font-size:.72rem;">
                        <span>{{ $m->type }}</span>
                        <span class="{{ $m->scheduled_at && $m->scheduled_at->isPast() ? 'text-danger' : 'text-success' }}">
                            {{ $m->scheduled_at?->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Tidak ada jadwal</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600"><i class="bi bi-shield-exclamation me-2 text-danger"></i>Garansi Hampir Habis</h6>
            </div>
            <div class="card-body p-0">
                @forelse($expiringWarranty as $asset)
                <div class="px-3 py-2 border-bottom">
                    <div class="small fw-500 text-dark text-truncate">{{ $asset->name }}</div>
                    <div class="text-muted d-flex justify-content-between" style="font-size:.72rem;">
                        <span>{{ $asset->asset_code }}</span>
                        <span class="text-warning fw-500">{{ $asset->warranty_expiry?->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Tidak ada garansi hampir habis</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const categoryData = @json($assetsByCategory);
const statusData = @json($assetsByStatus);
const colors = ['#6366f1','#10b981','#f59e0b','#ef4444','#06b6d4','#8b5cf6','#ec4899','#f97316','#14b8a6','#a3e635'];

// Category Doughnut
new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: categoryData.map(d => d.name),
        datasets: [{ data: categoryData.map(d => d.total), backgroundColor: colors, borderWidth: 2 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
});

// Status Bar
const statusColors = {
    'Tersedia': '#10b981', 'Digunakan': '#6366f1',
    'Dipinjam': '#f59e0b', 'Dalam Perbaikan': '#ef4444', 'Dihapus': '#94a3b8'
};
new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: statusData.map(d => d.status),
        datasets: [{ data: statusData.map(d => d.total), backgroundColor: statusData.map(d => statusColors[d.status] || '#6366f1'), borderWidth: 2 }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    }
});
</script>
@endpush
