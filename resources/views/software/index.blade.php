@extends('layouts.app')
@section('title', 'Inventaris Software & Lisensi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Software & Lisensi</h5>
        <p class="text-muted small mb-0">Kelola lisensi software yang dimiliki organisasi</p>
    </div>
    <a href="{{ route('software.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Lisensi
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Software</th>
                        <th>Tipe Lisensi</th>
                        <th>Kategori</th>
                        <th>Pengguna</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($licenses as $sw)
                <tr>
                    <td><code class="text-primary small">{{ $sw->license_code }}</code></td>
                    <td>
                        <div class="fw-500 small">{{ $sw->software_name }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $sw->publisher }} v{{ $sw->version }}</div>
                    </td>
                    <td><span class="badge bg-light text-dark">{{ $sw->license_type }}</span></td>
                    <td><span class="small">{{ $sw->category?->name ?? '-' }}</span></td>
                    <td>
                        <div class="d-flex align-items-center gap-1">
                            <div class="progress" style="flex:1;height:6px;max-width:60px;">
                                <div class="progress-bar {{ $sw->used_users >= $sw->max_users ? 'bg-danger' : 'bg-success' }}"
                                    style="width: {{ $sw->max_users ? min(100, $sw->used_users/$sw->max_users*100) : 0 }}%"></div>
                            </div>
                            <span class="small text-muted">{{ $sw->used_users }}/{{ $sw->max_users }}</span>
                        </div>
                    </td>
                    <td>
                        @if($sw->expiry_date)
                            <span class="small {{ $sw->isExpired() ? 'text-danger fw-500' : ($sw->isExpiringSoon() ? 'text-warning' : 'text-success') }}">
                                {{ $sw->expiry_date->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-muted small">Permanen</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $sw->status == 'Aktif' ? 'badge-aktif' : 'badge-expired' }}">{{ $sw->status }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('software.show', $sw) }}" class="btn btn-sm btn-outline-primary py-0 px-2"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('software.edit', $sw) }}" class="btn btn-sm btn-outline-secondary py-0 px-2"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('software.destroy', $sw) }}" onsubmit="deleteConfirm(event)">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="bi bi-trash"></i></button>
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
