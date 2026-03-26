@extends('layouts.app')
@section('title', 'Penugasan Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Penugasan Aset</h5>
        <p class="text-muted small mb-0">Riwayat penugasan dan peminjaman aset</p>
    </div>
    <a href="{{ route('assignments.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Tugaskan Aset
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Aset</th>
                        <th>Ditugaskan Ke</th>
                        <th>Departemen</th>
                        <th>Ditugaskan Oleh</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Kembali</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($assignments as $a)
                <tr>
                    <td>
                        <div class="small fw-500">{{ $a->asset?->name }}</div>
                        <div class="text-muted" style="font-size:.72rem;"><code>{{ $a->asset?->asset_code }}</code></div>
                    </td>
                    <td class="fw-500 small">{{ $a->assigned_to }}</td>
                    <td class="small">{{ $a->department ?? '-' }}</td>
                    <td class="small">{{ $a->assignedBy?->name ?? '-' }}</td>
                    <td class="small">{{ $a->assigned_at->format('d/m/Y') }}</td>
                    <td class="small">{{ $a->returned_at?->format('d/m/Y') ?? '—' }}</td>
                    <td><span class="badge {{ $a->status == 'Aktif' ? 'badge-aktif' : 'badge-dihapus' }}">{{ $a->status }}</span></td>
                    <td>
                        @if($a->status == 'Aktif')
                        <form method="POST" action="{{ route('assignments.destroy', $a) }}" onsubmit="deleteConfirm(event)">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-success py-0 px-2"><i class="bi bi-arrow-return-left me-1"></i>Kembalikan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
