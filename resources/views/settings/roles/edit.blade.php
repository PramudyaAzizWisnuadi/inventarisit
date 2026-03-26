@extends('layouts.app')
@section('title', 'Edit Role')
@section('content')
<div class="mb-4">
    <h5 class="fw-700 mb-1">Edit Role: {{ $role->name }}</h5>
    <nav aria-label="breadcrumb"><ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol></nav>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-500 small">Nama Role</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required {{ $role->slug === 'admin' ? 'readonly' : '' }}>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500 small">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $role->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-500 small d-block mb-3">Hak Akses (Permissions)</label>
                        <div class="row g-3">
                            @foreach($permissions as $p)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p->id }}" id="p_{{ $p->id }}" 
                                        {{ in_array($p->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}
                                        {{ $role->slug === 'admin' ? 'disabled' : '' }}>
                                    <label class="form-check-label small" for="p_{{ $p->id }}" title="{{ $p->description }}">
                                        {{ $p->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('permissions') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>

                    @if($role->slug === 'admin')
                    <div class="alert alert-info py-2 px-3 small">
                        <i class="bi bi-info-circle me-1"></i>Role Administrator memiliki semua hak akses secara default dan tidak dapat diubah.
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm px-4" {{ $role->slug === 'admin' ? 'disabled' : '' }}>Simpan Perubahan</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
