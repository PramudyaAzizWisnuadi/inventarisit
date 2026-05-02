@extends('layouts.app')
@section('title', 'Edit Realisasi Pengadaan')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="card">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-600">Edit Realisasi Pengadaan: {{ $purchase_request->request_number }}</h6>
                <span class="badge bg-primary">{{ strtoupper($purchase_request->status) }}</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('purchase-requests.update', $purchase_request) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="alert alert-info py-2 small mb-4">
                        <i class="bi bi-info-circle me-1"></i> Sesuaikan jumlah barang dan harga sesuai dengan nota pembelian asli (realisasi).
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang <span class="text-danger">*</span></th>
                                    <th>Spesifikasi</th>
                                    <th>Merk / Brand</th>
                                    <th style="width: 100px;">Qty <span class="text-danger">*</span></th>
                                    <th style="width: 200px;">Harga Satuan <span class="text-danger">*</span></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @foreach($purchase_request->details as $index => $detail)
                                <tr>
                                    <td><input type="text" name="items[{{ $index }}][item_name]" class="form-control form-control-sm" value="{{ $detail->item_name }}" required></td>
                                    <td><input type="text" name="items[{{ $index }}][specification]" class="form-control form-control-sm" value="{{ $detail->specification }}"></td>
                                    <td><input type="text" name="items[{{ $index }}][brand]" class="form-control form-control-sm" value="{{ $detail->brand }}"></td>
                                    <td><input type="number" name="items[{{ $index }}][qty]" class="form-control form-control-sm qty-input" value="{{ $detail->qty }}" min="1" required></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="items[{{ $index }}][price]" class="form-control price-input" value="{{ number_format($detail->price, 0, ',', '.') }}" required>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-btn {{ $purchase_request->details->count() <= 1 ? 'disabled' : '' }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Barang Baru
                        </button>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-7">
                            <label class="form-label text-muted small fw-500">Catatan Tambahan</label>
                            <textarea name="notes" rows="4" class="form-control" placeholder="Tambahkan keterangan realisasi jika ada...">{{ $purchase_request->notes }}</textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-muted small fw-500">Foto Bukti Nota / Approval <span class="text-muted text-xs ms-1">(Opsional)</span></label>
                            <div class="border rounded p-3 bg-light text-center">
                                @if($purchase_request->receipt_photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $purchase_request->receipt_photo) }}" class="img-thumbnail" style="max-height: 100px;">
                                        <p class="small text-muted mb-0">Foto saat ini</p>
                                    </div>
                                @endif
                                <input type="file" name="receipt_photo" class="form-control form-control-sm" accept="image/*">
                                <div class="form-text small">Upload foto nota fisik (Format: JPG, PNG, Max: 1MB)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('purchase-requests.show', $purchase_request) }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan Realisasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIdx = {{ $purchase_request->details->count() }};
    document.getElementById('addRowBtn').addEventListener('click', function() {
        const body = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="items[${itemIdx}][item_name]" class="form-control form-control-sm" required></td>
            <td><input type="text" name="items[${itemIdx}][specification]" class="form-control form-control-sm"></td>
            <td><input type="text" name="items[${itemIdx}][brand]" class="form-control form-control-sm"></td>
            <td><input type="number" name="items[${itemIdx}][qty]" class="form-control form-control-sm qty-input" value="1" min="1" required></td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[${itemIdx}][price]" class="form-control price-input" value="0" required>
                </div>
            </td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-btn"><i class="bi bi-trash"></i></button></td>
        `;
        body.appendChild(tr);
        itemIdx++;
        updateRemoveButtons();
    });

    // Rupiah Formatter Logic
    function formatRupiah(val) {
        let numeric = val.toString().replace(/[^0-9]/g, '');
        if (numeric === '') return '';
        
        // Manual formatting to be bulletproof
        let formatted = '';
        let valRev = numeric.split('').reverse().join('');
        for (let i = 0; i < valRev.length; i++) {
            if (i > 0 && i % 3 === 0) {
                formatted += '.';
            }
            formatted += valRev[i];
        }
        return formatted.split('').reverse().join('');
    }

    document.getElementById('itemsBody').addEventListener('input', function(e) {
        if (e.target.classList.contains('price-input')) {
            let cursorPosition = e.target.selectionStart;
            let originalLength = e.target.value.length;
            
            e.target.value = formatRupiah(e.target.value);
            
            let newLength = e.target.value.length;
            cursorPosition = cursorPosition + (newLength - originalLength);
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        }
    });

    // Strip dots before submit
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelectorAll('.price-input').forEach(input => {
            input.value = input.value.replace(/\./g, '');
        });
    });

    document.getElementById('itemsBody').addEventListener('click', function(e) {
        let btn = e.target.closest('.remove-btn');
        if (btn && !btn.classList.contains('disabled')) {
            btn.closest('tr').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#itemsBody tr');
        const btns = document.querySelectorAll('.remove-btn');
        if (rows.length <= 1) {
            btns.forEach(b => b.classList.add('disabled'));
        } else {
            btns.forEach(b => b.classList.remove('disabled'));
        }
    }
</script>
@endpush
@endsection
