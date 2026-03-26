@extends('layouts.app')
@section('title', 'Laporan & Audit')
@section('content')
<div class="mb-4">
    <h5 class="fw-700 mb-1">Laporan & Audit</h5>
    <p class="text-muted small mb-0">Pilih jenis laporan yang ingin dilihat atau diekspor</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #6366f1 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#ede9fe;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pc-display text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-600">Inventaris Hardware</div>
                        <div class="text-muted small">Daftar lengkap aset hardware</div>
                    </div>
                </div>
                <a href="{{ route('reports.inventory') }}" class="btn btn-outline-primary btn-sm me-2">Lihat</a>
                <a href="{{ route('reports.inventory', ['format'=>'pdf']) }}" class="btn btn-primary btn-sm me-2"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
                <a href="{{ route('reports.inventory', ['format'=>'excel']) }}" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i>Excel</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #06b6d4 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#cffafe;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-disc text-info fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-600">Inventaris Software</div>
                        <div class="text-muted small">Lisensi & status software</div>
                    </div>
                </div>
                <a href="{{ route('reports.software') }}" class="btn btn-outline-info btn-sm me-2">Lihat</a>
                <a href="{{ route('reports.software', ['format'=>'pdf']) }}" class="btn btn-info text-white btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #10b981 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#dcfce7;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-check text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-600">Riwayat Penugasan</div>
                        <div class="text-muted small">Peminjaman & penugasan aset</div>
                    </div>
                </div>
                <a href="{{ route('reports.assignment') }}" class="btn btn-outline-success btn-sm me-2">Lihat</a>
                <a href="{{ route('reports.assignment', ['format'=>'pdf']) }}" class="btn btn-success btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #f59e0b !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-tools text-warning fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-600">Laporan Pemeliharaan</div>
                        <div class="text-muted small">Schedule & biaya maintenance</div>
                    </div>
                </div>
                <a href="{{ route('reports.maintenance') }}" class="btn btn-outline-warning btn-sm me-2">Lihat</a>
                <a href="{{ route('reports.maintenance', ['format'=>'pdf']) }}" class="btn btn-warning btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #8b5cf6 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#f5f3ff;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-geo text-purple fs-4" style="color:#8b5cf6;"></i>
                    </div>
                    <div>
                        <div class="fw-600">Distribusi per Cabang</div>
                        <div class="text-muted small">Ringkasan aset per lokasi & cabang</div>
                    </div>
                </div>
                <a href="{{ route('reports.distribution') }}" class="btn btn-outline-purple btn-sm me-2" style="color:#8b5cf6;border-color:#8b5cf6;">Lihat</a>
                <a href="{{ route('reports.distribution', ['format'=>'pdf']) }}" class="btn btn-purple btn-sm text-white" style="background-color:#8b5cf6;"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0" style="border-left: 4px solid #ef4444 !important;">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px;height:48px;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-shield-check text-danger fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-600">Log Audit</div>
                        <div class="text-muted small">Trail setiap perubahan sistem</div>
                    </div>
                </div>
                <a href="{{ route('reports.audit') }}" class="btn btn-outline-danger btn-sm me-2">Lihat</a>
                <a href="{{ route('reports.audit', ['format'=>'pdf']) }}" class="btn btn-danger btn-sm"><i class="bi bi-filetype-pdf me-1"></i>PDF</a>
            </div>
        </div>
    </div>
</div>
@endsection
