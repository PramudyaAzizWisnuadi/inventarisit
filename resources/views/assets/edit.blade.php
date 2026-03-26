@extends('layouts.app')
@section('title', 'Edit Aset: '.$asset->name)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Edit Aset</h5>
        <nav aria-label="breadcrumb"><ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('assets.index') }}">Hardware</a></li>
            <li class="breadcrumb-item"><a href="{{ route('assets.show', $asset) }}">{{ $asset->asset_code }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol></nav>
    </div>
</div>

<form action="{{ route('assets.update', $asset) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Informasi Dasar</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-500 small">Nama Aset <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $asset->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Merek</label>
                        <input type="text" name="brand" class="form-control" value="{{ old('brand', $asset->brand) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Model</label>
                        <input type="text" name="model" class="form-control" value="{{ old('model', $asset->model) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Nomor Serial</label>
                        <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $asset->serial_number) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $asset->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-500 small">Spesifikasi</label>
                        <textarea name="specifications" class="form-control" rows="3">{{ old('specifications', $asset->specifications) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-500 small">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $asset->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Informasi Pembelian</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Vendor</label>
                        <select name="vendor_id" class="form-select">
                            <option value="">— Pilih Vendor —</option>
                            @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ old('vendor_id', $asset->vendor_id) == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Tanggal Pembelian</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Harga Pembelian (Rp)</label>
                        <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $asset->purchase_price) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-500 small">Garansi Sampai</label>
                        <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', $asset->warranty_expiry?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Status & Penempatan</h6></div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-500 small">Kondisi</label>
                    <select name="condition" class="form-select">
                        @foreach(['Baik','Kurang Baik','Rusak'] as $c)
                        <option value="{{ $c }}" {{ old('condition', $asset->condition) == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['Tersedia','Digunakan','Dipinjam','Dalam Perbaikan','Dihapus'] as $s)
                        <option value="{{ $s }}" {{ old('status', $asset->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Cabang</label>
                    <select id="branchSelect" class="form-select">
                        <option value="">— Pilih Cabang —</option>
                        @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ ($asset->location?->branch_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label fw-500 small">Lokasi</label>
                    <select name="location_id" id="locationSelect" class="form-select">
                        <option value="" data-branch="">— Pilih Lokasi —</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" data-branch="{{ $loc->branch_id }}" {{ old('location_id', $asset->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0">Foto Aset</h6></div>
            <div class="card-body">
                @if($asset->photo)
                <img src="{{ asset('storage/'.$asset->photo) }}" class="w-100 rounded mb-2" style="max-height:150px;object-fit:cover;">
                @endif
                <input type="file" name="photo" class="form-control" accept="image/*" id="photoInput">
                <img id="photoPreview" src="" class="mt-2 rounded w-100 d-none" style="max-height:150px;object-fit:cover;">
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-2"></i>Simpan Perubahan</button>
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('photoPreview');
            img.src = e.target.result;
            img.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// Branch & Location Filtering
const branchSelect = document.getElementById('branchSelect');
const locationSelect = document.getElementById('locationSelect');
const locationOptions = [...locationSelect.options];

function filterLocations() {
    const branchId = branchSelect.value;
    const currentLocId = locationSelect.value;
    
    // Clear and reset location select
    locationSelect.innerHTML = '';
    
    // Filter options
    const filtered = locationOptions.filter(opt => {
        return opt.value === '' || opt.dataset.branch === branchId || branchId === '';
    });
    
    filtered.forEach(opt => locationSelect.appendChild(opt));
    
    // Restore value if it's still in the filtered list
    if (filtered.some(opt => opt.value === currentLocId)) {
        locationSelect.value = currentLocId;
    } else if (branchId !== '') {
        locationSelect.value = '';
    }
}

branchSelect.addEventListener('change', filterLocations);
window.addEventListener('DOMContentLoaded', filterLocations);
</script>
@endpush
