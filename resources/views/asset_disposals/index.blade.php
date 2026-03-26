@extends('layouts.app')
@section('title', 'Riwayat Pemusnahan Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Pemusnahan Aset (Disposal)</h5>
    <a href="{{ route('asset-disposals.create') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-trash me-1"></i>Eksekusi Pemusnahan Aset
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Tanggal</th>
                    <th>Kode Aset</th>
                    <th>Nama Aset</th>
                    <th>Tipe Pemusnahan</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($disposals as $disp)
                <tr>
                    <td class="ps-3">{{ \Carbon\Carbon::parse($disp->disposal_date)->format('d M Y') }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $disp->asset->asset_code }}</span></td>
                    <td class="fw-500">{{ $disp->asset->name }}</td>
                    <td>
                        <span class="badge {{ $disp->disposal_type == 'sold' ? 'bg-success' : ($disp->disposal_type == 'donated' ? 'bg-primary' : ($disp->disposal_type == 'trashed' ? 'bg-secondary' : 'bg-danger')) }}">
                            {{ strtoupper($disp->disposal_type) }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $disp->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
