<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — InventarisIT</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div class="brand-icon-wrapper">
                <i class="bi bi-cpu text-white"></i>
            </div>
            <div>
                <div class="brand-title">InventarisIT</div>
                <div class="brand-sub">Sistem Inventaris</div>
            </div>
        </div>
    </div>

    <div class="pt-2">
        <div class="nav-section-title">Utama</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
        </a>
        <a href="{{ route('scan.index') }}" class="nav-link {{ request()->routeIs('scan.index') ? 'active' : '' }}">
            <i class="bi bi-qr-code-scan"></i> <span>Scan Barcode</span>
        </a>

        <div class="nav-section-title">Aset</div>
        <a href="{{ route('assets.index') }}" class="nav-link {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <i class="bi bi-pc-display"></i> <span>Hardware</span>
        </a>
        <a href="{{ route('software.index') }}" class="nav-link {{ request()->routeIs('software.*') ? 'active' : '' }}">
            <i class="bi bi-disc"></i> <span>Software & Lisensi</span>
        </a>
        <a href="{{ route('assignments.index') }}" class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
            <i class="bi bi-person-check"></i> <span>Penugasan Aset</span>
        </a>
        <a href="{{ route('maintenance.index') }}" class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> <span>Pemeliharaan</span>
        </a>

        <div class="nav-section-title">Permintaan & Eksekusi</div>
        <a href="{{ route('borrow-requests.index') }}" class="nav-link {{ request()->routeIs('borrow-requests.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> <span>Peminjaman Aset</span>
        </a>
        <a href="{{ route('purchase-requests.index') }}" class="nav-link {{ request()->routeIs('purchase-requests.*') ? 'active' : '' }}">
            <i class="bi bi-cart-plus"></i> <span>Pengadaan Baru</span>
        </a>
        <a href="{{ route('asset-disposals.index') }}" class="nav-link {{ request()->routeIs('asset-disposals.*') ? 'active' : '' }}">
            <i class="bi bi-trash"></i> <span>Pemusnahan (Disposal)</span>
        </a>

        <div class="nav-section-title">Laporan</div>
        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan & Audit</span>
        </a>
        <a href="{{ route('labels.index') }}" class="nav-link {{ request()->routeIs('labels.*') ? 'active' : '' }}">
            <i class="bi bi-tag"></i> <span>Cetak Label</span>
        </a>

        <div class="nav-section-title">Pengaturan</div>
        <a href="#settingsMenu" class="nav-link {{ request()->is('settings/*') ? 'active' : 'collapsed' }}" data-bs-toggle="collapse">
            <i class="bi bi-gear"></i> <span>Pengaturan Sistem</span>
        </a>
        <div class="collapse {{ request()->is('settings/*') ? 'show' : '' }}" id="settingsMenu">
            <ul class="nav-submenu">
                <li>
                    <a href="{{ route('branches.index') }}" class="nav-link {{ request()->routeIs('branches.*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> <span>Cabang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-folder2"></i> <span>Kategori</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt"></i> <span>Lokasi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('vendors.index') }}" class="nav-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i> <span>Vendor</span>
                    </a>
                </li>
                @if(auth()->user()->hasPermission('manage-users'))
                <li>
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> <span>Users</span>
                    </a>
                </li>
                @endif
                @if(auth()->user()->hasPermission('manage-roles'))
                <li>
                    <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-lock"></i> <span>Roles</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-initials">{{ substr(auth()->user()->name, 0, 1) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role?->name ?? 'User' }}</div>
            </div>
        </div>
    </div>
</nav>

<!-- TOPBAR -->
<div id="topbar">
    <button class="btn btn-sm btn-light border d-md-none" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>
    <button class="btn btn-sm btn-light border d-none d-md-inline-block me-2" id="collapseToggle">
        <i class="bi bi-text-indent-left" id="collapseIcon"></i>
    </button>
    <div class="page-title">@yield('title', 'Dashboard')</div>
    <div class="d-flex align-items-center gap-3">
        <span class="text-muted d-none d-md-inline topbar-date">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right"></i> <span class="d-none d-md-inline">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- MAIN CONTENT -->
<div id="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>


    $(function() {
        // Initialize DataTables
        if ($('.datatable').length > 0) {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json',
                },
                pageLength: 25,
                responsive: true,
                colReorder: true,
                dom: '<"d-flex justify-content-between align-items-center"lf>rt<"d-flex justify-content-between align-items-center border-top"ip>',
            });
        }

        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        const body = document.body;
        const collapseToggle = document.getElementById('collapseToggle');
        const collapseIcon = document.getElementById('collapseIcon');

        // Load state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            body.classList.add('sidebar-collapsed');
            collapseIcon?.classList.replace('bi-text-indent-left', 'bi-text-indent-right');
        }

        collapseToggle?.addEventListener('click', function() {
            body.classList.toggle('sidebar-collapsed');
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            
            if (isCollapsed) {
                collapseIcon?.classList.replace('bi-text-indent-left', 'bi-text-indent-right');
            } else {
                collapseIcon?.classList.replace('bi-text-indent-right', 'bi-text-indent-left');
            }
        });
    });

    // Initialize tooltips if already collapsed
    if (body.classList.contains('sidebar-collapsed')) {
        document.querySelectorAll('.nav-link').forEach(el => {
            const title = el.querySelector('span')?.innerText;
            if (title) {
                el.setAttribute('data-bs-toggle', 'tooltip');
                el.setAttribute('data-bs-placement', 'right');
                el.setAttribute('title', title);
                new bootstrap.Tooltip(el);
            }
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif

    window.deleteConfirm = function(e) {
        e.preventDefault();
        var form = e.currentTarget;
        const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
</script>
@stack('scripts')
</body>
</html>
