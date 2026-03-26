@extends('layouts.app')
@section('title', 'Pemeliharaan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Pemeliharaan Aset</h5>
        <p class="text-muted small mb-0">Jadwal dan riwayat pemeliharaan perangkat IT</p>
    </div>
    <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Jadwalkan Pemeliharaan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Aset</th>
                        <th>Tipe</th>
                        <th>Teknisi</th>
                        <th>Jadwal</th>
                        <th>Selesai</th>
                        <th>Biaya</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($maintenances as $m)
                <tr>
                    <td>
                        <div class="small fw-500">{{ $m->asset?->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;"><code>{{ $m->asset?->asset_code }}</code></div>
                    </td>
                    <td><span class="badge bg-light text-dark small">{{ $m->type }}</span></td>
                    <td class="small">{{ $m->technician ?? '-' }}</td>
                    <td class="small {{ $m->scheduled_at && $m->scheduled_at->isPast() && $m->status == 'Dijadwalkan' ? 'text-danger fw-500' : '' }}">
                        {{ $m->scheduled_at?->format('d/m/Y') ?? '-' }}
                    </td>
                    <td class="small">{{ $m->completed_at?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">{{ $m->cost ? 'Rp '.number_format($m->cost,0,',','.') : '-' }}</td>
                    <td>
                        @php $st = $m->status; @endphp
                        <span class="badge {{ $st=='Selesai'?'badge-aktif':($st=='Dijadwalkan'?'badge-digunakan':($st=='Dalam Proses'?'badge-dipinjam':'badge-dihapus')) }}">{{ $st }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('maintenance.show', $m) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('maintenance.edit', $m) }}" class="btn btn-sm btn-outline-secondary py-0 px-2"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
