@extends('layouts.app')
@section('title', 'Ajukan Pengadaan Barang')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-600">Form Pengadaan Barang Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('purchase-requests.store') }}" method="POST">
                    @csrf

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
                                <tr>
                                    <td><input type="text" name="items[0][item_name]" class="form-control form-control-sm" required></td>
                                    <td><input type="text" name="items[0][specification]" class="form-control form-control-sm"></td>
                                    <td><input type="text" name="items[0][brand]" class="form-control form-control-sm"></td>
                                    <td><input type="number" name="items[0][qty]" class="form-control form-control-sm qty-input" value="1" min="1" required></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" name="items[0][price]" class="form-control price-input" value="0" required>
                                        </div>
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-btn disabled"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Barang Baru
                        </button>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-500">Tujuan / Alasan Pengadaan</label>
                        <textarea name="notes" rows="3" class="form-control" placeholder="Jelaskan untuk keperluan apa barang-barang ini diadakan..."></textarea>
                    </div>
                    
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('purchase-requests.index') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let itemIdx = 1;
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
    });

    // Rupiah Formatter Logic
    document.getElementById('itemsBody').addEventListener('input', function(e) {
        if (e.target.classList.contains('price-input')) {
            // Simpan posisi kursor
            let cursorPosition = e.target.selectionStart;
            let originalLength = e.target.value.length;
            
            // Bersihkan selain angka
            let val = e.target.value.replace(/[^0-9]/g, '');
            if (val === '') {
                e.target.value = '';
                return;
            }
            
            // Format ke Rupiah (Titik tiap 3 angka)
            let formatted = '';
            let valRev = val.split('').reverse().join('');
            for (let i = 0; i < valRev.length; i++) {
                if (i > 0 && i % 3 === 0) {
                    formatted += '.';
                }
                formatted += valRev[i];
            }
            formatted = formatted.split('').reverse().join('');
            
            e.target.value = formatted;
            
            // Kembalikan posisi kursor
            let newLength = formatted.length;
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
        }
    });
</script>
@endpush
@endsection
