@extends('layouts.app')
@section('title', 'Ajukan Peminjaman Aset')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-600">Form Pengajuan Peminjaman Aset (Inventaris)</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('borrow-requests.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-500">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" name="borrower_name" class="form-control" placeholder="Ketik nama (bebas/free text, mis. Budi Santoso)..." value="{{ old('borrower_name') }}" required>
                        <div class="form-text">Nama di atas akan tertera pada kolom penerima di cetakan PDF BAST.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-500">Pilih Aset Spesifik <span class="text-danger">*</span></label>
                        <select name="asset_ids[]" class="form-select" multiple required style="height: 150px;">
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}">[{{ $asset->asset_code }}] {{ $asset->name }} ({{ $asset->brand }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Tekan dan tahan tombol CTRL (Windows) atau CMD (Mac) pada keyboard untuk memilih lebih dari 1 barang secara bersamaan.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-500">Alasan / Catatan</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Tuliskan keterangan durasi pinjam atau catatan lainnya..."></textarea>
                    </div>
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('borrow-requests.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
