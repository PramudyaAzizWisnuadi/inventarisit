@extends('layouts.app')
@section('title', 'Manajemen Role')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Manajemen Role & Hak Akses</h5>
        <p class="text-muted small mb-0">Atur peran pengguna dan permission yang tersedia</p>
    </div>
    <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-shield-lock me-1"></i>Tambah Role
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Nama Role</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th class="text-center">User</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td><div class="fw-600 small">{{ $role->name }}</div></td>
                        <td><code class="small">{{ $role->slug }}</code></td>
                        <td><span class="small text-muted">{{ $role->description ?? '-' }}</span></td>
                        <td class="text-center"><span class="badge bg-light text-dark border px-2">{{ $role->users_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" onsubmit="deleteConfirm(event)">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-sm btn-outline-danger py-0 px-2" 
                                        {{ $role->slug === 'admin' || $role->users_count > 0 ? 'disabled' : '' }} 
                                        title="{{ $role->slug === 'admin' ? 'Role Admin tidak bisa dihapus' : ($role->users_count > 0 ? 'Role masih digunakan oleh user' : 'Hapus') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
