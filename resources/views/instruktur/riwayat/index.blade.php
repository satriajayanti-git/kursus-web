<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Siswa - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-bg: #f8fafc; --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; }
        .accordion-button:not(.collapsed) { background-color: #e0f8fd; color: #007bb5; font-weight: bold; box-shadow: none; border-left: 4px solid #007bb5; }
        .accordion-button:focus { box-shadow: none; }
        .accordion-item { border-radius: 12px !important; overflow: hidden; margin-bottom: 10px; border: 1px solid #e9ecef; }
        .card-custom { border: none; border-radius: 24px; padding: 1.5rem; background: #fff; box-shadow: var(--sj-card-shadow); }
        
        /* Stempel/Badge Khusus Kursus Selesai */
        .stamp-completed { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; padding: 5px 12px; border: 1px solid #198754; color: #198754; background: #d1e7dd; border-radius: 50px; font-weight: 800; }
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

        <div class="flex-grow-1 p-3 p-md-4" style="height: 100vh; overflow-y: auto;">
            <div class="mb-4">
                <h4 class="fw-bold text-dark"><i class="bi bi-journal-text me-2 text-info"></i>Data & Riwayat Evaluasi Siswa</h4>
                <p class="text-muted small">Cek progres latihan siswa yang sedang aktif atau cari data siswa lama.</p>
            </div>

            <!-- FITUR SEARCH -->
            <form action="{{ url('/instruktur/riwayat') }}" method="GET" class="mb-4">
                <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                    <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-0 py-3 bg-white" placeholder="Cari berdasarkan Nama atau ID Siswa (Contoh: SJN-001)..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-info text-white px-4 fw-bold">Cari Data</button>
                </div>
            </form>

            <div class="card card-custom">
                @if($groupedSiswa->count() > 0)
                    <div class="accordion" id="accordionSiswa">
                        @foreach($groupedSiswa as $userId => $sesiSiswa)
                            @php 
                                $siswa = $sesiSiswa->first()->user; 
                                
                                // Kalkulasi Progres Latihan
                                $totalLatihan = $sesiSiswa->count();
                                // Mengambil target pertemuan paket (Bisa menyesuaikan nama field di db lu, asumsi: pertemuan/jumlah_pertemuan)
                                $targetSesi = $siswa->package->pertemuan ?? $siswa->package->jumlah_pertemuan ?? 1; 
                                $isCompleted = $totalLatihan >= $targetSesi;
                            @endphp
                            
                            <div class="accordion-item shadow-sm border-0 mb-3 bg-light">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white rounded-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#siswa{{ $userId }}">
                                        <div class="d-flex flex-column w-100 pe-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bolder text-dark fs-6">{{ $siswa->nama_lengkap ?? 'Tanpa Nama' }}</span>
                                                <!-- STEMPEL KURSUS SELESAI -->
                                                @if($isCompleted)
                                                    <div class="stamp-completed shadow-sm"><i class="bi bi-patch-check-fill me-1"></i>Selesai</div>
                                                @else
                                                    <span class="badge bg-warning text-dark rounded-pill shadow-sm" style="font-size: 0.65rem;">Sedang Berjalan</span>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex flex-wrap gap-2 text-muted mt-1" style="font-size: 0.75rem; font-weight: 600;">
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill">
                                                    <i class="bi bi-person-vcard me-1"></i>{{ $siswa->id_siswa ?? 'ID: '.$siswa->id }}
                                                </span>
                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill text-truncate" style="max-width: 150px;">
                                                    <i class="bi bi-box-seam me-1"></i>{{ $siswa->package->nama_package ?? 'Paket' }}
                                                </span>
                                                <span class="badge {{ $isCompleted ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} rounded-pill">
                                                    <i class="bi bi-bar-chart-line-fill me-1"></i>Sesi: {{ $totalLatihan }} / {{ $targetSesi }}
                                                </span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="siswa{{ $userId }}" class="accordion-collapse collapse" data-bs-parent="#accordionSiswa">
                                    <div class="accordion-body p-3">
                                        
                                        <!-- Looping Riwayat Per Sesi (Terbaru ke Terlama) -->
                                        <div class="d-flex flex-column gap-3">
                                            @foreach($sesiSiswa->sortByDesc('tanggal') as $sesi)
                                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                                                <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                                    <div>
                                                        <span class="fw-bold small d-block text-primary"><i class="bi bi-calendar2-check-fill me-2"></i>{{ date('d M Y', strtotime($sesi->tanggal)) }}</span>
                                                    </div>
                                                    <span class="badge bg-light text-dark border">{{ $sesi->jam_mulai }} WIB</span>
                                                </div>
                                                <div class="small mb-2 d-flex align-items-center">
                                                    <span class="text-muted me-2">Instruktur:</span>
                                                    <span class="fw-bold text-dark border-bottom border-dark">{{ $sesi->instructor->nama_lengkap ?? 'Instruktur' }}</span>
                                                </div>
                                                <div class="bg-primary bg-opacity-10 p-3 rounded-3 border-start border-primary border-4 small text-dark mt-2">
                                                    <strong>Catatan Latihan:</strong><br>
                                                    <span class="fst-italic">"{{ $sesi->catatan_evaluasi ?? 'Tidak ada catatan khusus pada sesi ini.' }}"</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-search display-1 text-muted opacity-25 d-block mb-3"></i>
                        <h5 class="fw-bold text-dark">Data Tidak Ditemukan</h5>
                        <p class="text-muted small">
                            @if(!empty($search))
                                Tidak ada siswa dengan nama atau ID "<strong>{{ $search }}</strong>" yang pernah menyelesaikan latihan.
                            @else
                                Belum ada riwayat siswa yang menyelesaikan latihan di cabang ini.
                            @endif
                        </p>
                        @if(!empty($search))
                            <a href="{{ url('/instruktur/riwayat') }}" class="btn btn-outline-info rounded-pill fw-bold mt-2 px-4">Reset Pencarian</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>