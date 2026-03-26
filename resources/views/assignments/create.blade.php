@extends('layouts.app')
@section('title', 'Tugaskan Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Tugaskan Aset</h5>
</div>
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card">
    <div class="card-header"><h6 class="mb-0">Form Penugasan Aset</h6></div>
    <div class="card-body">
        <form action="{{ route('assignments.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-500 small">Aset <span class="text-danger">*</span></label>
            <select name="asset_id" class="form-select" required>
                <option value="">— Pilih Aset —</option>
                @foreach($assets as $asset)
                <option value="{{ $asset->id }}" {{ request('asset_id') == $asset->id ? 'selected' : '' }}>
                    [{{ $asset->asset_code }}] {{ $asset->name }} — {{ $asset->status }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-500 small">Ditugaskan Ke <span class="text-danger">*</span></label>
            <input type="text" name="assigned_to" class="form-control" value="{{ old('assigned_to') }}" placeholder="Nama pengguna/karyawan" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-500 small">Departemen / Bagian</label>
            <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="Keuangan, IT, HRD...">
        </div>
        <div class="mb-3">
            <label class="form-label fw-500 small">Tanggal Penugasan <span class="text-danger">*</span></label>
            <input type="date" name="assigned_at" class="form-control" value="{{ old('assigned_at', now()->format('Y-m-d')) }}" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-500 small">Catatan</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-person-check me-2"></i>Tugaskan Aset</button>
            <a href="{{ route('assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
