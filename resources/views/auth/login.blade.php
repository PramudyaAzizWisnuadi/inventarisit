<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — InventarisIT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
        }
        .login-card {
            width: 100%; max-width: 420px; background: #fff;
            border-radius: 20px; padding: 40px; box-shadow: 0 25px 60px rgba(0,0,0,.4);
        }
        .brand-icon {
            width: 56px; height: 56px; background: #6366f1;
            border-radius: 14px; display: flex; align-items: center; justify-content: center;
        }
        .input-group-custom { transition: all 0.2s; }
        .input-group-custom:focus-within { box-shadow: 0 0 0 3px rgba(99,102,241,.15); }
        .input-group-custom:focus-within .input-group-text,
        .input-group-custom:focus-within .form-control { border-color: #6366f1; }
        .form-control:focus { box-shadow: none; }
        .form-control::placeholder { color: #adb5bd; font-size: 0.95rem; }
        .btn-login {
            background: #6366f1; border: none; color: #fff; padding: 12px;
            border-radius: 10px; font-weight: 600; font-size: .95rem;
            transition: all .2s;
        }
        .btn-login:hover { background: #4f46e5; color: #fff; transform: translateY(-2px); box-shadow: 0 8px 15px rgba(99,102,241,.2); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <div class="brand-icon mx-auto mb-3">
                <i class="bi bi-cpu text-white fs-4"></i>
            </div>
            <h4 class="fw-700 mb-1">InventarisIT</h4>
            <p class="text-muted small">Sistem Manajemen Inventaris Perangkat IT</p>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                });
            </script>
        @endif

        @if($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    Toast.fire({
                        icon: 'error',
                        title: "{{ $errors->first() }}"
                    });
                });
            </script>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label text-secondary small fw-semibold mb-1">Alamat Email</label>
                <div class="input-group input-group-custom">
                    <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control border-start-0 ps-0 py-2"
                        placeholder="Masukan Email Anda..." value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary small fw-semibold mb-1">Kata Sandi</label>
                <div class="input-group input-group-custom">
                    <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0 ps-0 py-2"
                        placeholder="Masukan Password Anda..." required>
                    <span class="input-group-text bg-white border-start-0 text-muted px-3" style="cursor: pointer;" id="togglePasswordBtn" title="Lihat Sandi">
                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted user-select-none" for="remember" style="cursor: pointer;">Ingat Saya</label>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100 mb-2">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
            </button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">
            &copy; {{ date('Y') }} Divisi IT — InventarisIT v{{ config('app.version') }}
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('passwordInput');
            const togglePasswordIcon = document.getElementById('togglePasswordIcon');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    togglePasswordIcon.classList.toggle('bi-eye');
                    togglePasswordIcon.classList.toggle('bi-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
