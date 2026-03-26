@extends('layouts.app')
@section('title', 'Manajemen Cabang')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Daftar Cabang</h5>
        <p class="text-muted small mb-0">Kelola cabang/kantor pusat dan wilayah</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal">
        <i class="bi bi-plus-lg me-1"></i>Tambah Cabang
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Cabang</th>
                        <th>Alamat</th>
                        <th>Manager</th>
                        <th class="text-center">Lokasi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branches as $branch)
                    <tr>
                        <td><code class="small">{{ $branch->code }}</code></td>
                        <td><div class="fw-600 small">{{ $branch->name }}</div></td>
                        <td><span class="small text-muted">{{ Str::limit($branch->address, 50) ?: '-' }}</span></td>
                        <td><span class="small">{{ $branch->manager ?? '-' }}</span></td>
                        <td class="text-center"><span class="badge bg-light text-dark border px-2">{{ $branch->locations_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editBranchModal{{ $branch->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('branches.destroy', $branch) }}" method="POST" onsubmit="deleteConfirm(event)">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-sm btn-outline-danger py-0 px-2" {{ $branch->locations_count > 0 ? 'disabled' : '' }} title="{{ $branch->locations_count > 0 ? 'Hapus lokasi terlebih dahulu' : 'Hapus' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('branches.update', $branch) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Cabang</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-500">Nama Cabang</label>
                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $branch->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-500">Kode Cabang</label>
                                                    <input type="text" name="code" class="form-control form-control-sm" value="{{ $branch->code }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-500">Alamat</label>
                                                    <textarea name="address" class="form-control form-control-sm" rows="2">{{ $branch->address }}</textarea>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label small fw-500">Manager / PIC</label>
                                                    <input type="text" name="manager" class="form-control form-control-sm" value="{{ $branch->manager }}">
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

<!-- Add Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Cabang</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-500">Nama Cabang</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Cabang Jakarta" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-500">Kode Cabang</label>
                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Contoh: JKT-01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-500">Alamat</label>
                        <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap cabang"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-500">Manager / PIC</label>
                        <input type="text" name="manager" class="form-control form-control-sm" placeholder="Nama manager cabang">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
