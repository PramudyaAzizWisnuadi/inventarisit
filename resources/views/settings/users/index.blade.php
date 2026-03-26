@extends('layouts.app')
@section('title', 'Manajemen User')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-700 mb-1">Manajemen User</h5>
        <p class="text-muted small mb-0">Kelola pengguna sistem dan hak akses mereka</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah User
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="fw-500 small">{{ $user->name }}</div>
                        </td>
                        <td><code class="small">{{ $user->username }}</code></td>
                        <td><span class="small">{{ $user->email }}</span></td>
                        <td>
                            <span class="badge bg-light text-primary border">{{ $user->role?->name ?? 'No Role' }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $user->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                            </span>
                        </td>
                        <td><span class="small text-muted">{{ $user->created_at->format('d/m/Y') }}</span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-xs btn-sm btn-outline-secondary py-0 px-2" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="deleteConfirm(event)">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-sm btn-outline-danger py-0 px-2" {{ $user->id === auth()->id() ? 'disabled' : '' }}><i class="bi bi-trash"></i></button>
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
