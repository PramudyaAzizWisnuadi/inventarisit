@extends('layouts.app')
@section('title', 'Manajemen Lokasi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Daftar Lokasi</h5>
        <p class="text-muted small mb-0">Kelola detail lokasi penempatan aset per cabang</p>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLocationModal">
        <i class="bi bi-plus-lg me-1"></i>Tambah Lokasi
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Cabang</th>
                        <th>Nama Lokasi</th>
                        <th>Gedung/Lt/Ruang</th>
                        <th>PIC</th>
                        <th class="text-center">Aset</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $loc)
                    <tr>
                        <td>
                            @if($loc->branch)
                            <span class="badge bg-light text-primary border fw-500">{{ $loc->branch->name }}</span>
                            @else
                            <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td><div class="fw-600 small">{{ $loc->name }}</div></td>
                        <td>
                            <div class="small text-muted">
                                {{ $loc->building ?? '-' }} / {{ $loc->floor ?? '-' }} / {{ $loc->room ?? '-' }}
                            </div>
                        </td>
                        <td class="small">{{ $loc->pic ?? '-' }}</td>
                        <td class="text-center"><span class="badge bg-light text-dark border px-2">{{ $loc->assets_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <button class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editLocationModal{{ $loc->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('locations.destroy', $loc) }}" method="POST" onsubmit="deleteConfirm(event)">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-sm btn-outline-danger py-0 px-2" {{ $loc->assets_count > 0 ? 'disabled' : '' }} title="{{ $loc->assets_count > 0 ? 'Hapus aset terlebih dahulu' : 'Hapus' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editLocationModal{{ $loc->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('locations.update', $loc) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h6 class="modal-title fw-bold">Edit Lokasi</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-500">Cabang</label>
                                                    <select name="branch_id" class="form-select form-select-sm" required>
                                                        @foreach($branches as $branch)
                                                        <option value="{{ $branch->id }}" {{ $loc->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-500">Nama Lokasi</label>
                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $loc->name }}" required>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label small fw-500">Gedung</label>
                                                        <input type="text" name="building" class="form-control form-control-sm" value="{{ $loc->building }}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label small fw-500">Lantai</label>
                                                        <input type="text" name="floor" class="form-control form-control-sm" value="{{ $loc->floor }}">
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label small fw-500">Ruangan</label>
                                                        <input type="text" name="room" class="form-control form-control-sm" value="{{ $loc->room }}">
                                                    </div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label small fw-500">PIC Lokasi</label>
                                                    <input type="text" name="pic" class="form-control form-control-sm" value="{{ $loc->pic }}">
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
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('locations.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h6 class="modal-title fw-bold">Tambah Lokasi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label small fw-500">Cabang</label>
                        <select name="branch_id" class="form-select form-select-sm" required>
                            <option value="">Pilih Cabang</option>
                            @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-500">Nama Lokasi</label>
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Contoh: Ruang Meeting A" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-500">Gedung</label>
                            <input type="text" name="building" class="form-control form-control-sm" placeholder="Gedung Utama">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-500">Lantai</label>
                            <input type="text" name="floor" class="form-control form-control-sm" placeholder="Lt. 2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-500">Ruangan</label>
                            <input type="text" name="room" class="form-control form-control-sm" placeholder="R. 201">
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-500">PIC Lokasi</label>
                        <input type="text" name="pic" class="form-control form-control-sm" placeholder="Nama penanggung jawab">
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
