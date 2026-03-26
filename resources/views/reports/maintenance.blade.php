@extends('layouts.app')
@section('title', 'Laporan Pemeliharaan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-0">Laporan Pemeliharaan</h5>
        <p class="text-muted small mb-0">Total biaya: <strong>Rp {{ number_format($totalCost,0,',','.') }}</strong></p>
    </div>
    <a href="{{ route('reports.maintenance', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aset</th>
                        <th>Tipe</th>
                        <th>Teknisi</th>
                        <th>Jadwal</th>
                        <th>Selesai</th>
                        <th class="text-end">Biaya (Rp)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($maintenances as $i => $m)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="small fw-500">{{ $m->asset?->name }}</div>
                        <div class="text-muted" style="font-size:.7rem;"><code>{{ $m->asset?->asset_code }}</code></div>
                    </td>
                    <td class="small">{{ $m->type }}</td>
                    <td class="small">{{ $m->technician ?? '-' }}</td>
                    <td class="small">{{ $m->scheduled_at?->format('d/m/Y') ?? '-' }}</td>
                    <td class="small">{{ $m->completed_at?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small text-end">{{ $m->cost ? number_format($m->cost,0,',','.') : '-' }}</td>
                    <td><span class="badge {{ $m->status=='Selesai'?'badge-aktif':($m->status=='Dijadwalkan'?'badge-digunakan':'badge-perbaikan') }}">{{ $m->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
