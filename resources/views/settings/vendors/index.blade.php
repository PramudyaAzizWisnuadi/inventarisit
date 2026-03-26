@extends('layouts.app')
@section('title', 'Kelola Vendor')
@section('content')
<div class="mb-4">
    <h5 class="fw-700 mb-1">Kelola Vendor</h5>
    <p class="text-muted small mb-0">Data vendor/supplier perangkat IT</p>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Tambah Vendor</h6></div>
            <div class="card-body">
                <form action="{{ route('vendors.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-500 small">Nama Vendor <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">No. Telepon</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-500 small">Website</label>
                    <input type="text" name="website" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Daftar Vendor ({{ $vendors->count() }})</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0 datatable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Contact</th>
                            <th>Telepon</th>
                            <th>Aset</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($vendors as $vendor)
                    <tr>
                        <td class="fw-500 small">{{ $vendor->name }}</td>
                        <td class="small">{{ $vendor->contact_person ?? '-' }}</td>
                        <td class="small">{{ $vendor->phone ?? '-' }}</td>
                        <td><span class="badge bg-light text-dark">{{ $vendor->assets_count + $vendor->software_licenses_count }}</span></td>
                        <td>
                            <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" onsubmit="deleteConfirm(event)">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
                            </form>
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
