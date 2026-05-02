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

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-500">Pilih Aset <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-2 mb-2" id="selected-assets-display">
                            <div class="text-muted small py-2" id="no-assets-selected">Belum ada aset terpilih.</div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assetModal">
                            <i class="bi bi-plus-square me-1"></i>Pilih Aset dari Tabel
                        </button>
                        <div id="hidden-asset-inputs"></div>
                        @error('asset_ids') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-500">Alasan / Catatan</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Tuliskan keterangan durasi pinjam atau catatan lainnya...">{{ old('notes') }}</textarea>
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

<!-- Modal Pilih Aset -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetModalLabel">Pilih Aset Tersedia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-light border-bottom">
                    <div class="small text-muted">Aset yang muncul hanya yang berstatus <strong>Tersedia</strong> atau aktif di sistem.</div>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle datatable-modal w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40px;">Pilih</th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Merk</th>
                                <th>Cabang</th>
                                <th>Status</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                                <tr class="{{ $asset->status == 'Dipinjam' ? 'bg-light opacity-75' : 'selectable-row' }}">
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input asset-checkbox" type="checkbox" value="{{ $asset->id }}" 
                                                data-name="{{ $asset->name }}" data-code="{{ $asset->asset_code }}" id="asset-chk-{{ $asset->id }}"
                                                {{ $asset->status == 'Dipinjam' ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $asset->asset_code }}</span></td>
                                    <td class="fw-500 text-dark">{{ $asset->name }}</td>
                                    <td class="small">{{ $asset->brand ?? '-' }}</td>
                                    <td class="small text-muted">{{ $asset->location?->branch?->name ?? '-' }}</td>
                                    <td>
                                        @if($asset->status == 'Dipinjam')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">SEDANG DIPINJAM</span>
                                        @else
                                            <span class="badge bg-success" style="font-size: 0.65rem;">TERSEDIA</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $asset->category?->name ?? 'Tanpa Kategori' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-confirm-selection" data-bs-dismiss="modal">Gunakan Aset Terpilih</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .asset-badge {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        font-weight: 500;
    }
    .remove-asset {
        margin-left: 8px;
        cursor: pointer;
        color: #94a3b8;
        transition: color 0.2s;
    }
    .remove-asset:hover {
        color: #ef4444;
    }
    .datatable-modal tbody tr {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script>
$(function() {
    // Initialize DataTable on Modal
    const selectionTable = $('.datatable-modal').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        pageLength: 10,
        lengthMenu: [5, 10, 25],
        columnDefs: [{ orderable: false, targets: 0 }]
    });

    let selectedAssets = [];

    // Row Click Selection
    $('.datatable-modal tbody').on('click', 'tr.selectable-row', function(e) {
        if ($(e.target).is('.asset-checkbox')) return;
        const chk = $(this).find('.asset-checkbox');
        if (!chk.prop('disabled')) {
            chk.prop('checked', !chk.prop('checked')).trigger('change');
        }
    });

    // Checkbox Change
    $(document).on('change', '.asset-checkbox', function() {
        const id = $(this).val();
        const name = $(this).data('name');
        const code = $(this).data('code');

        if (this.checked) {
            if (!selectedAssets.find(a => a.id == id)) {
                selectedAssets.push({ id, name, code });
            }
        } else {
            selectedAssets = selectedAssets.filter(a => a.id != id);
        }
    });

    // Handle Confirmation
    $('#btn-confirm-selection').on('click', function() {
        renderSelectedAssets();
    });

    function renderSelectedAssets() {
        const display = $('#selected-assets-display');
        const inputs = $('#hidden-asset-inputs');
        
        display.empty();
        inputs.empty();

        if (selectedAssets.length === 0) {
            display.append('<div class="text-muted small py-2" id="no-assets-selected">Belum ada aset terpilih.</div>');
            return;
        }

        selectedAssets.forEach(asset => {
            display.append(`
                <div class="asset-badge">
                    <span>${asset.code} - ${asset.name}</span>
                    <i class="bi bi-x-circle-fill remove-asset" onclick="removeAsset('${asset.id}')"></i>
                </div>
            `);
            inputs.append(`<input type="hidden" name="asset_ids[]" value="${asset.id}">`);
        });
    }

    // Accessible globally for the badge X button
    window.removeAsset = function(id) {
        selectedAssets = selectedAssets.filter(a => a.id != id);
        // Uncheck in modal if exists
        $(`#asset-chk-${id}`).prop('checked', false);
        renderSelectedAssets();
    }
});
</script>
@endpush

