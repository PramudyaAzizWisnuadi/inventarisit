@extends('layouts.app')
@section('title', 'Jadwalkan Pemeliharaan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Jadwalkan Pemeliharaan</h5>
</div>
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
    <div class="card-header"><h6 class="mb-0">Form Pemeliharaan</h6></div>
    <div class="card-body">
        <form action="{{ route('maintenance.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-500 small">Aset <span class="text-danger">*</span></label>
                <select name="asset_id" class="form-select" required>
                    <option value="">— Pilih Aset —</option>
                    @foreach($assets as $asset)
                    <option value="{{ $asset->id }}" {{ request('asset_id')==$asset->id?'selected':'' }}>
                        [{{ $asset->asset_code }}] {{ $asset->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Tipe Pemeliharaan <span class="text-danger">*</span></label>
                <select name="type" class="form-select" required>
                    @foreach(['Preventif','Korektif','Penggantian Komponen','Upgrade','Kalibrasi'] as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Teknisi</label>
                <input type="text" name="technician" class="form-control" value="{{ old('technician') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Vendor Service</label>
                <input type="text" name="vendor_service" class="form-control" value="{{ old('vendor_service') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Tanggal Jadwal</label>
                <input type="date" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Estimasi Biaya (Rp)</label>
                <input type="number" name="cost" class="form-control" value="{{ old('cost') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-500 small">Status</label>
                <select name="status" class="form-select">
                    @foreach(['Dijadwalkan','Dalam Proses'] as $s)
                    <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-500 small">Deskripsi Masalah</label>
                <textarea name="problem_description" class="form-control" rows="2">{{ old('problem_description') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-500 small">Catatan</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Simpan</button>
                <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
