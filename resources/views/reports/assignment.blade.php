@extends('layouts.app')
@section('title', 'Laporan Penugasan Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Laporan Riwayat Penugasan</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.assignment', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
        <a href="{{ route('reports.assignment', array_merge(request()->all(), ['format'=>'excel'])) }}" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Aset</th>
                        <th>Ditugaskan Ke</th>
                        <th>Departemen</th>
                        <th>Oleh</th>
                        <th>Mulai</th>
                        <th>Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assignments as $i => $a)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>
                        <div class="small fw-500">{{ $a->asset?->name }}</div>
                        <div class="text-muted" style="font-size:.7rem;"><code>{{ $a->asset?->asset_code }}</code></div>
                    </td>
                    <td class="small fw-500">{{ $a->assigned_to }}</td>
                    <td class="small">{{ $a->department ?? '-' }}</td>
                    <td class="small">{{ $a->assignedBy?->name ?? '-' }}</td>
                    <td class="small">{{ $a->assigned_at->format('d/m/Y') }}</td>
                    <td class="small">{{ $a->returned_at?->format('d/m/Y') ?? '—' }}</td>
                    <td><span class="badge {{ $a->status=='Aktif'?'badge-aktif':'badge-dihapus' }}">{{ $a->status }}</span></td>
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
