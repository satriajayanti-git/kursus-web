<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Instruktur & Unit - CMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .nav-tabs .nav-link { border: none; color: #6c757d; font-weight: 600; padding: 0.8rem 1.25rem; border-bottom: 3px solid transparent; transition: all 0.3s ease; }
        .nav-tabs .nav-link.active { border-bottom: 3px solid #0d6efd; color: #0d6efd; background: transparent; }
        .nav-tabs .nav-link:hover:not(.active) { border-bottom: 3px solid #dee2e6; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">Pusat Operasional Cabang</h3>
                    <p class="text-muted small m-0">Kelola instruktur bertugas, penanganan kendala unit kendaraan ringan, dan reset password.</p>
                </div>
            </div>

            @if(session('success')) 
                <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold rounded-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div> 
            @endif

            @if(session('error')) 
                <div class="alert alert-danger border-0 shadow-sm mb-4 fw-bold rounded-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div> 
            @endif

            <!-- NAVIGATION TAB -->
            <ul class="nav nav-tabs mb-4 border-bottom" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-instruktur" type="button">
                        <i class="bi bi-person-badge me-2"></i>Data Instruktur
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-laporan-unit" type="button">
                        <i class="bi bi-wrench-adjustable me-2"></i>Laporan Kendala Unit
                        @php 
                            // Hitung pending khusus kendala ringan yang bisa ditangani Admin
                            $pendingRinganCount = isset($laporans) ? $laporans->where('tingkat_kendala', 'Ringan')->where('status_laporan', 'Menunggu')->count() : 0; 
                        @endphp
                        @if($pendingRinganCount > 0)
                            <span class="badge bg-danger rounded-pill ms-2">{{ $pendingRinganCount }}</span>
                        @endif
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- ================= TAB 1: DATA INSTRUKTUR ================= -->
                <div class="tab-pane fade show active" id="tab-instruktur">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 px-4">Nama Instruktur</th>
                                    <th class="py-3">Kontak</th>
                                    <th class="py-3">Unit Pegangan</th> 
                                    <th class="py-3">Spesialisasi Transmisi</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($instructors as $ins)
                                <tr>
                                    <!-- 1. Kolom Instruktur -->
                                    <td class="px-4">
                                        <div class="fw-bold text-dark">{{ $ins->nama_lengkap }}</div>
                                        <small class="text-muted fw-normal">&#64;{{ $ins->username }}</small>
                                    </td>
                                    
                                    <!-- 2. Kolom Kontak -->
                                    <td>
                                        <span class="d-block small"><i class="bi bi-whatsapp text-success me-1"></i>{{ $ins->no_telp }}</span>
                                        <small class="text-muted">{{ $ins->email }}</small>
                                    </td>

                                    <!-- 3. Kolom Unit Pegangan -->
                                    <td>
                                        @if($ins->unit_pegangan)
                                            <div class="fw-bold text-primary"><i class="bi bi-car-front-fill me-1"></i>{{ $ins->unit_pegangan->nopol }}</div>
                                            <small class="text-muted">{{ $ins->unit_pegangan->nama_mobil }}</small>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary border rounded-pill" style="font-size: 0.7rem;">
                                                <i class="bi bi-exclamation-circle me-1"></i>Instruktur Backup
                                            </span>
                                        @endif
                                    </td>

                                    <!-- 4. Kolom Transmisi -->
                                    <td>
                                        @if($ins->kategori_transmisi == 'Manual & Matic')
                                            <span class="badge bg-success rounded-pill px-3">Manual & Matic</span>
                                        @elseif($ins->kategori_transmisi == 'Matic')
                                            <span class="badge bg-info text-dark rounded-pill px-3">Khusus Matic</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">Khusus Manual</span>
                                        @endif
                                    </td>

                                    <!-- 5. Kolom Aksi -->
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#resetModal{{ $ins->id }}">
                                            <i class="bi bi-key-fill me-1"></i>Reset Password
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                        Belum ada instruktur yang ditugaskan ke cabang ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 2: LAPORAN UNIT KENDARAAN ================= -->
                <div class="tab-pane fade" id="tab-laporan-unit">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="p-3 p-md-4 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark"><i class="bi bi-headset me-2 text-primary"></i>Laporan Kendala Armada Kendaraan</h6>
                                <p class="text-muted small mb-0">Admin cabang hanya berwenang menyelesaikan kendala <strong>Ringan</strong>. Kendala <strong>Berat</strong> langsung ditangani Management.</p>
                            </div>
                            <button class="btn btn-warning text-dark fw-bold rounded-pill px-3 shadow-sm btn-sm" data-bs-toggle="modal" data-bs-target="#modalBuatLaporanAdmin">
                                <i class="bi bi-plus-circle-fill me-1"></i> Input Laporan Kendala
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 px-4">Waktu Lapor</th>
                                        <th class="py-3">Armada / Unit</th>
                                        <th class="py-3">Pelapor</th>
                                        <th class="py-3">Tingkat Kendala & Deskripsi</th>
                                        <th class="py-3 text-center">Tindakan Admin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($laporans))
                                        @forelse($laporans as $tiket)
                                        <tr class="{{ $tiket->status_laporan == 'Menunggu' && $tiket->tingkat_kendala == 'Ringan' ? 'bg-warning-subtle bg-opacity-25' : '' }}">
                                            <td class="px-4">
                                                <div class="fw-bold small">{{ $tiket->created_at->format('d M Y') }}</div>
                                                <div class="text-muted small">{{ $tiket->created_at->format('H:i') }} WIB</div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><i class="bi bi-car-front-fill me-1 text-primary"></i> {{ $tiket->unit->nopol ?? 'Unit Terhapus' }}</div>
                                                <small class="text-muted">{{ $tiket->unit->nama_mobil ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-dark border"><i class="bi bi-person-badge me-1"></i> {{ $tiket->instruktur->nama_lengkap ?? 'Admin Cabang' }}</span>
                                            </td>
                                            <td>
                                                <div class="mb-1">
                                                    @if($tiket->tingkat_kendala == 'Berat') 
                                                        <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.75rem;"><i class="bi bi-exclamation-diamond-fill me-1"></i>Darurat (Berat)</span>
                                                    @else 
                                                        <span class="badge bg-warning text-dark rounded-pill px-2" style="font-size: 0.75rem;"><i class="bi bi-info-circle-fill me-1"></i>Perbaikan (Ringan)</span> 
                                                    @endif
                                                </div>
                                                <div class="small text-truncate" style="max-width: 280px;" title="{{ $tiket->deskripsi }}">
                                                    "{{ $tiket->deskripsi }}"
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <!-- LOGIC FILTER OTORITAS ADMIN -->
                                                @if($tiket->tingkat_kendala == 'Berat')
                                                    <!-- KENDALA BERAT: DIKUNCI / WEWENANG MANAGEMENT -->
                                                    <span class="badge bg-secondary-subtle text-muted border px-3 py-2" title="Laporan kendala berat otomatis ditangani oleh Management Pusat">
                                                        <i class="bi bi-lock-fill me-1 text-danger"></i>Wewenang Management
                                                    </span>
                                                @else
                                                    <!-- KENDALA RINGAN: BISA DITANGANI ADMIN CABANG -->
                                                    @if($tiket->status_laporan == 'Menunggu')
                                                        <button class="btn btn-danger btn-sm rounded-pill fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalProsesTiketAdmin{{ $tiket->id }}">
                                                            <i class="bi bi-wrench me-1"></i>Tindak Lanjuti
                                                        </button>
                                                    @elseif($tiket->status_laporan == 'Diproses')
                                                        <button class="btn btn-primary btn-sm rounded-pill fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#modalProsesTiketAdmin{{ $tiket->id }}">
                                                            <i class="bi bi-arrow-repeat me-1"></i>Update Progres
                                                        </button>
                                                    @else
                                                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-all me-1"></i>Selesai</span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted fst-italic">
                                                <i class="bi bi-shield-check display-5 d-block mb-2 text-success opacity-50"></i>
                                                Belum ada laporan kendala unit di cabang ini. Kondisi armada aman.
                                            </td>
                                        </tr>
                                        @endforelse
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 🔥 SEMUA MODAL DITARUH DI LUAR TABEL (MENJAGA STRUKTUR DOM TETAP STABIL) 🔥 -->
    <!-- ========================================================================= -->

    <!-- 1. MODAL RESET PASSWORD INSTRUKTUR -->
    @foreach($instructors as $ins)
    <div class="modal fade" id="resetModal{{ $ins->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-lock-fill me-2"></i>Reset Password Instruktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/admin/instruktur/'.$ins->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body p-4 text-start">
                        <div class="alert alert-light border border-warning text-dark small mb-4">
                            Anda hanya diberikan akses untuk mereset password akun <strong>{{ $ins->nama_lengkap }}</strong>. Penambahan atau perubahan data diri hanya dapat dilakukan oleh Management Pusat.
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Password Baru</label>
                            <input type="password" name="password" class="form-control shadow-sm" placeholder="Masukkan password baru (min. 6 karakter)" required minlength="6">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 fw-bold rounded-pill shadow-sm">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- 2. MODAL BUAT LAPORAN KENDALA OLEH ADMIN -->
    <div class="modal fade" id="modalBuatLaporanAdmin" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                <form action="{{ url('/admin/laporan-unit') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-warning border-0">
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-exclamation-triangle-fill me-2"></i>Buat Laporan Kendala Armada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Pilih Armada Kendaraan</label>
                            <select name="unit_id" class="form-select shadow-sm" required>
                                <option value="">-- Pilih Unit Mobil Cabang --</option>
                                @if(isset($units))
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}">{{ $u->nama_mobil }} ({{ $u->nopol ?? 'Nopol Kosong' }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Tingkat Kendala</label>
                            <select name="tingkat_kendala" class="form-select shadow-sm" required>
                                <option value="Ringan" selected>Ringan (Dapat ditangani langsung oleh Admin Cabang)</option>
                                <option value="Berat">Berat / Darurat (Otomatis ditangani oleh Management Pusat)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Deskripsi Kendala</label>
                            <textarea name="deskripsi" class="form-control bg-light shadow-sm" rows="4" placeholder="Detail kendala yang ditemukan pada kendaraan..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. MODAL PROSES TIKET LAPORAN RINGAN OLEH ADMIN -->
    @if(isset($laporans))
        @foreach($laporans as $tiket)
            @if($tiket->tingkat_kendala == 'Ringan')
            <div class="modal fade" id="modalProsesTiketAdmin{{ $tiket->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg text-start">
                        <form action="{{ url('/admin/laporan-unit/'.$tiket->id) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-header bg-dark text-white border-0">
                                <h5 class="fw-bold mb-0"><i class="bi bi-tools me-2"></i>Penanganan Kendala Ringan</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                    <div class="small fw-bold text-muted mb-1">Unit: {{ $tiket->unit->nama_mobil ?? '-' }} ({{ $tiket->unit->nopol ?? '-' }})</div>
                                    <div class="small text-dark fst-italic">"{{ $tiket->deskripsi }}"</div>
                                </div>
                                
                                <div class="mb-0">
                                    <label class="small fw-bold text-muted mb-1">Status Penanganan Admin</label>
                                    <select name="status_laporan" class="form-select shadow-sm fw-bold">
                                        <option value="Menunggu" {{ $tiket->status_laporan == 'Menunggu' ? 'selected' : '' }}>🔴 Menunggu Perbaikan</option>
                                        <option value="Diproses" {{ $tiket->status_laporan == 'Diproses' ? 'selected' : '' }}>🟡 Sedang Diperbaiki / Diproses</option>
                                        <option value="Selesai" {{ $tiket->status_laporan == 'Selesai' ? 'selected' : '' }}>🟢 Perbaikan Ringan Selesai</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer border-0 bg-light">
                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold">Update Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>