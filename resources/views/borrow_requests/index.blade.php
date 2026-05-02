@extends('layouts.app')
@section('title', 'Daftar Peminjaman Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Peminjaman Aset</h5>
    <a href="{{ route('borrow-requests.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Buat Pengajuan Pinjam
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nomor</th>
                    <th>Peminjam</th>
                    <th>Aset Terkait</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td class="ps-3"><span class="badge bg-light text-dark border">{{ $req->request_number }}</span></td>
                    <td class="fw-500">{{ $req->borrower_name }}</td>
                    <td>
                        @foreach($req->details as $detail)
                            <div class="fw-500 small mb-1">
                                {{ $detail->asset->name }} <span class="text-muted">[{{ $detail->asset->asset_code }}]</span>
                            </div>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge {{ $req->status == 'approved' ? 'bg-success' : ($req->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                            {{ strtoupper($req->status) }}
                        </span>
                    </td>
                    <td class="small text-muted">
                        {{ \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') }}
                    </td>
                    <td class="pe-3 text-end">
                        <a href="{{ route('borrow-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
    $(function() {
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable().destroy();
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                },
                pageLength: 25,
                responsive: true,
                colReorder: true,
                order: [[4, 'desc']], // Sort by Tanggal (index 4) descending
                dom: '<"d-flex justify-content-between align-items-center"lf>rt<"d-flex justify-content-between align-items-center border-top"ip>',
            });
        }
    });
</script>
@endpush
@endsection
