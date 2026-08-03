@php $setting = \App\Models\Setting::first() ?? new \App\Models\Setting(['nama_website' => 'PT. Satria Jayanti']); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $setting->nama_website }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=1920') center/cover fixed;
            min-height: 100vh; display: flex; align-items: center; font-family: 'Segoe UI', sans-serif;
        }
        .auth-card { background: rgba(255, 255, 255, 0.95); border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .btn-back { position: absolute; top: 20px; left: 20px; z-index: 100; }
        
        /* Tambahan style untuk merapikan input password agar border menyatu dengan ikon */
        .input-password-custom:focus { z-index: 0; box-shadow: none; border-color: #dee2e6; }
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="btn btn-light btn-sm rounded-pill px-3 shadow btn-back fw-bold">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="auth-card p-4 p-md-5 text-center">
                    
                    <div class="mb-4">
                        @if($setting->logo)
                            <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" alt="Logo" style="max-height: 60px;" class="mb-2">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 60px; height: 60px;"><i class="bi bi-steering fs-2"></i></div>
                        @endif
                        <h4 class="fw-bold text-dark">{{ $setting->nama_website }}</h4>
                        <p class="text-muted small">Silakan masuk ke akun Anda</p>
                    </div>

                    @if($errors->any())<div class="alert alert-danger small py-2">{{ $errors->first() }}</div>@endif
                    @if(session('success'))<div class="alert alert-success small py-2">{{ session('success') }}</div>@endif

                    <form action="{{ url('/login') }}" method="POST" class="text-start">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        
                        <!-- 🔥 BAGIAN PASSWORD YANG SUDAH DITAMBAHKAN IKON MATA -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordField" class="form-control input-password-custom border-end-0" required>
                                <button class="btn btn-white border border-start-0 text-muted" type="button" id="togglePassword" style="background-color: #fff;">
                                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">Masuk</button>
                    </form>

                    <div class="mt-4"><p class="small text-muted mb-0">Belum punya akun? <a href="{{ url('/register') }}" class="text-primary fw-bold text-decoration-none">Daftar Siswa Baru</a></p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 SCRIPT JAVASCRIPT UNTUK TOGGLE SHOW/HIDE PASSWORD -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('toggleIcon');

            togglePassword.addEventListener('click', function () {
                // Mengecek tipe input saat ini, lalu membaliknya
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Mengubah icon dari mata dicoret (eye-slash) ke mata terbuka (eye)
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        });
    </script>
</body>
</html>