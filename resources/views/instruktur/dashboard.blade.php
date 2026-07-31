<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Kerja Instruktur - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-bg: #f8fafc; --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .header-top { background: #fff; padding: 1.25rem 2rem; border-bottom: 1px solid #e2e8f0; margin-bottom: 2rem; }
        .card-custom { border: none; border-radius: 24px; padding: 1.75rem; background: #fff; box-shadow: var(--sj-card-shadow); }
        .welcome-box { background: linear-gradient(135deg, #0dcaf0 0%, #007bb5 100%); border-radius: 24px; padding: 2.5rem; color: #fff; margin-bottom: 2rem; box-shadow: 0 10px 20px rgba(13, 202, 240, 0.15); }
        .accordion-button:not(.collapsed) { background-color: #e0f8fd; color: #007bb5; font-weight: bold; box-shadow: none; border-left: 4px solid #007bb5; }
        .accordion-item { border-radius: 12px !important; overflow: hidden; margin-bottom: 10px; border: 1px solid #e9ecef; }
    </style>
</head>
<body>

    <!-- MOBILE NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm d-md-none sticky-top px-3">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <h6 class="fw-bold text-info mb-0"><i class="bi bi-steering me-2"></i>Portal Instruktur</h6>
            <button class="btn btn-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebarInstruktur">
                <i class="bi bi-list fs-3"></i>
            </button>
        </div>
    </nav>

    <div class="d-flex">
        @include('instruktur.sidebar')

        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
            
            <div class="header-top d-none d-md-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark">Ruang Kerja & Operasional</h5>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-info text-dark rounded-pill">{{ $user->branch->nama_cabang ?? 'Belum Diatur' }}</span>
                    <div class="vr mx-2"></div>
                    <span class="small fw-bold">{{ $user->nama_lengkap }}</span>
                </div>
            </div>

            <div class="p-3 p-md-4 pb-4">
                @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 fw-bold"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div> @endif
                
                @if(!$user->branch_id)
                <div class="alert alert-danger shadow-sm rounded-4 border-0 mb-4 p-4 d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill display-5 opacity-50"></i>
                    <div><h5 class="fw-bold mb-1">Penugasan Kosong!</h5><p class="mb-0 small">Hubungi Admin/Management untuk penempatan cabang Anda.</p></div>
                </div>
                @endif

                <div class="welcome-box position-relative overflow-hidden p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-9 position-relative" style="z-index: 2;">
                            <h3 class="fw-bold mb-2">Semangat Mengajar, {{ explode(' ', $user->nama_lengkap)[0] }}!</h3>
                            <p class="opacity-75 mb-0 small d-none d-md-block">Periksa jadwal latihan Anda. Jika ada masalah dengan mobil operasional, segera laporkan ke Management.</p>
                            
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <button type="button" class="btn btn-light text-dark fw-bold rounded-pill px-4 py-2 shadow-sm flex-grow-1 flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#panduanInstrukturModal">
                                    <i class="bi bi-info-circle-fill me-2 text-info"></i> Panduan
                                </button>
                                <button type="button" class="btn btn-danger fw-bold rounded-pill px-4 py-2 shadow-sm flex-grow-1 flex-md-grow-0" data-bs-toggle="modal" data-bs-target="#modalLaporUnit">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Lapor Kendala
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3 text-end position-relative d-none d-md-block" style="z-index: 2;"><i class="bi bi-car-front-fill display-1 fw-bold opacity-25 m-0"></i></div>
                    </div>
                </div>

                <!-- TABEL JADWAL (RESPONSIF) -->
                <div class="card card-custom border-top border-info border-4 mb-4 p-3 p-md-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-card-checklist me-2 text-info"></i>Daftar Siswa & Jadwal</h6>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th class="small text-muted">Jadwal</th>
                                    <th class="small text-muted">Data Siswa</th>
                                    <th class="small text-muted">Unit Kendaraan</th>
                                    <th class="small text-muted text-center">Status</th>
                                    <th class="small text-muted text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwals as $jadwal)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ date('d M Y', strtotime($jadwal->tanggal)) }}</div>
                                        <div class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $jadwal->jam_mulai }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $jadwal->user->nama_lengkap ?? 'Dihapus' }}</div>
                                        <div class="small text-muted"><i class="bi bi-whatsapp text-success me-1"></i>{{ $jadwal->user->no_telp ?? '-' }}</div>
                                    </td>
                                    <td>
                                        @if($jadwal->unit)
                                            <div class="fw-bold text-dark"><i class="bi bi-car-front-fill text-info me-1"></i> {{ $jadwal->unit->nopol ?? $jadwal->unit->nama_unit }}</div>
                                        @else
                                            <span class="badge bg-light text-secondary border"><i class="bi bi-hourglass-split me-1"></i>Menunggu Plotting</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($jadwal->status == 'Selesai') <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                        @elseif($jadwal->status == 'Disetujui') <span class="badge bg-primary rounded-pill px-3">Terjadwal</span>
                                        @else <span class="badge bg-warning text-dark rounded-pill px-3">{{ $jadwal->status }}</span> @endif
                                    </td>
                                    <td class="text-center">
                                        @if($jadwal->status == 'Selesai')
                                            <button class="btn btn-outline-success btn-sm rounded-pill fw-bold px-3 shadow-sm" disabled><i class="bi bi-check-all me-1"></i>Selesai</button>
                                        @elseif($jadwal->status == 'Disetujui')
                                            <button class="btn btn-info text-white btn-sm rounded-pill fw-bold shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#evaluasiModal{{ $jadwal->id }}">Evaluasi</button>
                                        @else
                                            <span class="text-muted small fst-italic">Pending</span>
                                        @endif
                                    </td>
                                </tr>

                                @if($jadwal->status == 'Disetujui')
                                <div class="modal fade" id="evaluasiModal{{ $jadwal->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered px-2">
                                        <div class="modal-content border-0 shadow-lg rounded-4">
                                            <div class="modal-header bg-info text-white border-0"><h6 class="fw-bold mb-0">Evaluasi Sesi Latihan</h6><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                                            <form action="{{ url('/instruktur/jadwal/evaluasi/'.$jadwal->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-light border small mb-3">
                                                        Siswa: <strong>{{ $jadwal->user->nama_lengkap }}</strong><br>
                                                        Mobil: <strong>{{ $jadwal->unit->nopol ?? 'Belum ada data' }}</strong>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold mb-2">Catatan Kekurangan & Saran</label>
                                                        <textarea name="catatan_evaluasi" class="form-control bg-light shadow-sm" rows="4" placeholder="Contoh: Penguasaan kopling masih kurang..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0"><button type="submit" class="btn btn-info text-white w-100 fw-bold rounded-pill shadow-sm">Simpan & Selesai</button></div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr><td colspan="5" class="text-center text-muted py-5">Belum ada jadwal.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIWAYAT LAPORAN KENDARAAN -->
                <div class="card card-custom p-3 p-md-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2 text-secondary"></i>Riwayat Laporan Kendala Kendaraan Anda</h6>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0 text-nowrap">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th>Tanggal Lapor</th>
                                    <th>Unit Mobil</th>
                                    <th>Tingkat Kendala</th>
                                    <th>Status Penanganan</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($laporans ?? [] as $lapor)
                                <tr>
                                    <td class="text-muted">{{ $lapor->created_at->format('d M Y') }}</td>
                                    <td class="fw-bold">{{ $lapor->unit->nopol ?? 'Unit ID: '.$lapor->unit_id }}</td>
                                    <td>
                                        @if($lapor->tingkat_kendala == 'Berat') <span class="badge bg-danger-subtle text-danger border">Berat (Darurat)</span>
                                        @else <span class="badge bg-warning-subtle text-warning border">Ringan (Info)</span> @endif
                                    </td>
                                    <td>
                                        @if($lapor->status_laporan == 'Menunggu') <span class="badge bg-secondary rounded-pill">Menunggu</span>
                                        @elseif($lapor->status_laporan == 'Diproses') <span class="badge bg-primary rounded-pill">Diproses</span>
                                        @else <span class="badge bg-success rounded-pill">Selesai</span> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center text-muted py-4 fst-italic">Belum ada riwayat pelaporan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL FORM LAPOR KENDALA UNIT -->
    <div class="modal fade" id="modalLaporUnit" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered px-2">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white border-0">
                    <h6 class="modal-title fw-bold"><i class="bi bi-tools me-2"></i>Form Laporan Kendala</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/instruktur/laporan-unit') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <div class="alert alert-warning border-0 small shadow-sm mb-4">
                            Laporan akan langsung diteruskan ke Management Pusat PT. Satria Jayanti.
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small mb-2 text-muted">Pilih Kendaraan Bermasalah</label>
                            <select name="unit_id" class="form-select shadow-sm" required>
                                <option value="">-- Pilih Mobil --</option>
                                @foreach($unitsAvailable ?? [] as $u)
                                    <option value="{{ $u->id }}">{{ $u->nopol ?? $u->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small mb-2 text-muted">Tingkat Kendala</label>
                            <select name="tingkat_kendala" class="form-select shadow-sm" required>
                                <option value="Ringan">Ringan (Ban kempes, lecet, dll)</option>
                                <option value="Berat">Berat (Mogok, rem blong, dll)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold small mb-2 text-muted">Deskripsi Kendala</label>
                            <textarea name="deskripsi" class="form-control shadow-sm bg-light" rows="4" placeholder="Jelaskan detail kendala..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm w-100">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL SOP -->
    <div class="modal fade" id="panduanInstrukturModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable px-2">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-info text-dark border-0 p-4">
                    <h6 class="modal-title fw-bold"><i class="bi bi-journal-bookmark-fill me-2"></i> Panduan Penggunaan</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="accordion" id="faqInstruktur">
                        <div class="accordion-item shadow-sm border-0 mb-3 rounded-3">
                            <h2 class="accordion-header"><button class="accordion-button fw-bold text-dark bg-white rounded-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">1. Evaluasi Siswa</button></h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqInstruktur"><div class="accordion-body text-muted small border-top">Klik tombol <strong>Evaluasi</strong> pada kolom aksi setelah sesi selesai. Menyimpan evaluasi akan menyelesaikan sesi.</div></div>
                        </div>
                        <div class="accordion-item shadow-sm border-0 mb-3 rounded-3">
                            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold text-dark bg-white rounded-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">2. Pengajuan Cuti</button></h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqInstruktur"><div class="accordion-body text-muted small border-top">Gunakan menu <strong>Pengajuan Cuti</strong>. Sistem otomatis memblokir nama Anda agar Admin tidak salah jadwal.</div></div>
                        </div>
                        <div class="accordion-item shadow-sm border-0 mb-3 rounded-3">
                            <h2 class="accordion-header"><button class="accordion-button collapsed fw-bold text-dark bg-white rounded-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">3. Lapor Mobil Bermasalah</button></h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqInstruktur"><div class="accordion-body text-muted small border-top">Gunakan tombol merah <strong>Lapor Kendala</strong> di beranda. Laporan ini langsung termonitor oleh Manajemen Pusat.</div></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-info text-white w-100 rounded-pill fw-bold py-2 shadow-sm" data-bs-dismiss="modal">Saya Paham</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>