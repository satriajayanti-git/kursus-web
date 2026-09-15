<div class="sidebar bg-white border-end d-flex flex-column" style="width: 280px; min-height: 100vh; position: sticky; top: 0; z-index: 1000;">
    
    <!-- Area Logo & Branding -->
    <div class="sidebar-logo text-center p-4 border-bottom bg-light">
        @if(isset($setting) && $setting->logo)
            <!-- Menampilkan Logo SJN di atas -->
            <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" style="max-height: 55px;" alt="Logo SJN" class="mb-2 img-fluid">
        @else
            <!-- Fallback icon jika gambar dari database kosong -->
            <i class="bi bi-steering text-primary d-block mb-2" style="font-size: 2.5rem;"></i>
        @endif
        
        <!-- Teks Satria Jayanti diletakkan statis di bawah logo -->
        <h5 class="fw-bold text-primary mb-0" style="letter-spacing: 0.5px;">Satria Jayanti</h5>
        <p class="text-muted mt-1 mb-0 fw-bold text-uppercase" style="letter-spacing: 1.5px; font-size: 0.65rem;">Executive Panel</p>
    </div>
    
    <!-- Area Menu Navigasi -->
    <div class="p-3 flex-grow-1 overflow-auto">
        <div class="menu-label text-muted mb-2 mt-2 px-2" style="font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Main Analytics</div>
        <a href="{{ url('/management/dashboard') }}" class="nav-link-custom {{ Request::is('management/dashboard') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-grid-fill me-3"></i> Executive Dashboard
        </a>
        
        <div class="menu-label text-muted mb-2 mt-4 px-2" style="font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Operational Control</div>
        <a href="{{ url('/management/units') }}" class="nav-link-custom {{ Request::is('management/units*') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-truck me-3"></i> Manajemen Unit
        </a>
        
        <a href="{{ url('/management/cuti') }}" class="nav-link-custom {{ Request::is('management/cuti*') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-calendar-event-fill me-3"></i> Approval Center Cuti
        </a>
        
        <a href="{{ url('/management/karyawan') }}" class="nav-link-custom {{ Request::is('management/karyawan*') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-people-fill me-3"></i> Kelola Karyawan
        </a>

        <div class="menu-label text-muted mb-2 mt-4 px-2" style="font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Reporting</div>
        <a href="{{ url('/management/laporan') }}" class="nav-link-custom {{ Request::is('management/laporan*') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-file-earmark-bar-graph-fill me-3"></i> Laporan Global
        </a>

        <div class="menu-label text-muted mb-2 mt-4 px-2" style="font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">Akun & Keamanan</div>
        <a href="{{ url('/management/ubah-password') }}" class="nav-link-custom {{ Request::is('management/ubah-password*') ? 'active' : '' }}" style="display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;">
            <i class="bi bi-shield-lock-fill me-3"></i> Ubah Password
        </a>
    </div>

    <!-- Area Logout -->
    <div class="p-4 border-top bg-white">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 fw-bold rounded-pill py-2 shadow-sm" style="font-size: 0.9rem;">
                <i class="bi bi-box-arrow-left me-2"></i>Logout Akun
            </button>
        </form>
    </div>
</div>