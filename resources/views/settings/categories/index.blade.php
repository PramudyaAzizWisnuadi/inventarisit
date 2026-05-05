@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('content')
<div class="mb-4">
    <h5 class="fw-700 mb-1">Kelola Kategori</h5>
    <p class="text-muted small mb-0">Kategori untuk hardware dan software</p>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Tambah Kategori</h6></div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-500 small">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Tipe</label>
                    <select name="type" class="form-select">
                        <option value="hardware">Hardware</option>
                        <option value="software">Software</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Icon Bootstrap (bi-...)</label>
                    <input type="text" name="icon" class="form-control" placeholder="bi-laptop" value="bi-box">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Warna Badge</label>
                    <select name="color" class="form-select">
                        @foreach(['primary','success','info','warning','danger','secondary','dark'] as $c)
                        <option value="{{ $c }}">{{ ucfirst($c) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Daftar Kategori ({{ $categories->count() }})</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0 datatable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Icons</th>
                            <th>Aset</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td>
                            <i class="{{ $cat->icon }} text-{{ $cat->color }} me-2"></i>{{ $cat->name }}
                        </td>
                        <td><span class="badge bg-{{ $cat->type=='hardware'?'primary':'info' }} bg-opacity-10 text-{{ $cat->type=='hardware'?'primary':'info' }}">{{ ucfirst($cat->type) }}</span></td>
                        <td class="small font-monospace text-muted">{{ $cat->icon }}</td>
                        <td><span class="badge bg-light text-dark">{{ $cat->assets_count + $cat->software_licenses_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCategoryModal{{ $cat->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if(($cat->assets_count + $cat->software_licenses_count) > 0)
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="Swal.fire('Tidak Dapat Dihapus', 'Kategori \'{{ $cat->name }}\' masih digunakan oleh {{ $cat->assets_count + $cat->software_licenses_count }} aset/software.', 'error')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('categories.destroy', $cat) }}" id="deleteForm{{ $cat->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="hapusKategori('{{ $cat->id }}', '{{ addslashes($cat->name) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editCategoryModal{{ $cat->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('categories.update', $cat) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Kategori</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-500 small">Nama Kategori <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-500 small">Tipe</label>
                                                    <select name="type" class="form-select">
                                                        <option value="hardware" {{ $cat->type == 'hardware' ? 'selected' : '' }}>Hardware</option>
                                                        <option value="software" {{ $cat->type == 'software' ? 'selected' : '' }}>Software</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-500 small">Icon Bootstrap (bi-...)</label>
                                                    <input type="text" name="icon" class="form-control" placeholder="bi-laptop" value="{{ $cat->icon }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-500 small">Warna Badge</label>
                                                    <select name="color" class="form-select">
                                                        @foreach(['primary','success','info','warning','danger','secondary','dark'] as $c)
                                                        <option value="{{ $c }}" {{ $cat->color == $c ? 'selected' : '' }}>{{ ucfirst($c) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hapusKategori(id, name) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: "Apakah Anda yakin ingin menghapus kategori '" + name + "'?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm' + id).submit();
        }
    });
}
</script>
@endpush
