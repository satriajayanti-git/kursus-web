<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Password - Executive Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-bg: #f8fafc; }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .nav-link-custom { color: #495057; text-decoration: none; padding: 12px 15px; display: flex; align-items: center; border-radius: 12px; margin-bottom: 5px; transition: 0.3s; font-weight: 500; cursor: pointer; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('management.sidebar')
        
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Ubah Kata Sandi</h3>
                    <p class="text-muted m-0 small">Pastikan akun Anda terlindungi dengan menggunakan kata sandi yang kuat.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 fw-bold mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 fw-bold mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 fw-bold mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 col-lg-5">
                    <div class="card card-custom p-4 bg-white">
                        <form action="{{ url('/management/ubah-password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Password Lama</label>
                                <input type="password" name="password_lama" class="form-control shadow-sm" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Password Baru</label>
                                <input type="password" name="password_baru" class="form-control shadow-sm" required minlength="6">
                                <small class="text-muted" style="font-size: 0.7rem;">Minimal 6 karakter.</small>
                            </div>
                            
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-1">Konfirmasi Password Baru</label>
                                <input type="password" name="password_baru_confirmation" class="form-control shadow-sm" required minlength="6">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2">
                                <i class="bi bi-shield-lock-fill me-2"></i>Simpan Password Baru
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>