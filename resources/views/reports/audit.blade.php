@extends('layouts.app')
@section('title', 'Log Audit Sistem')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-700 mb-1">Log Audit Sistem</h5>
        <p class="text-muted small mb-0">Semua aktivitas tercatat secara otomatis untuk keperluan audit</p>
    </div>
    <a href="{{ route('reports.audit', array_merge(request()->all(), ['format'=>'pdf'])) }}" class="btn btn-danger btn-sm">
        <i class="bi bi-filetype-pdf me-1"></i>Export PDF
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 table-sm datatable">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($logs as $log)
                @php
                    $actionColors = ['create'=>'success','update'=>'info','delete'=>'danger','login'=>'primary','logout'=>'secondary','export'=>'warning','print'=>'dark'];
                    $color = $actionColors[$log->action] ?? 'secondary';
                @endphp
                <tr>
                    <td class="small">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="small">{{ $log->user_name ?? 'System' }}</td>
                    <td><span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 small">{{ ucfirst($log->action) }}</span></td>
                    <td class="small">{{ $log->description }}</td>
                    <td class="text-muted small">{{ $log->ip_address }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
