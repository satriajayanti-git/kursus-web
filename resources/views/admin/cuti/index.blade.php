<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti Admin - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --sj-primary: #0d6efd;
            --sj-bg: #f8fafc;
            --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling (Senada dengan Dashboard) */
        .sidebar { width: 280px; min-height: 100vh; background: #fff; border-right: 1px solid #e2e8f0; position: sticky; top: 0; }
        .sidebar-logo { padding: 2rem 1.5rem; text-align: center; border-bottom: 1px solid #f1f5f9; }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 1rem; transition: 0.3s; font-weight: 500; }
        .nav-link-custom:hover { background: #f1f5f9; color: var(--sj-primary); }
        .nav-link-custom.active { background: #e7f1ff; color: var(--sj-primary); font-weight: 700; }
        .menu-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; padding: 1.5rem 2.5rem 0.5rem; }

        /* Dashboard Components */
        .header-top { background: #fff; padding: 1.25rem 2rem; border-bottom: 1px solid #e2e8f0; margin-bottom: 2rem; }
        .card-custom { border: none; border-radius: 24px; padding: 1.75rem; background: #fff; box-shadow: var(--sj-card-shadow); }
    </style>
</head>
<body>
    <div class="d-flex">
        
        <div class="sidebar d-flex flex-column">
            <div class="sidebar-logo">
                @if(isset($setting) && $setting->logo)
                    <img src="{{ asset('uploads/settings/'.$setting->logo) }}" height="50" alt="Logo">
                @else
                    <h4 class="fw-bold text-primary mb-0"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
                @endif
                <p class="text-muted small mt-2 mb-0 fw-bold">ADMIN PANEL</p>
            </div>
            
            <div class="flex-grow-1">
                <div class="menu-label">Main Menu</div>
                <a href="{{ url('/admin/dashboard') }}" class="nav-link-custom"><i class="bi bi-grid-1x2-fill me-3"></i> Dashboard</a>
                <a href="{{ url('/admin/jadwal') }}" class="nav-link-custom"><i class="bi bi-calendar3 me-3"></i> Manajemen Jadwal</a>
                <a href="{{ url('/admin/keuangan') }}" class="nav-link-custom"><i class="bi bi-wallet2 me-3"></i> Keuangan</a>
                
                <div class="menu-label">Data Master</div>
                <a href="{{ url('/admin/siswa') }}" class="nav-link-custom"><i class="bi bi-people me-3"></i> Data Siswa</a>
                <a href="{{ url('/admin/instruktur') }}" class="nav-link-custom"><i class="bi bi-person-badge me-3"></i> Data Instruktur</a>
                <a href="{{ url('/admin/cuti') }}" class="nav-link-custom active"><i class="bi bi-calendar-x me-3"></i> Pengajuan Cuti</a>

                <div class="menu-label">Settings</div>
                <a href="{{ url('/admin/settings') }}" class="nav-link-custom"><i class="bi bi-gear me-3"></i> Pengaturan Web</a>
                <a href="{{ url('/admin/laporan') }}" class="nav-link-custom"><i class="bi bi-file-earmark-pdf me-3"></i> Laporan</a>
            </div>

            <div class="p-4 border-top">
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger w-100 rounded-pill fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </div>
        </div>

        <div class="flex-grow-1">
            
            <div class="header-top d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Pengajuan Cuti Personal</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end me-2">
                        <p class="small text-muted mb-0">Cabang Penugasan:</p>
                        <span class="badge bg-primary rounded-pill">{{ $user->branch->nama_cabang ?? 'Belum Diatur' }}</span>
                    </div>
                    <div class="vr mx-2"></div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-light rounded-circle p-2"><i class="bi bi-person text-primary"></i></div>
                        <span class="small fw-bold">{{ $user->nama_lengkap }}</span>
                    </div>
                </div>
            </div>

            <div class="px-4 pb-4">
                
                @if(session('success')) 
                    <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div> 
                @endif
                @if(session('error')) 
                    <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div> 
                @endif

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card-custom border-top border-primary border-4">
                            <h5 class="fw-bold mb-4"><i class="bi bi-calendar-plus me-2 text-primary"></i>Buat Pengajuan</h5>
                            <form action="{{ url('/admin/cuti') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="small fw-bold mb-1 text-muted">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control bg-light border-0 py-2" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small fw-bold mb-1 text-muted">Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control bg-light border-0 py-2" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="small fw-bold mb-1 text-muted">Alasan Cuti</label>
                                    <textarea name="alasan" class="form-control bg-light border-0 py-2" rows="4" placeholder="Contoh: Keperluan keluarga..." required></textarea>
                                </div>
                                <button class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm py-2">Kirim ke Management</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card-custom">
                            <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-secondary"></i>Riwayat Pengajuan Anda</h5>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="py-3 px-3">Tanggal Pelaksanaan</th>
                                            <th class="py-3">Alasan Cuti</th>
                                            <th class="py-3 text-center">Status ACC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cutis as $c)
                                        <tr>
                                            <td class="px-3 py-3">
                                                <div class="fw-bold text-dark">{{ date('d M Y', strtotime($c->tanggal_mulai)) }}</div>
                                                <div class="small text-muted">s/d {{ date('d M Y', strtotime($c->tanggal_selesai)) }}</div>
                                            </td>
                                            <td style="max-width: 250px;"><small class="text-muted fw-medium">{{ $c->alasan }}</small></td>
                                            <td class="text-center">
                                                @if($c->status == 'Pending') 
                                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Menunggu Keputusan</span>
                                                @elseif($c->status == 'Disetujui') 
                                                    <span class="badge bg-success px-3 py-2 rounded-pill">Disetujui Management</span>
                                                @else 
                                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Ditolak</span> 
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="3" class="text-center py-5 text-muted">Anda belum memiliki riwayat pengajuan cuti.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>