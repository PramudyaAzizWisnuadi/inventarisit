@extends('layouts.app')
@section('title', 'Laporan Software & Lisensi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-700 mb-0">Laporan Software & Lisensi</h5>
    <a href="{{ route('reports.software', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>Export PDF</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0 datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Software</th>
                        <th>Publisher</th>
                        <th>Tipe</th>
                        <th>Pengguna</th>
                        <th>Berlaku Hingga</th>
                        <th>Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($licenses as $i => $sw)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><code class="small">{{ $sw->license_code }}</code></td>
                    <td class="fw-500 small">{{ $sw->software_name }} v{{ $sw->version }}</td>
                    <td class="small">{{ $sw->publisher ?? '-' }}</td>
                    <td><span class="badge bg-light text-dark small">{{ $sw->license_type }}</span></td>
                    <td class="small">{{ $sw->used_users }}/{{ $sw->max_users }}</td>
                    <td class="small {{ $sw->isExpired()?'text-danger':'' }}">{{ $sw->expiry_date?->format('d/m/Y') ?? 'Permanen' }}</td>
                    <td class="small">{{ $sw->purchase_price ? 'Rp '.number_format($sw->purchase_price,0,',','.') : '-' }}</td>
                    <td><span class="badge {{ $sw->status=='Aktif'?'badge-aktif':'badge-expired' }}">{{ $sw->status }}</span></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
