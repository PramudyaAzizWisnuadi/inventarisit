@extends('layouts.app')
@section('title', 'Laporan Distribusi Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Laporan Distribusi Aset per Cabang</h5>
        <p class="text-muted small mb-0">Ringkasan statistik aset berdasarkan struktur organisasi</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.distribution', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-filetype-pdf me-1"></i>PDF
        </a>
        <a href="{{ route('reports.distribution', array_merge(request()->all(), ['format'=>'excel'])) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Excel
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i></button>
    </div>
</div>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Semua Cabang (Tampilkan Semua)</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-filter me-1"></i>Terapkan Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    @foreach($distribution as $branch)
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-building text-primary"></i>
                    <h6 class="mb-0 fw-700 text-uppercase letter-spacing-1">{{ $branch->name }}</h6>
                    <span class="badge bg-secondary ms-2 small">{{ $branch->code }}</span>
                </div>
                <div class="text-muted small">
                    Total: <strong>{{ $branch->locations->sum('assets_count') }}</strong> Aset | 
                    Nilai: <strong>Rp {{ number_format($branch->locations->sum('assets_sum_purchase_price'), 0, ',', '.') }}</strong>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-light">
                                <th style="width: 40%">Lokasi</th>
                                <th class="text-center">Jumlah Aset</th>
                                <th class="text-end">Total Nilai (Rp)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($branch->locations as $loc)
                            <tr>
                                <td>
                                    <div class="fw-500">{{ $loc->name }}</div>
                                    <div class="text-muted small">{{ $loc->building }} - Lt.{{ $loc->floor }} ({{ $loc->room }})</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3">{{ $loc->assets_count }}</span>
                                </td>
                                <td class="text-end fw-600">
                                    {{ $loc->assets_sum_purchase_price ? number_format($loc->assets_sum_purchase_price, 0, ',', '.') : '0' }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('reports.inventory', ['branch_id' => $branch->id, 'location_id' => $loc->id]) }}" class="btn btn-xs btn-outline-primary py-0 px-2" style="font-size: .75rem;">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada lokasi di cabang ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
