@extends('layouts.app')
@section('title', 'Laporan Inventaris Hardware')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Laporan Inventaris Hardware</h5>
        <p class="text-muted small mb-0">Total: <strong>{{ count($assets) }}</strong> aset | Nilai: <strong>Rp {{ number_format($totalValue,0,',','.') }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.inventory', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-filetype-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('reports.inventory', array_merge(request()->all(), ['format'=>'excel'])) }}" class="btn btn-success btn-sm">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-printer"></i></button>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row gx-1 gy-2">
            <div class="col-md-2">
                <select name="branch_id" class="form-select form-select-sm">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $b)
                    <option value="{{ $b->id }}" {{ request('branch_id')==$b->id?'selected':'' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="location_id" class="form-select form-select-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id')==$loc->id?'selected':'' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    @foreach(['Tersedia','Digunakan','Dipinjam','Dalam Perbaikan'] as $s)
                    <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="Dari Tanggal">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="Sampai Tanggal">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 table-sm datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode Aset</th>
                        <th>Nama Aset</th>
                        <th>Merek/Model</th>
                        <th>Kategori</th>
                        <th>Cabang</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Tgl Beli</th>
                        <th class="text-end">Harga (Rp)</th>
                        <th>Garansi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($assets as $i => $a)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td><code class="small">{{ $a->asset_code }}</code></td>
                    <td class="fw-500 small">{{ $a->name }}</td>
                    <td class="small text-muted">{{ $a->brand }} {{ $a->model }}</td>
                    <td class="small">{{ $a->category?->name ?? '-' }}</td>
                    <td class="small text-muted" style="font-size: .7rem;">{{ $a->location?->branch?->name ?? '-' }}</td>
                    <td class="small">{{ $a->location?->name ?? '-' }}</td>
                    <td><span class="badge {{ $a->condition=='Baik'?'badge-tersedia':'badge-perbaikan' }} small">{{ $a->condition }}</span></td>
                    <td><span class="badge {{ $a->status=='Tersedia'?'badge-tersedia':($a->status=='Digunakan'?'badge-digunakan':($a->status=='Dihapus'?'badge-dihapus':'badge-perbaikan')) }} small">{{ $a->status }}</span></td>
                    <td class="small">{{ $a->purchase_date?->format('d/m/Y') ?? '-' }}</td>
                    <td class="text-end small">{{ $a->purchase_price ? number_format($a->purchase_price,0,',','.') : '-' }}</td>
                    <td class="small {{ $a->isWarrantyExpired()?'text-danger':'' }}">{{ $a->warranty_expiry?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-light fw-600">
                        <td colspan="9" class="text-end small">TOTAL NILAI:</td>
                        <td class="text-end text-primary">{{ number_format($totalValue,0,',','.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
