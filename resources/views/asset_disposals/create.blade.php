@extends('layouts.app')
@section('title', 'Eksekusi Pemusnahan Aset')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Pemusnahan Aset (Batch)</h5>
        <p class="text-muted small mb-0">Hapus aset dari inventaris aktif secara permanen dalam kelompok</p>
    </div>
    <a href="{{ route('asset-disposals.index') }}" class="btn btn-light btn-sm">Batal</a>
</div>

<form action="{{ route('asset-disposals.store') }}" method="POST" id="disposalForm">
    @csrf
    <div class="row">
        <!-- Bagian Atas: Info Umum -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-info-circle me-2"></i>Informasi Pemusnahan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small fw-500">Tanggal Eksekusi <span class="text-danger">*</span></label>
                            <input type="date" name="disposal_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small fw-500">Tipe Pemusnahan <span class="text-danger">*</span></label>
                            <select name="disposal_type" class="form-select" required>
                                <option value="">-- Pilih Alasan / Metode --</option>
                                <option value="sold">Dijual (Sold)</option>
                                <option value="donated">Didonasikan (Donated)</option>
                                <option value="trashed">Dibuang / Rusak Total (Trashed)</option>
                                <option value="lost">Hilang / Dicuri (Lost)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small fw-500">Catatan / Keterangan</label>
                            <textarea name="notes" rows="1" class="form-control" placeholder="Berita acara singkat..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Bawah: Daftar Barang -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="bi bi-list-ul me-2"></i>Daftar Aset yang Dimusnahkan</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assetModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Aset
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="selectedAssetsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Merek / Model</th>
                                    <th>Status Saat Ini</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selectedAssetsBody">
                                <!-- Assets will be added here via JS -->
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        Belum ada aset yang dipilih. Klik tombol <strong>Tambah Aset</strong> di atas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger px-4 fw-bold" id="btnSubmit" disabled>
                        <i class="bi bi-trash me-2"></i>Eksekusi Pemusnahan <span id="selectedCountBadge" class="badge bg-white text-danger ms-2">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Container for hidden inputs -->
    <div id="hiddenInputsContainer"></div>
</form>

<!-- Modal Pemilihan Aset -->
<div class="modal fade" id="assetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title fw-bold"><i class="bi bi-search me-2"></i>Pilih Aset untuk Dimusnahkan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-light border-bottom">
                    <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Hanya menampilkan aset dengan status selain 'Disposed'. Silakan gunakan kotak pencarian untuk mencari kode atau nama aset.</small>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-hover datatable w-100" id="assetsLookupTable">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkAllAssets">
                                    </div>
                                </th>
                                <th>Kode Aset</th>
                                <th>Nama Aset</th>
                                <th>Merek</th>
                                <th>Status</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assets as $asset)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input asset-checkbox" type="checkbox" 
                                               data-id="{{ $asset->id }}" 
                                               data-code="{{ $asset->asset_code }}"
                                               data-name="{{ $asset->name }}"
                                               data-brand="{{ $asset->brand }}"
                                               data-status="{{ $asset->status }}">
                                    </div>
                                </td>
                                <td><code class="fw-bold">{{ $asset->asset_code }}</code></td>
                                <td class="fw-500">{{ $asset->name }}</td>
                                <td>{{ $asset->brand }}</td>
                                <td><span class="badge {{ $asset->status == 'Tersedia' ? 'badge-tersedia' : 'badge-digunakan' }} small">{{ $asset->status }}</span></td>
                                <td>{{ $asset->condition }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnAddToBatch">
                    <i class="bi bi-plus-circle me-1"></i>Tambahkan Terpilih (<span id="modalSelectedCount">0</span>)
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let selectedAssets = [];
        const assetModal = new bootstrap.Modal(document.getElementById('assetModal'));
        
        // Modal Selection Count
        function updateModalCount() {
            const count = $('.asset-checkbox:checked').length;
            $('#modalSelectedCount').text(count);
        }

        $(document).on('change', '.asset-checkbox', updateModalCount);
        $('#checkAllAssets').change(function() {
            $('.asset-checkbox').prop('checked', this.checked);
            updateModalCount();
        });

        // Add to Batch List
        $('#btnAddToBatch').click(function() {
            $('.asset-checkbox:checked').each(function() {
                const asset = {
                    id: $(this).data('id'),
                    code: $(this).data('code'),
                    name: $(this).data('name'),
                    brand: $(this).data('brand'),
                    status: $(this).data('status')
                };

                if (!selectedAssets.find(a => a.id === asset.id)) {
                    selectedAssets.push(asset);
                }
            });

            renderSelectedAssets();
            assetModal.hide();
        });

        // Sync Modal State when opened
        $('#assetModal').on('show.bs.modal', function() {
            $('.asset-checkbox').each(function() {
                const id = $(this).data('id');
                const isSelected = selectedAssets.find(a => a.id === id);
                
                if (isSelected) {
                    $(this).prop('checked', false).prop('disabled', true);
                    $(this).closest('tr').addClass('table-light opacity-75');
                    if (!$(this).next('.selected-badge').length) {
                        $(this).after('<span class="selected-badge badge bg-success-subtle text-success border border-success-subtle ms-2" style="font-size: 0.65rem;">Terpilih</span>');
                    }
                } else {
                    $(this).prop('disabled', false);
                    $(this).closest('tr').removeClass('table-light opacity-75');
                    $(this).next('.selected-badge').remove();
                }
            });
            updateModalCount();
            $('#checkAllAssets').prop('checked', false);
        });

        // Render Table
        function renderSelectedAssets() {
            const tbody = $('#selectedAssetsBody');
            const hiddenContainer = $('#hiddenInputsContainer');
            
            if (selectedAssets.length === 0) {
                tbody.html(`
                    <tr id="emptyRow">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada aset yang dipilih. Klik tombol <strong>Tambah Aset</strong> di atas.
                        </td>
                    </tr>
                `);
                $('#btnSubmit').prop('disabled', true);
            } else {
                tbody.empty();
                hiddenContainer.empty();
                
                selectedAssets.forEach((asset, index) => {
                    tbody.append(`
                        <tr>
                            <td class="ps-4"><code class="fw-bold">${asset.code}</code></td>
                            <td class="fw-500">${asset.name}</td>
                            <td class="small">${asset.brand}</td>
                            <td><span class="badge bg-light text-dark small">${asset.status}</span></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-outline-danger btn-sm border-0 remove-asset" data-index="${index}">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    hiddenContainer.append(`<input type="hidden" name="asset_ids[]" value="${asset.id}">`);
                });
                
                $('#btnSubmit').prop('disabled', false);
            }

            $('#selectedCountBadge').text(selectedAssets.length);
        }

        // Remove Asset
        $(document).on('click', '.remove-asset', function() {
            const index = $(this).data('index');
            selectedAssets.splice(index, 1);
            renderSelectedAssets();
        });

        // Form Validation Before Submit
        $('#disposalForm').submit(function(e) {
            if (selectedAssets.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Daftar Kosong',
                    text: 'Harap pilih minimal satu aset untuk dimusnahkan.'
                });
            }
        });
    });
</script>
@endpush
