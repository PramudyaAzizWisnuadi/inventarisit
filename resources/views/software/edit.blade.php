@extends('layouts.app')
@section('title', 'Edit Lisensi: '.$software->software_name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-700 mb-0">Edit Lisensi Software</h5>
</div>
<form action="{{ route('software.update', $software) }}" method="POST">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Detail Software</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-500 small">Nama Software <span class="text-danger">*</span></label>
                        <input type="text" name="software_name" class="form-control" value="{{ old('software_name', $software->software_name) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-500 small">Versi</label>
                        <input type="text" name="version" class="form-control" value="{{ old('version', $software->version) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Publisher</label>
                        <input type="text" name="publisher" class="form-control" value="{{ old('publisher', $software->publisher) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Tipe Lisensi <span class="text-danger">*</span></label>
                        <select name="license_type" class="form-select" required>
                            @foreach(['Per-Seat','Volume','OEM','Freeware','Open Source','Subscription'] as $t)
                            <option value="{{ $t }}" {{ old('license_type', $software->license_type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-500 small">License Key</label>
                        <input type="text" name="license_key" class="form-control font-monospace" value="{{ old('license_key', $software->license_key) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $software->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">— Pilih —</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id', $software->vendor_id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-500 small">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $software->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Pembelian & Penggunaan</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-500 small">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $software->purchase_date?->format('Y-m-d')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Harga (Rp)</label>
                    <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $software->purchase_price) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Expired Per Tanggal</label>
                    <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $software->expiry_date?->format('Y-m-d')) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Maks Pengguna</label>
                    <input type="number" name="max_users" class="form-control" value="{{ old('max_users', $software->max_users) }}" min="1">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Pengguna Terpakai</label>
                    <input type="number" name="used_users" class="form-control" value="{{ old('used_users', $software->used_users) }}" min="0">
                </div>
                <div>
                    <label class="form-label fw-500 small">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['Aktif','Tidak Aktif','Expired'] as $s)
                        <option value="{{ $s }}" {{ old('status', $software->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Simpan Perubahan</button>
            <a href="{{ route('software.show', $software) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection
