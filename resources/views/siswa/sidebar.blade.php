<div class="sidebar bg-white shadow-sm border-end d-flex flex-column" style="width: 280px; min-height: 100vh; position: sticky; top: 0;">
    <div class="p-4 border-bottom text-center">
        @php $setting = \App\Models\Setting::first(); @endphp
        @if($setting && $setting->logo)
            <img src="{{ asset('uploads/settings/'.$setting->logo) }}" height="40" class="mb-2">
        @else
            <h4 class="fw-bold text-primary mb-0"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
        @endif
        <p class="text-muted small mb-0 fw-bold">Student Portal</p>
    </div>
    
    <div class="p-3 flex-grow-1">
        <div class="menu-label mb-2">Main Menu</div>
        <a href="{{ url('/siswa/dashboard') }}" class="nav-link-custom active">
            <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard & Progres
        </a>
        
        <div class="menu-label mt-4 mb-2">Administrasi</div>
        <a href="#" class="nav-link-custom text-muted">
            <i class="bi bi-person-badge me-3"></i> Profil Saya
        </a>
        <a href="#" class="nav-link-custom text-muted">
            <i class="bi bi-receipt-cutoff me-3"></i> Riwayat Transaksi
        </a>
    </div>

    <div class="p-3 border-top">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger w-100 fw-bold rounded-pill">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>

    <style>
        .menu-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #adb5bd; letter-spacing: 1px; padding-left: 15px; }
        .nav-link-custom { color: #495057; text-decoration: none; padding: 12px 15px; display: flex; align-items: center; border-radius: 12px; margin-bottom: 5px; transition: 0.3s; font-weight: 500; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; }
    </style>
</div>