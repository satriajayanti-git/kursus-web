<div class="sidebar bg-white shadow-sm border-end d-flex flex-column" style="width: 260px; min-height: 100vh; position: sticky; top: 0;">
    <div class="p-4 border-bottom text-center">
        <h4 class="fw-bolder text-primary mb-1"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
        <p class="text-muted small mb-0 fw-medium">Admin Workspace</p>
    </div>
    
    <div class="p-3 flex-grow-1">
        <div class="menu-label">Menu Utama</div>
        <a href="{{ url('/admin/dashboard') }}" class="nav-link-custom {{ Request::is('admin/dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
        
        <div class="menu-label mt-4">Manajemen Kursus</div>
        <a href="{{ url('/admin/jadwal') }}" class="nav-link-custom {{ Request::is('admin/jadwal*') ? 'active' : '' }}"><i class="bi bi-calendar-event-fill me-3"></i> Data Jadwal</a>
        <a href="{{ url('/admin/keuangan') }}" class="nav-link-custom {{ Request::is('admin/keuangan*') ? 'active' : '' }}"><i class="bi bi-wallet2 me-3"></i> Data Keuangan</a>
        <a href="{{ url('/admin/siswa') }}" class="nav-link-custom {{ Request::is('admin/siswa*') ? 'active' : '' }}"><i class="bi bi-people-fill me-3"></i> Data Siswa</a>
        <a href="{{ url('/admin/instruktur') }}" class="nav-link-custom {{ Request::is('admin/instruktur*') ? 'active' : '' }}"><i class="bi bi-person-vcard-fill me-3"></i> Data Instruktur</a>
        <a href="{{ url('/admin/cuti') }}" class="nav-link-custom {{ Request::is('admin/cuti*') ? 'active' : '' }}"><i class="bi bi-calendar2-minus-fill me-3"></i> Pengajuan Cuti</a>
        
        <div class="menu-label mt-4">Pusat Laporan</div>
        <a href="{{ url('/admin/laporan') }}" class="nav-link-custom {{ Request::is('admin/laporan*') ? 'active' : '' }}"><i class="bi bi-printer-fill me-3"></i> Cetak Laporan</a>
        
        <div class="menu-label mt-4">Sistem Web</div>
        <a href="{{ url('/admin/settings') }}" class="nav-link-custom {{ Request::is('admin/settings*') ? 'active' : '' }}"><i class="bi bi-globe2 me-3"></i> Tampilan Depan</a>
    </div>

    <div class="p-3 border-top">
        <form action="{{ url('/logout') }}" method="POST">@csrf<button class="btn btn-danger w-100 fw-bold shadow-sm"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
    </div>

    <style>
        .menu-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 700; color: #adb5bd; letter-spacing: 0.8px; margin: 15px 0 5px 15px; }
        .nav-link-custom { color: #6c757d; text-decoration: none; padding: 12px 15px; display: flex; align-items: center; border-radius: 8px; margin-bottom: 4px; transition: 0.2s; }
        .nav-link-custom:hover, .nav-link-custom.active { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; }
    </style>
</div>