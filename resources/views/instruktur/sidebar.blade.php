<!-- DESKTOP SIDEBAR -->
<div class="sidebar bg-white border-end d-none d-md-flex flex-column" style="width: 280px; min-height: 100vh; position: sticky; top: 0; z-index: 1000;">
    <div class="sidebar-logo text-center p-4 border-bottom">
        @if(isset($setting) && $setting->logo)
            <img src="{{ asset('uploads/settings/'.$setting->logo) }}" height="50" alt="Logo">
        @else
            <h4 class="fw-bold text-info mb-0"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
        @endif
        <p class="text-muted small mt-2 mb-0 fw-bold">PORTAL INSTRUKTUR</p>
    </div>
    
    <div class="p-3 flex-grow-1">
        <div class="menu-label text-muted mb-2 mt-2" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding-left: 15px;">Operasional Lapangan</div>
        
        <a href="{{ url('/instruktur/dashboard') }}" class="nav-link-custom {{ Request::is('instruktur/dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill me-3"></i> Ruang Kerja (Jadwal)
        </a>
        <a href="{{ url('/instruktur/riwayat') }}" class="nav-link-custom {{ Request::is('instruktur/riwayat') ? 'active' : '' }}">
            <i class="bi bi-journal-text me-3"></i> Data & Riwayat Siswa
        </a>
        
        <div class="menu-label text-muted mb-2 mt-4" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; padding-left: 15px;">Personal</div>
        
        <a href="{{ url('/instruktur/cuti') }}" class="nav-link-custom {{ Request::is('instruktur/cuti') ? 'active' : '' }}">
            <i class="bi bi-calendar-x me-3"></i> Pengajuan Cuti
        </a>
    </div>

    <div class="p-4 border-top">
        <form action="{{ url('/logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger w-100 fw-bold rounded-pill shadow-sm">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>
    </div>
</div>

<!-- OFFCANVAS SIDEBAR (MOBILE) -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="offcanvasSidebarInstruktur">
    <div class="offcanvas-header border-bottom p-4">
        <div class="d-flex align-items-center">
            @if(isset($setting) && $setting->logo)
                <img src="{{ asset('uploads/settings/'.$setting->logo) }}" height="40" class="me-2">
            @endif
            <h5 class="fw-bold text-info mb-0">Satria Jayanti</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3 d-flex flex-column">
        <div class="flex-grow-1">
            <div class="menu-label text-muted mb-2" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Operasional Lapangan</div>
            <a href="{{ url('/instruktur/dashboard') }}" class="nav-link-custom {{ Request::is('instruktur/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-3"></i> Ruang Kerja (Jadwal)
            </a>
            <a href="{{ url('/instruktur/riwayat') }}" class="nav-link-custom {{ Request::is('instruktur/riwayat') ? 'active' : '' }}">
                <i class="bi bi-journal-text me-3"></i> Data & Riwayat Siswa
            </a>
            
            <div class="menu-label text-muted mb-2 mt-4" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Personal</div>
            <a href="{{ url('/instruktur/cuti') }}" class="nav-link-custom {{ Request::is('instruktur/cuti') ? 'active' : '' }}">
                <i class="bi bi-calendar-x me-3"></i> Pengajuan Cuti
            </a>
        </div>
        <div class="mt-auto pt-3 border-top">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger w-100 fw-bold rounded-pill"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
            </form>
        </div>
    </div>
</div>

<style>
    .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; font-weight: 500; transition: 0.3s; }
    .nav-link-custom:hover { background: #f1f5f9; color: #0d6efd; }
    .nav-link-custom.active { background: #e0f8fd; color: #0d6efd; font-weight: 700 !important; border-left: 4px solid #0dcaf0; }
</style>