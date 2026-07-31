<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Jadwal - CMS Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-custom { border-radius: 15px; overflow: hidden; border: none; }
        .monitor-box { background: #f8f9fa; border-left: 4px solid #198754; border-radius: 8px; padding: 15px; }
        .btn-ubah { font-size: 0.8rem; letter-spacing: 0.5px; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">Plotting Jadwal, Instruktur & Unit</h3>
                    <p class="text-muted small">Atur jadwal siswa, tugaskan instruktur, plotting unit kendaraan, dan respon permintaan reschedule.</p>
                </div>
                <button class="btn btn-success fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahJadwal">
                    <i class="bi bi-calendar-plus-fill me-2"></i>Buat Jadwal Manual
                </button>
            </div>

            <div class="card border-0 shadow-sm mb-4 p-3 rounded-4">
                <form action="{{ url('/admin/jadwal') }}" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="status" value="{{ $status ?? 'Pending' }}">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik nama siswa..." value="{{ $search ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Cari</button>
                </form>
            </div>

            @if(session('success')) <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger border-0 shadow-sm mb-4 fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}</div> @endif
            
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4 fw-bold">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @php $status = $status ?? 'Pending'; @endphp
            <ul class="nav nav-pills mb-4 gap-2">
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'Pending' ? 'active shadow-sm fw-bold' : 'bg-white border text-muted' }} rounded-pill px-4" 
                       href="{{ url('/admin/jadwal?status=Pending' . ($search ? '&search='.$search : '')) }}">
                        <i class="bi bi-hourglass-split me-1"></i> Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'Disetujui' ? 'active shadow-sm fw-bold' : 'bg-white border text-muted' }} rounded-pill px-4" 
                       href="{{ url('/admin/jadwal?status=Disetujui' . ($search ? '&search='.$search : '')) }}">
                        <i class="bi bi-calendar-check me-1"></i> Aktif / Berjalan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'Selesai' ? 'active shadow-sm fw-bold' : 'bg-white border text-muted' }} rounded-pill px-4" 
                       href="{{ url('/admin/jadwal?status=Selesai' . ($search ? '&search='.$search : '')) }}">
                        <i class="bi bi-check-all me-1"></i> Selesai Latihan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $status == 'Dibatalkan' ? 'active shadow-sm fw-bold' : 'bg-white border text-muted' }} rounded-pill px-4" 
                       href="{{ url('/admin/jadwal?status=Dibatalkan' . ($search ? '&search='.$search : '')) }}">
                        <i class="bi bi-x-circle me-1"></i> Dibatalkan
                    </a>
                </li>
                <li class="nav-item ms-auto">
                    <a class="nav-link {{ $status == 'all' ? 'active shadow-sm fw-bold bg-dark text-white' : 'bg-white border text-muted' }} rounded-pill px-4" 
                       href="{{ url('/admin/jadwal?status=all' . ($search ? '&search='.$search : '')) }}">
                        <i class="bi bi-collection me-1"></i> Semua Data
                    </a>
                </li>
            </ul>

            <div class="card table-custom shadow-sm">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Siswa & Paket</th>
                            <th class="py-3">Waktu Latihan (1 Jam)</th>
                            <th class="py-3">Instruktur & Unit</th>
                            <th class="py-3 text-center">Status & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwals as $j)
                        <tr>
                            <td class="px-4">
                                <div class="fw-bold">{{ $j->user->nama_lengkap ?? 'Dihapus' }}</div>
                                <span class="badge bg-light text-primary border small">{{ $j->user->package->nama_package ?? 'Tanpa Paket' }} ({{ $j->user->package->transmisi ?? 'Manual' }})</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold small"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ date('d M Y', strtotime($j->tanggal)) }}</div>
                                        <small class="text-muted"><i class="bi bi-clock me-2"></i>{{ $j->jam_mulai }} WIB</small>
                                    </div>
                                    <button class="btn btn-link btn-sm ms-3 text-decoration-none fw-bold btn-ubah" data-bs-toggle="modal" data-bs-target="#modalReschedule{{ $j->id }}">
                                        <i class="bi bi-pencil-square"></i> Ubah
                                    </button>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    @if($j->instructor)
                                        <span class="badge bg-primary rounded-pill px-2 py-1 shadow-sm" style="font-size: 0.75rem;"><i class="bi bi-person-badge me-1"></i>{{ $j->instructor->nama_lengkap }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 0.75rem;"><i class="bi bi-hourglass-split me-1"></i>Belum Ada Instruktur</span>
                                    @endif
                                </div>
                                <div>
                                    @if($j->unit)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.75rem;"><i class="bi bi-car-front-fill me-1"></i>{{ $j->unit->nopol ?? $j->unit->nama_mobil ?? 'Unit ID: '.$j->unit_id }}</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1" style="font-size: 0.75rem;"><i class="bi bi-exclamation-circle me-1"></i>Belum Plot Unit</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                @if($j->status == 'Selesai')
                                    <button class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#evalModal{{ $j->id }}">
                                        <i class="bi bi-check-all me-1"></i>Selesai
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#evalModal{{ $j->id }}">
                                        <i class="bi bi-gear-fill me-1"></i>Kelola
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted d-block mb-3"></i>
                                <p class="text-muted mb-0">Belum ada data jadwal pada kategori ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 🔥 MODAL BUAT JADWAL MANUAL (OFFLINE) -->
    <div class="modal fade" id="modalTambahJadwal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus-fill me-2"></i>Buat & Plotting Jadwal Manual</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/admin/jadwal') }}" method="POST" id="formTambahJadwal">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <div class="alert alert-light border border-success-subtle small mb-4 text-dark shadow-sm">
                            <i class="bi bi-info-circle-fill text-success me-2"></i> 
                            Sistem mendeteksi <strong>pindah transmisi otomatis</strong> dan akan memberikan tagihan / peringatan sesuai kebijakan.
                        </div>

                        <div class="row">
                            <!-- 🔥 FIX: Inject data-transmisi ke Select Siswa -->
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Pilih Siswa (Sudah Lunas)</label>
                                <select name="user_id" class="form-select shadow-sm" required>
                                    <option value="">-- Cari & Pilih Siswa --</option>
                                    @foreach($siswas as $siswa)
                                        <option value="{{ $siswa->id }}" data-transmisi="{{ $siswa->package->transmisi ?? 'Manual' }}">
                                            {{ $siswa->nama_lengkap }} ({{ $siswa->id_siswa ?? 'ID' }}) - {{ $siswa->package->nama_package ?? 'Paket' }} [{{ $siswa->package->transmisi ?? 'Manual' }}]
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Tanggal Latihan</label>
                                <input type="date" name="tanggal" class="form-control shadow-sm" min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Jam Latihan (1 Jam)</label>
                                <select name="jam_mulai" class="form-select shadow-sm" required>
                                    <option value="">-- Pilih Jam Mulai --</option>
                                    <option value="08:00">08:00 - 09:00 WIB</option>
                                    <option value="09:00">09:00 - 10:00 WIB</option>
                                    <option value="10:00">10:00 - 11:00 WIB</option>
                                    <option value="11:00">11:00 - 12:00 WIB</option>
                                    <option value="13:00">13:00 - 14:00 WIB</option>
                                    <option value="14:00">14:00 - 15:00 WIB</option>
                                    <option value="15:00">15:00 - 16:00 WIB</option>
                                    <option value="16:00">16:00 - 17:00 WIB</option>
                                    <option value="17:00">17:00 - 18:00 WIB</option>
                                    <option value="19:00">19:00 - 20:00 WIB</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Tugaskan Instruktur</label>
                                <select name="instructor_id" id="instructor_id_new" class="form-select shadow-sm instructor-select" data-jadwal-id="new" required>
                                    <option value="">-- Pilih Instruktur --</option>
                                    @foreach($instructors as $ins)
                                        <option value="{{ $ins->id }}">✅ {{ $ins->nama_lengkap }} ({{ $ins->tipe_instruktur ?? 'Tetap' }} - {{ $ins->kategori_transmisi }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 🔥 FIX: Inject data-transmisi ke Select Unit -->
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Plot Unit Kendaraan</label>
                                <select name="unit_id" id="unit_id_new" class="form-select shadow-sm unit-select" required>
                                    <option value="">-- Pilih Unit Kendaraan --</option>
                                    <optgroup label="🟢 TRANSMISI MATIC" style="background: #e0f8e9;">
                                        @foreach($units->where('transmisi', 'Matic') as $u)
                                            <option value="{{ $u->id }}" data-transmisi="Matic">🚗 {{ $u->nopol ?? $u->nama_mobil }} - Matic ({{ $u->status_kepemilikan }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="⚫ TRANSMISI MANUAL" style="background: #e9ecef;">
                                        @foreach($units->where('transmisi', 'Manual') as $u)
                                            <option value="{{ $u->id }}" data-transmisi="Manual">🚗 {{ $u->nopol ?? $u->nama_mobil }} - Manual ({{ $u->status_kepemilikan }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="🔵 BISA MANUAL & MATIC">
                                        @foreach($units->where('transmisi', 'Manual & Matic') as $u)
                                            <option value="{{ $u->id }}" data-transmisi="Manual & Matic">🚗 {{ $u->nopol ?? $u->nama_mobil }} - Bebas ({{ $u->status_kepemilikan }})</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <small id="unitNotif_new" class="text-muted mt-1 d-block fw-bold" style="font-size: 0.75rem;"></small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($jadwals as $j)
        <!-- MODAL RESCHEDULE TETAP -->
        <div class="modal fade" id="modalReschedule{{ $j->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-dark text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-calendar-range me-2"></i>Ubah Jadwal Siswa</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ url('/admin/jadwal/reschedule/'.$j->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 text-start">
                            <p class="small text-muted mb-4">Atur ulang tanggal dan jam untuk siswa: <strong>{{ $j->user->nama_lengkap ?? 'Anonim' }}</strong></p>
                            <div class="mb-3">
                                <label class="small fw-bold mb-1">Tanggal Latihan Baru</label>
                                <input type="date" name="tanggal" class="form-control shadow-sm" value="{{ $j->tanggal }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold mb-1">Pilih Jam Baru (1 Jam)</label>
                                <select name="jam_mulai" class="form-select shadow-sm" required>
                                    <option value="08:00" {{ $j->jam_mulai == '08:00' ? 'selected' : '' }}>08:00 - 09:00 WIB</option>
                                    <option value="09:00" {{ $j->jam_mulai == '09:00' ? 'selected' : '' }}>09:00 - 10:00 WIB</option>
                                    <option value="10:00" {{ $j->jam_mulai == '10:00' ? 'selected' : '' }}>10:00 - 11:00 WIB</option>
                                    <option value="11:00" {{ $j->jam_mulai == '11:00' ? 'selected' : '' }}>11:00 - 12:00 WIB</option>
                                    <option value="13:00" {{ $j->jam_mulai == '13:00' ? 'selected' : '' }}>13:00 - 14:00 WIB</option>
                                    <option value="14:00" {{ $j->jam_mulai == '14:00' ? 'selected' : '' }}>14:00 - 15:00 WIB</option>
                                    <option value="15:00" {{ $j->jam_mulai == '15:00' ? 'selected' : '' }}>15:00 - 16:00 WIB</option>
                                    <option value="16:00" {{ $j->jam_mulai == '16:00' ? 'selected' : '' }}>16:00 - 17:00 WIB</option>
                                    <option value="17:00" {{ $j->jam_mulai == '17:00' ? 'selected' : '' }}>17:00 - 18:00 WIB</option>
                                    <option value="19:00" {{ $j->jam_mulai == '19:00' ? 'selected' : '' }}>19:00 - 20:00 WIB</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Jadwal Baru</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 🔥 MODAL KELOLA & PLOTTING -->
        <div class="modal fade" id="evalModal{{ $j->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title fw-bold"><i class="bi bi-diagram-2 me-2"></i>Plotting Sesi Latihan & Unit</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Tambah atribut identifier untuk JS -->
                    <form action="{{ url('/admin/jadwal/update-full/'.$j->id) }}" method="POST" class="form-update-jadwal" data-transmisi-siswa="{{ $j->user->package->transmisi ?? 'Manual' }}">
                        @csrf @method('PUT')
                        <div class="modal-body p-4 text-start">
                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Status Jadwal</label>
                                <select name="status" class="form-select shadow-sm" required>
                                    <option value="Pending" {{ $j->status == 'Pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                    <option value="Disetujui" {{ $j->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                                    <option value="Selesai" {{ $j->status == 'Selesai' ? 'selected' : '' }}>Selesai Latihan</option>
                                    <option value="Dibatalkan" {{ $j->status == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Plot Instruktur</label>
                                <select name="instructor_id" id="instructor_id_{{ $j->id }}" class="form-select shadow-sm instructor-select" data-jadwal-id="{{ $j->id }}" required>
                                    <option value="">-- Tugaskan Instruktur --</option>
                                    @foreach($instructors as $ins)
                                        @php $transmisiPaket = $j->user->package->transmisi ?? 'Manual'; @endphp
                                        
                                        @if(in_array($ins->kategori_transmisi, [$transmisiPaket, 'Manual & Matic']) || $transmisiPaket == 'Manual & Matic')
                                            
                                            @if($ins->isCuti($j->tanggal))
                                                <option disabled class="text-danger fw-bold bg-danger-subtle">❌ {{ $ins->nama_lengkap }} (Sedang Cuti)</option>
                                            @elseif($ins->isSibuk($j->tanggal, $j->jam_mulai, $j->id))
                                                @if($j->instructor_id == $ins->id)
                                                    <option value="{{ $ins->id }}" selected class="fw-bold text-success">✅ {{ $ins->nama_lengkap }} (Tugas Saat Ini)</option>
                                                @else
                                                    <option disabled class="text-warning fw-bold bg-warning-subtle">⚠️ {{ $ins->nama_lengkap }} (Jadwal Bentrok)</option>
                                                @endif
                                            @else
                                                <option value="{{ $ins->id }}" {{ $j->instructor_id == $ins->id ? 'selected' : '' }}>
                                                    ✅ {{ $ins->nama_lengkap }} ({{ $ins->tipe_instruktur ?? 'Tetap' }})
                                                </option>
                                            @endif
                                        @else
                                            <option disabled class="text-muted">🚫 {{ $ins->nama_lengkap }} (Khusus {{ $ins->kategori_transmisi }})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Inject data-transmisi ke Option -->
                            <div class="mb-3">
                                <label class="fw-bold small mb-2 text-muted text-uppercase">Plot Unit Kendaraan</label>
                                <select name="unit_id" id="unit_id_{{ $j->id }}" class="form-select shadow-sm unit-select" required>
                                    <option value="">-- Pilih Unit Kendaraan --</option>
                                    <optgroup label="🟢 TRANSMISI MATIC" style="background: #e0f8e9;">
                                        @foreach($units->where('transmisi', 'Matic') as $u)
                                            <option value="{{ $u->id }}" data-transmisi="Matic" {{ $j->unit_id == $u->id ? 'selected' : '' }}>🚗 {{ $u->nopol ?? $u->nama_mobil }} - Matic ({{ $u->status_kepemilikan }})</option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="⚫ TRANSMISI MANUAL" style="background: #e9ecef;">
                                        @foreach($units->where('transmisi', 'Manual') as $u)
                                            <option value="{{ $u->id }}" data-transmisi="Manual" {{ $j->unit_id == $u->id ? 'selected' : '' }}>🚗 {{ $u->nopol ?? $u->nama_mobil }} - Manual ({{ $u->status_kepemilikan }})</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <small id="unitNotif_{{ $j->id }}" class="text-muted mt-1 d-block fw-bold" style="font-size: 0.75rem;"></small>
                            </div>

                            @if($j->catatan_evaluasi)
                                <div class="monitor-box mt-4 shadow-sm">
                                    <h6 class="small fw-bold text-success mb-2"><i class="bi bi-file-earmark-text-fill me-1"></i>Laporan Instruktur:</h6>
                                    <p class="small text-dark mb-3">"{{ $j->catatan_evaluasi }}"</p>
                                    
                                    @if($j->rating)
                                        <hr class="my-2 border-secondary opacity-25">
                                        <h6 class="small fw-bold text-warning mb-1">
                                            @for($i=0; $i<$j->rating; $i++) <i class="bi bi-star-fill"></i> @endfor
                                        </h6>
                                        <p class="small text-muted mb-0"><i class="bi bi-chat-right-quote-fill me-1"></i>Feedback Siswa: "{{ $j->feedback_siswa }}"</p>
                                    @else
                                        <hr class="my-2 border-secondary opacity-25">
                                        <p class="small text-muted mb-0 fst-italic">Siswa belum memberikan rating.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- 🔥 MODAL KONFIRMASI PINDAH TRANSMISI -->
    <div class="modal fade" id="modalConfirmPindahMatic" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-dark border-0 rounded-top-4">
                    <h6 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Perubahan Transmisi</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-shuffle display-1 text-warning mb-3 d-block opacity-75"></i>
                    <p class="text-muted small mt-2 mb-0 lh-base" id="pindahMaticMessage"></p>
                </div>
                <div class="modal-footer bg-light border-0 p-3 d-flex flex-nowrap rounded-bottom-4">
                    <button type="button" class="btn btn-secondary w-50 rounded-pill fw-bold shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnConfirmPindahMatic" class="btn btn-warning w-50 rounded-pill fw-bold text-dark shadow-sm">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const instrukturData = @json($instructors);

            document.querySelectorAll('.instructor-select').forEach(selectEl => {
                const jadwalId = selectEl.getAttribute('data-jadwal-id');
                const unitSelect = document.getElementById('unit_id_' + jadwalId);
                const notifEl = document.getElementById('unitNotif_' + jadwalId);

                function evaluateInstructor() {
                    if(!unitSelect || !notifEl) return; 

                    const selectedId = selectEl.value;
                    const instruktur = instrukturData.find(i => i.id == selectedId);

                    if (instruktur && instruktur.tipe_instruktur === 'Tetap' && instruktur.unit_pegangan) {
                        unitSelect.value = instruktur.unit_pegangan.id;
                        unitSelect.style.pointerEvents = 'none';
                        unitSelect.style.backgroundColor = '#e9ecef';
                        notifEl.className = 'text-success mt-1 d-block fw-bold';
                        notifEl.innerHTML = '<i class="bi bi-check-circle-fill"></i> Auto-Plot: Mobil Pegangan Tetap (' + (instruktur.unit_pegangan.nopol || instruktur.unit_pegangan.nama_mobil) + ')';
                    } else if (instruktur && instruktur.tipe_instruktur === 'Backup') {
                        unitSelect.style.pointerEvents = 'auto';
                        unitSelect.style.backgroundColor = '#ffffff';
                        notifEl.className = 'text-warning mt-1 d-block fw-bold';
                        notifEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Instruktur Backup: Silakan pilih Unit secara manual sesuai dengan transmisi paket siswa.';
                    } else {
                        unitSelect.style.pointerEvents = 'auto';
                        unitSelect.style.backgroundColor = '#ffffff';
                        notifEl.innerHTML = '';
                    }
                }

                selectEl.addEventListener('change', evaluateInstructor);
                evaluateInstructor();
            });

            // 🔥 LOGIC JS INTERCEPTOR UNTUK CEK SILANG TRANSMISI
            let formToSubmit = null;

            function checkPindahMatic(e, form, transmisiSiswa) {
                const unitSelect = form.querySelector('.unit-select');
                if(!unitSelect) return true;

                const selectedOption = unitSelect.options[unitSelect.selectedIndex];
                if(!selectedOption || !selectedOption.value) return true; 

                const transmisiUnit = selectedOption.getAttribute('data-transmisi');
                let needsPopup = false;
                let popupMessage = "";

                if (transmisiSiswa === 'Manual' && transmisiUnit === 'Matic') {
                    needsPopup = true;
                    popupMessage = "Siswa ini mendaftar dengan paket transmisi manual, jika anda mengubah jadwal latihan ini menggunakan mobil bertransmisi matic maka siswa akan dikenakan biaya charge <strong class='text-danger fs-6'>Rp 20.000</strong>.";
                } else if (transmisiSiswa === 'Matic' && transmisiUnit === 'Manual') {
                    needsPopup = true;
                    popupMessage = "Siswa ini mendaftar dengan paket transmisi matic, apakah anda yakin ingin plotting jadwal latihan sesi ini menggunakan mobil manual? <strong class='text-success fs-6'>(Free / Tanpa Biaya)</strong>.";
                }

                if (needsPopup) {
                    if (!form.dataset.confirmed) {
                        e.preventDefault();
                        formToSubmit = form;
                        document.getElementById('pindahMaticMessage').innerHTML = popupMessage;
                        const modal = new bootstrap.Modal(document.getElementById('modalConfirmPindahMatic'));
                        modal.show();
                        return false;
                    }
                }
                return true;
            }

            const formTambah = document.getElementById('formTambahJadwal');
            if(formTambah) {
                formTambah.addEventListener('submit', function(e) {
                    const siswaSelect = formTambah.querySelector('[name="user_id"]');
                    const selectedSiswa = siswaSelect.options[siswaSelect.selectedIndex];
                    const transmisiSiswa = selectedSiswa ? selectedSiswa.getAttribute('data-transmisi') : 'Manual';
                    checkPindahMatic(e, this, transmisiSiswa);
                });
            }

            document.querySelectorAll('.form-update-jadwal').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const statusSelect = form.querySelector('[name="status"]');
                    if (statusSelect && (statusSelect.value === 'Pending' || statusSelect.value === 'Dibatalkan' || statusSelect.value === 'Batal')) {
                        return true; 
                    }
                    const transmisiSiswa = this.getAttribute('data-transmisi-siswa');
                    checkPindahMatic(e, this, transmisiSiswa);
                });
            });

            document.getElementById('btnConfirmPindahMatic').addEventListener('click', function() {
                if(formToSubmit) {
                    formToSubmit.dataset.confirmed = 'true';
                    formToSubmit.submit();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>