<div class="sidebar bg-white border-end d-flex flex-column" style="width: 280px; min-height: 100vh; position: sticky; top: 0; z-index: 1000;">
    <div class="sidebar-logo text-center">
        @if(isset($setting) && $setting->logo)
            <img src="{{ asset('uploads/settings/'.$setting->logo) }}" height="50" alt="Logo">
        @else
            <h4 class="fw-bold text-primary mb-0"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
        @endif
        <p class="text-muted small mt-2 mb-0 fw-bold text-uppercase" style="letter-spacing: 1.5px;">Executive Panel</p>
    </div>
    
    <div class="p-3 flex-grow-1">
        <div class="menu-label text-muted mb-2 mt-2" style="font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Main Analytics</div>
        <a href="{{ url('/management/dashboard') }}" class="nav-link-custom {{ Request::is('management/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill me-3"></i> Executive Dashboard
        </a>
        
        <div class="menu-label text-muted mb-2 mt-4" style="font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Operational Control</div>
        
        <a href="{{ url('/management/units') }}" class="nav-link-custom {{ Request::is('management/units*') ? 'active' : '' }}">
            <i class="bi bi-truck me-3"></i> Manajemen Unit
        </a>
        
        <a href="{{ url('/management/cuti') }}" class="nav-link-custom {{ Request::is('management/cuti*') ? 'active' : '' }}">
            <i class="bi bi-check2-circle me-3"></i> Approval Center Cuti
        </a>
        
        <a href="{{ url('/management/karyawan') }}" class="nav-link-custom {{ Request::is('management/karyawan*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-3"></i> Kelola Karyawan
        </a>

        <div class="menu-label text-muted mb-2 mt-4" style="font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Reporting</div>
        <a href="{{ url('/management/laporan') }}" class="nav-link-custom {{ Request::is('management/laporan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph-fill me-3"></i> Laporan Global
        </a>

        <!-- 🔥 TAMBAHAN MENU UBAH PASSWORD -->
        <div class="menu-label text-muted mb-2 mt-4" style="font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Akun & Keamanan</div>
        <a href="{{ url('/management/ubah-password') }}" class="nav-link-custom {{ Request::is('management/ubah-password*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill me-3"></i> Ubah Password
        </a>
    </div>

    <div class="p-4 border-top">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger w-100 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Logout Akun
            </button>
        </form>
    </div>
</div>