<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Unit & Aset - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-primary: #0d6efd; --sj-bg: #f8fafc; --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #eef2ff; color: var(--sj-primary); font-weight: 600; }
        .main-content { margin-left: 280px; padding: 2rem; min-height: 100vh; }
        .card-custom { border: none; border-radius: 20px; background: #fff; box-shadow: var(--sj-card-shadow); overflow: hidden;}
        .table > :not(caption) > * > * { padding: 1rem 1.5rem; }
        
        .nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 600; padding: 1rem 1.5rem; border-bottom: 3px solid transparent; transition: all 0.3s ease;}
        .nav-tabs .nav-link.active { border-bottom: 3px solid var(--sj-primary); color: var(--sj-primary); background: transparent; }
        .nav-tabs .nav-link:hover:not(.active) { border-bottom: 3px solid #dee2e6; }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <div class="sidebar bg-white border-end d-flex flex-column" style="width: 280px; min-height: 100vh; position: fixed; top: 0; z-index: 1000;">
        <div class="sidebar-logo text-center p-4 border-bottom">
            @if(isset($setting) && $setting->logo)
                <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="50" alt="Logo">
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

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark m-0">Pusat Kendali Aset Kendaraan</h3>
                <p class="text-muted m-0">Kelola armada operasional, transmisi, mutasi cabang, pajak, dan audit pelacakan unit.</p>
            </div>
            <button class="btn btn-primary fw-bold rounded-pill shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalTambahUnit">
                <i class="bi bi-plus-lg me-2"></i>Tambah Armada Baru
            </button>
        </header>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}</div>
        @endif

        <!-- SISTEM 3 TAB ERP -->
        <ul class="nav nav-tabs mb-4 border-bottom-0" id="unitTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-armada" type="button">
                    <i class="bi bi-truck me-2"></i>Data Armada & Legalitas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ticketing" type="button">
                    <i class="bi bi-headset me-2"></i>Laporan Unit Kendaraan
                    @php $pendingCount = isset($laporans) ? $laporans->where('status_laporan', 'Menunggu')->count() : 0; @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-danger rounded-pill ms-2 shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-audit" type="button">
                    <i class="bi bi-shield-lock-fill me-2"></i>Audit Trail & Tracking
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- TAB 1: MASTER DATA UNIT -->
            <div class="tab-pane fade show active" id="tab-armada">
                <div class="card card-custom">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kendaraan & Status</th>
                                    <th>Mapping Lokasi & Penanggung Jawab</th>
                                    <th>Status Pajak (STNK) & KIR</th>
                                    <th class="text-center">Aksi Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $unit)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 position-relative">
                                                @if($unit->foto_unit)
                                                    <img src="{{ asset('storage/uploads/units/'.$unit->foto_unit) }}" alt="Mobil" class="rounded-3 object-fit-cover shadow-sm" style="width: 80px; height: 60px;">
                                                @else
                                                    <div class="bg-primary text-white rounded-3 d-flex justify-content-center align-items-center shadow-sm" style="width: 80px; height: 60px;">
                                                        <i class="bi bi-car-front-fill fs-4"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="fw-bold m-0 text-dark">{{ $unit->nama_mobil }}</h6>
                                                <div class="text-muted m-0 small fw-bold mb-1"><i class="bi bi-card-text me-1"></i> {{ $unit->nopol ?? 'Nopol Kosong' }}</div>
                                                
                                                <div class="d-flex gap-1 flex-wrap">
                                                    @if($unit->status_operasional == 'Aktif')
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.6rem;"><i class="bi bi-check-circle-fill me-1"></i>Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.6rem;"><i class="bi bi-wrench me-1"></i>Bengkel</span>
                                                    @endif

                                                    @if($unit->status_kepemilikan == 'Tetap')
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.6rem;">Tetap</span>
                                                    @else
                                                        <span class="badge bg-warning-subtle text-dark border border-warning-subtle" style="font-size: 0.6rem;">Rolling</span>
                                                    @endif
                                                    
                                                    <!-- 🔥 Indikator Transmisi -->
                                                    <span class="badge bg-dark text-white" style="font-size: 0.6rem;">⚙️ {{ $unit->transmisi ?? 'Manual' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small mb-1"><i class="bi bi-building me-1 text-primary"></i> Cabang: <strong>{{ $unit->branch->nama_cabang ?? 'Pusat / Belum Diset' }}</strong></div>
                                        <div class="small"><i class="bi bi-person-badge me-1 text-secondary"></i> P.Jawab: <strong>{{ $unit->instruktur->nama_lengkap ?? 'Tidak Ada (Mobil Rolling)' }}</strong></div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">STNK: 
                                            @if($unit->tgl_jatuh_tempo_pajak)
                                                @php $diffPajak = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($unit->tgl_jatuh_tempo_pajak), false); @endphp
                                                <strong class="{{ $diffPajak <= 14 ? 'text-danger' : 'text-success' }}">{{ \Carbon\Carbon::parse($unit->tgl_jatuh_tempo_pajak)->format('d M Y') }}</strong>
                                            @else
                                                <span class="text-secondary"> - </span>
                                            @endif
                                        </div>
                                        <div class="small text-muted mt-1">KIR: 
                                            @if($unit->tgl_jatuh_tempo_kir)
                                                @php $diffKir = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($unit->tgl_jatuh_tempo_kir), false); @endphp
                                                <strong class="{{ $diffKir <= 14 ? 'text-danger' : 'text-success' }}">{{ \Carbon\Carbon::parse($unit->tgl_jatuh_tempo_kir)->format('d M Y') }}</strong>
                                            @else
                                                <span class="text-secondary"> - </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info text-white rounded-pill mb-1 fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMutasi{{ $unit->id }}" title="Mutasi Cabang & Mapping">
                                            <i class="bi bi-arrow-left-right me-1"></i> Mutasi
                                        </button>
                                        <br>
                                        <button class="btn btn-sm btn-outline-warning rounded-pill mb-1 fw-bold px-2" data-bs-toggle="modal" data-bs-target="#modalPajak{{ $unit->id }}" style="font-size: 0.75rem;">STNK</button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill mb-1 fw-bold px-2" data-bs-toggle="modal" data-bs-target="#modalKir{{ $unit->id }}" style="font-size: 0.75rem;">KIR</button>
                                        <br>
                                        <button class="btn btn-sm btn-primary rounded-circle me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditUnit{{ $unit->id }}" title="Edit Data"><i class="bi bi-pencil"></i></button>
                                        <button class="btn btn-sm btn-danger rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalHapusUnit{{ $unit->id }}" title="Hapus Data"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-5 fst-italic"><i class="bi bi-inboxes display-4 d-block mb-3 opacity-25"></i>Belum ada data armada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: TICKETING LAPORAN KENDALA -->
            <div class="tab-pane fade" id="tab-ticketing">
                <div class="card card-custom">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Waktu Lapor</th>
                                    <th>Armada Bermasalah</th>
                                    <th>Pelapor (Instruktur)</th>
                                    <th>Kendala & Deskripsi</th>
                                    <th class="text-center">Tindakan Management</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($laporans))
                                    @forelse($laporans as $tiket)
                                    <tr class="{{ $tiket->status_laporan == 'Menunggu' ? 'bg-danger-subtle bg-opacity-10' : '' }}">
                                        <td>
                                            <div class="fw-bold small">{{ $tiket->created_at->format('d M Y') }}</div>
                                            <div class="text-muted small">{{ $tiket->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark"><i class="bi bi-car-front-fill me-1 text-info"></i> {{ $tiket->unit->nopol ?? 'Unit Terhapus' }}</div>
                                            <div class="small text-muted">{{ $tiket->unit->nama_mobil ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-dark border"><i class="bi bi-person-badge me-1"></i> {{ $tiket->instruktur->nama_lengkap ?? 'Anonim' }}</span>
                                        </td>
                                        <td>
                                            <div class="mb-1">
                                                @if($tiket->tingkat_kendala == 'Berat') <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem;">Darurat (Berat)</span>
                                                @else <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.7rem;">Info (Ringan)</span> @endif
                                            </div>
                                            <div class="small text-truncate" style="max-width: 250px;" title="{{ $tiket->deskripsi }}">
                                                "{{ $tiket->deskripsi }}"
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($tiket->status_laporan == 'Menunggu')
                                                <button class="btn btn-danger btn-sm rounded-pill fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalProsesTiket{{ $tiket->id }}">Tindak Lanjuti</button>
                                            @elseif($tiket->status_laporan == 'Diproses')
                                                <button class="btn btn-primary btn-sm rounded-pill fw-bold shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#modalProsesTiket{{ $tiket->id }}">Update Progres</button>
                                            @else
                                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-all me-1"></i>Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="text-center text-muted py-5 fst-italic"><i class="bi bi-shield-check display-4 d-block mb-3 text-success opacity-50"></i>Kondisi armada 100% aman. Belum ada laporan.</td></tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: AUDIT TRAIL & LIVE TRACKING -->
            <div class="tab-pane fade" id="tab-audit">
                <div class="card card-custom border-top border-dark border-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle m-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Identitas Armada</th>
                                    <th>Status & Penanggung Jawab</th>
                                    <th class="text-center">Total Riwayat Terdata</th>
                                    <th class="text-center">Tindakan Audit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($units as $unit)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><i class="bi bi-car-front-fill me-2 text-primary"></i>{{ $unit->nama_mobil }} <span class="badge bg-dark ms-1" style="font-size:0.6rem;">{{ $unit->transmisi ?? 'Manual' }}</span></div>
                                        <div class="text-muted small ms-4">{{ $unit->nopol ?? 'Nopol Kosong' }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold {{ $unit->status_kepemilikan == 'Tetap' ? 'text-primary' : 'text-warning' }}">
                                            {{ $unit->status_kepemilikan == 'Tetap' ? 'Mobil Utama' : 'Mobil Cadangan (Rolling)' }}
                                        </div>
                                        <div class="small text-muted">P.Jawab: {{ $unit->instruktur->nama_lengkap ?? 'Tidak Ada' }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary rounded-pill px-3 shadow-sm">
                                            {{ $unit->jadwals()->whereIn('status', ['Disetujui', 'Selesai'])->count() }} Sesi
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-dark rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAudit{{ $unit->id }}">
                                            <i class="bi bi-clock-history me-1"></i> Buka Log Audit
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-5 fst-italic">Belum ada armada yang terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 🔥 SEMUA MODAL DENGAN AMAN DILETAKKAN DI LUAR TABEL (ANTI BUG HTML DOM) 🔥 -->
    <!-- ========================================================================= -->

    <!-- 1. MODAL TAMBAH UNIT BARU -->
    <div class="modal fade" id="modalTambahUnit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ url('/management/units') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="fw-bold mb-0">Tambah Unit Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Nama Mobil</label>
                            <input type="text" name="nama_mobil" class="form-control" placeholder="Cth: Toyota Avanza" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Nomor Polisi (Plat)</label>
                            <input type="text" name="nopol" class="form-control" placeholder="Cth: B 1234 CD" required>
                        </div>
                        <!-- 🔥 Input Transmisi (Baru) -->
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Kategori Transmisi</label>
                            <select name="transmisi" class="form-select" required>
                                <option value="Manual">Manual</option>
                                <option value="Matic">Matic</option>
                                <option value="Manual & Matic">Manual & Matic</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted mb-1">Upload Foto Unit</label>
                            <input type="file" name="foto_unit" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                            <div class="form-text small">Format: JPG, JPEG, PNG (Maks 2MB)</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. LOOPING SEMUA MODAL UNTUK MASING-MASING UNIT -->
    @foreach($units as $unit)
        <!-- MODAL MUTASI -->
        <div class="modal fade" id="modalMutasi{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <form action="{{ url('/management/units/mutasi/'.$unit->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header bg-info text-white border-0">
                            <h5 class="fw-bold mb-0"><i class="bi bi-arrow-left-right me-2"></i>Mutasi & Mapping Aset</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="alert alert-light border small mb-3">
                                Kendaraan: <strong>{{ $unit->nama_mobil }} ({{ $unit->nopol }})</strong>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Tugaskan ke Cabang</label>
                                <select name="branch_id" class="form-select shadow-sm" required>
                                    <option value="">-- Pilih Cabang Penempatan --</option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option value="{{ $branch->id }}" {{ $unit->branch_id == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Status Kepemilikan Unit</label>
                                <select name="status_kepemilikan" class="form-select shadow-sm" id="statusKepemilikan{{ $unit->id }}" onchange="toggleInstruktur({{ $unit->id }})" required>
                                    <option value="Tetap" {{ $unit->status_kepemilikan == 'Tetap' ? 'selected' : '' }}>Tetap (Diikat ke Instruktur Utama)</option>
                                    <option value="Rolling" {{ $unit->status_kepemilikan == 'Rolling' ? 'selected' : '' }}>Rolling (Unit Cadangan / Saling Tukar)</option>
                                </select>
                            </div>
                            <div class="mb-0" id="wrapperInstruktur{{ $unit->id }}" style="{{ $unit->status_kepemilikan == 'Rolling' ? 'display:none;' : '' }}">
                                <label class="small fw-bold text-muted mb-1">Instruktur Penanggung Jawab Utama</label>
                                <select name="instruktur_id" class="form-select shadow-sm">
                                    <option value="">-- Pilih Instruktur Tetap --</option>
                                    <!-- 🔥 Tambahkan with('branch') agar relasi nama cabang ikut ditarik -->
                                    @foreach(\App\Models\User::with('branch')->where('role', 'instruktur')->get() as $ins)
                                        <option value="{{ $ins->id }}" {{ $unit->instruktur_id == $ins->id ? 'selected' : '' }}>
                                            ✅ {{ $ins->nama_lengkap }} (Cabang: {{ $ins->branch->nama_cabang ?? 'Pusat / Belum diset' }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text small">Wajib diisi jika status unit adalah Tetap.</div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-bold">Simpan Mutasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT UNIT -->
        <div class="modal fade" id="modalEditUnit{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <form action="{{ url('/management/units/'.$unit->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="modal-header border-0">
                            <h5 class="fw-bold mb-0">Edit Data & Status Unit</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Nama Mobil</label>
                                <input type="text" name="nama_mobil" class="form-control bg-light" required value="{{ $unit->nama_mobil }}">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Nomor Polisi (Plat)</label>
                                <input type="text" name="nopol" class="form-control bg-light" required value="{{ $unit->nopol }}">
                            </div>
                            <!-- 🔥 Input Edit Transmisi -->
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Transmisi Unit</label>
                                <select name="transmisi" class="form-select bg-light" required>
                                    <option value="Manual" {{ $unit->transmisi == 'Manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="Matic" {{ $unit->transmisi == 'Matic' ? 'selected' : '' }}>Matic</option>
                                    <option value="Manual & Matic" {{ $unit->transmisi == 'Manual & Matic' ? 'selected' : '' }}>Manual & Matic</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Status Operasional Unit</label>
                                <select name="status_operasional" class="form-select shadow-sm border-secondary">
                                    <option value="Aktif" {{ $unit->status_operasional == 'Aktif' ? 'selected' : '' }}>🟢 Aktif (Bisa Di-Plotting Admin)</option>
                                    <option value="Maintenance" {{ $unit->status_operasional == 'Maintenance' ? 'selected' : '' }}>🔴 Masuk Bengkel (Diblokir dari Admin)</option>
                                </select>
                            </div>
                            <div class="mb-0">
                                <label class="small fw-bold text-muted mb-1">Upload Foto Baru (Opsional)</label>
                                <input type="file" name="foto_unit" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL HAPUS UNIT -->
        <div class="modal fade" id="modalHapusUnit{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-center">
                    <form action="{{ url('/management/units/'.$unit->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <div class="modal-body p-4">
                            <div class="mb-3"><i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i></div>
                            <h5 class="fw-bold mb-2">Hapus Unit Kendaraan?</h5>
                            <p class="text-muted small">Anda yakin ingin menghapus <strong>{{ $unit->nama_mobil }} ({{ $unit->nopol }})</strong>?</p>
                        </div>
                        <div class="modal-footer border-0 d-flex justify-content-between p-3 bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Ya, Hapus Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL PAJAK -->
        <div class="modal fade" id="modalPajak{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <form action="{{ url('/management/units/pajak/'.$unit->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header border-0 bg-warning">
                            <h5 class="fw-bold mb-0 text-dark">Update Pajak - {{ $unit->nama_mobil }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Tanggal Pembayaran Hari Ini</label>
                                <input type="date" name="tgl_terakhir_bayar_pajak" class="form-control" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="mb-0">
                                <label class="small fw-bold text-muted mb-1">Tanggal Jatuh Tempo Tahun Depan</label>
                                <input type="date" name="tgl_jatuh_tempo_pajak" class="form-control" required value="{{ \Carbon\Carbon::now()->addYear()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-warning w-100 rounded-pill fw-bold text-dark">Simpan Data Pajak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL KIR -->
        <div class="modal fade" id="modalKir{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <form action="{{ url('/management/units/kir/'.$unit->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header border-0 bg-danger text-white">
                            <h5 class="fw-bold mb-0">Update KIR - {{ $unit->nama_mobil }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Tanggal Uji KIR Terakhir</label>
                                <input type="date" name="tgl_terakhir_bayar_kir" class="form-control" required value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            <div class="mb-0">
                                <label class="small fw-bold text-muted mb-1">Tanggal Jatuh Tempo KIR Selanjutnya</label>
                                <input type="date" name="tgl_jatuh_tempo_kir" class="form-control" required value="{{ \Carbon\Carbon::now()->addMonths(6)->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Simpan Data KIR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL AUDIT TRAIL LOG -->
        <div class="modal fade" id="modalAudit{{ $unit->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <div class="modal-header bg-dark text-white border-0">
                        <div>
                            <h5 class="fw-bold mb-0"><i class="bi bi-search me-2"></i>Jejak Riwayat Pemakaian Armada</h5>
                            <p class="mb-0 small text-light opacity-75">Unit: {{ $unit->nama_mobil }} ({{ $unit->nopol }})</p>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm text-start align-middle m-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th class="ps-4 py-3">Waktu Kejadian</th>
                                        <th class="py-3">Instruktur (Driver)</th>
                                        <th class="py-3">Siswa (Penumpang)</th>
                                        <th class="py-3 text-center pe-4">Status Sesi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($unit->jadwals()->with(['instructor', 'user'])->whereIn('status', ['Disetujui', 'Selesai'])->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'desc')->take(30)->get() as $log)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($log->tanggal)->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $log->jam_mulai }} WIB</div>
                                        </td>
                                        <td class="small fw-bold text-primary">{{ $log->instructor->nama_lengkap ?? 'Dihapus' }}</td>
                                        <td class="small text-muted">{{ $log->user->nama_lengkap ?? 'Dihapus' }}</td>
                                        <td class="text-center pe-4">
                                            @if($log->status == 'Selesai') 
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">Selesai</span>
                                            @else 
                                                <span class="badge bg-info-subtle text-info border border-info-subtle">Berjalan (ACC)</span> 
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5 fst-italic">Belum ada riwayat pemakaian untuk armada ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <small class="text-muted fst-italic me-auto">*Menampilkan maksimal 30 jejak riwayat pemakaian terbaru.</small>
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- 3. LOOPING MODAL LAPORAN TICKETING -->
    @if(isset($laporans))
        @foreach($laporans as $tiket)
        <div class="modal fade" id="modalProsesTiket{{ $tiket->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                    <form action="{{ url('/management/laporan-unit/'.$tiket->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-header bg-dark text-white border-0">
                            <h5 class="fw-bold mb-0"><i class="bi bi-tools me-2"></i>Tindak Lanjut Kendala</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="p-3 bg-light rounded-3 mb-4 border">
                                <h6 class="fw-bold small mb-2 text-muted">Laporan dari Instruktur:</h6>
                                <p class="mb-0 fst-italic text-dark">"{{ $tiket->deskripsi }}"</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="small fw-bold text-muted mb-1">Status Penanganan Saat Ini</label>
                                <select name="status_laporan" class="form-select shadow-sm fw-bold">
                                    <option value="Menunggu" {{ $tiket->status_laporan == 'Menunggu' ? 'selected' : '' }}>🔴 Menunggu Respon</option>
                                    <option value="Diproses" {{ $tiket->status_laporan == 'Diproses' ? 'selected' : '' }}>🟡 Sedang Masuk Bengkel / Diproses</option>
                                    <option value="Selesai" {{ $tiket->status_laporan == 'Selesai' ? 'selected' : '' }}>🟢 Masalah Selesai Diperbaiki</option>
                                </select>
                            </div>

                            <div class="mb-0 p-3 bg-danger-subtle rounded-3 border border-danger-subtle">
                                <label class="small fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>Tindakan Sistem Pada Unit (Otomatis)</label>
                                <select name="tindakan_unit" class="form-select shadow-sm">
                                    <option value="tetap">Abaikan (Biarkan status armada seperti saat ini)</option>
                                    <option value="maintenance">Blokir Armada (Tarik ke Bengkel & Hilangkan dari Admin)</option>
                                    <option value="aktif">Aktifkan Kembali (Mobil siap dipakai operasional Admin)</option>
                                </select>
                                <div class="form-text small text-dark mt-2 lh-sm">
                                    Opsi di atas akan otomatis diabaikan dan mobil akan aktif kembali jika Status Penanganan diatur ke <strong>Selesai</strong>.
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold">Simpan Keputusan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif

    <script>
        function toggleInstruktur(unitId) {
            const selectStatus = document.getElementById('statusKepemilikan' + unitId);
            const wrapperIns = document.getElementById('wrapperInstruktur' + unitId);
            
            if (selectStatus.value === 'Rolling') {
                wrapperIns.style.display = 'none';
            } else {
                wrapperIns.style.display = 'block';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>