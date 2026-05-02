@extends('layouts.app')
@section('title', 'Daftar Pengadaan Barang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Pengadaan Barang</h5>
    <div>
        <button type="button" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-arrow-up me-1"></i>Import Data
        </button>
        <a href="{{ route('purchase-requests.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Buat Pengajuan Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0 datatable">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Nomor</th>
                    <th>Pemohon</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="pe-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td class="ps-3"><span class="badge bg-light text-dark border">{{ $req->request_number }}</span></td>
                    <td>{{ $req->user->name }}</td>
                    <td>
                        @foreach($req->details as $detail)
                            <div class="fw-500 small mb-1">
                                {{ $detail->item_name }} <span class="text-muted">({{ $detail->qty }}x)</span>
                                @if($detail->specification) <br><span class="text-muted" style="font-size: 0.75rem;">Spec: {{ $detail->specification }}</span> @endif
                            </div>
                        @endforeach
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $req->details->sum('qty') }} Item</span></td>
                    <td><span class="fw-600">Rp {{ number_format($req->total_price, 0, ',', '.') }}</span></td>
                    <td>
                        <span class="badge {{ $req->status == 'completed' || $req->status == 'received' ? 'bg-success' : ($req->status == 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                            {{ strtoupper(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($req->request_date)->format('d/m/Y') }}</td>
                    <td class="pe-3 text-end">
                        <a href="{{ route('purchase-requests.show', $req) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-search"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('purchase-requests.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Pengadaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        Gunakan fitur ini untuk memigrasi data dari sistem lama. 
                        Silakan unduh template excel terlebih dahulu agar struktur kolom sesuai.
                        <br><br>
                        <a href="{{ route('purchase-requests.import-template') }}" class="btn btn-sm btn-info text-white">
                            <i class="bi bi-file-earmark-excel me-1"></i> Download Template Excel
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <label for="file_import" class="form-label fw-500">Pilih File (CSV / Excel)</label>
                        <input class="form-control" type="file" id="file_import" name="file_import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(function() {
        if ($('.datatable').length > 0) {
            // Re-initialize DataTable with specific order for this page
            $('.datatable').DataTable().destroy();
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                },
                pageLength: 25,
                responsive: true,
                colReorder: true,
                order: [[6, 'desc']], // Sort by Tanggal (index 6) descending
                dom: '<"d-flex justify-content-between align-items-center"lf>rt<"d-flex justify-content-between align-items-center border-top"ip>',
            });
        }
    });
</script>
@endpush
@endsection
