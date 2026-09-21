<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; padding-bottom: 80px; }
        
        .sidebar-desktop { width: 280px; min-height: 100vh; position: sticky; top: 0; background: #fff; z-index: 1000; }
        .menu-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #adb5bd; letter-spacing: 1px; padding-left: 15px; }
        .nav-link-custom { color: #495057; text-decoration: none; padding: 12px 15px; display: flex; align-items: center; border-radius: 12px; margin-bottom: 5px; transition: 0.3s; font-weight: 500; cursor: pointer; border: none; background: transparent; width: 100%; text-align: left; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; }
        
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .eval-note { background: #f1f5f9; border-left: 4px solid #0d6efd; padding: 15px; border-radius: 8px; font-style: italic; }
        .extra-bill { border-left: 4px solid #ffc107; background: #fffbf0; transition: 0.3s; }
        .extra-bill:hover { background: #fff8e1; }
        .rejected-bill { border-left: 4px solid #dc3545 !important; background: #fff5f5 !important; }
        
        .badge-status { padding: 8px 15px; border-radius: 50px; font-weight: 600; font-size: 0.8rem; }
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: #0d6efd; font-weight: bold; box-shadow: none; }
        .accordion-button { border-radius: 15px !important; font-weight: 600; padding: 1rem 1.25rem; }
        .accordion-item { border: 1px solid #e9ecef; border-radius: 15px !important; overflow: hidden; margin-bottom: 1rem; }
        
        /* Checkbox Khusus Tagihan */
        .bill-checkbox { width: 24px; height: 24px; cursor: pointer; }
        
        /* Floating Bottom Bar (Bulk Payment) */
        #floatingPaymentBar {
            position: fixed; bottom: 0; left: 0; width: 100%; background: #fff; box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
            padding: 15px 20px; z-index: 1050; display: none; align-items: center; justify-content: space-between;
            transform: translateY(100%); transition: transform 0.3s ease-out;
        }
        #floatingPaymentBar.show { transform: translateY(0); display: flex; }
        @media (min-width: 768px) { #floatingPaymentBar { left: 280px; width: calc(100% - 280px); } }
    </style>
</head>
<body class="bg-light">

    @php
        $adminPhone = $user->branch->no_telp_admin ?? '';
        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (strpos($cleanPhone, '0') === 0) { $waAdmin = '62' . substr($cleanPhone, 1); } else { $waAdmin = $cleanPhone; }
        $namaSiswa = urlencode(explode(' ', $user->nama_lengkap)[0]);
        $namaPaket = urlencode($user->package->nama_package ?? 'Paket');
        $waUrl = "https://wa.me/{$waAdmin}?text=Halo%20Admin,%20saya%20{$namaSiswa}%20baru%20saja%20mengunggah%20bukti%20pembayaran.%20Mohon%20bantuannya%20untuk%20diverifikasi%20ya.";
        $waUrlBantuan = "https://wa.me/{$waAdmin}?text=Halo%20Admin,%20saya%20{$namaSiswa}%20ingin%20meminta%20bantuan%20terkait%20jadwal%20latihan%20dan%20kursus%20saya.";
    @endphp

    <nav class="navbar navbar-expand-lg bg-white shadow-sm d-md-none sticky-top px-3">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <div class="d-flex align-items-center">
                @if($setting && $setting->logo)
                    <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="30" class="me-2">
                @else
                    <i class="bi bi-steering fs-4 text-primary me-2"></i>
                @endif
                <h6 class="fw-bold text-primary mb-0 m-0">Student Portal</h6>
            </div>
            <button class="btn btn-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidebar">
                <i class="bi bi-list fs-3"></i>
            </button>
        </div>
    </nav>

    <div class="d-flex">
        <div class="sidebar-desktop border-end d-none d-md-flex flex-column">
            <div class="p-4 border-bottom text-center">
                @if($setting && $setting->logo)
                    <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="50" class="mb-2">
                @else
                    <h4 class="fw-bold text-primary mb-0"><i class="bi bi-steering me-2"></i>Satria Jayanti</h4>
                @endif
                <p class="text-muted small mb-0 fw-bold">Student Portal</p>
            </div>
            <div class="p-3 flex-grow-1 nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                <div class="menu-label mb-2">Main Menu</div>
                <button class="nav-link-custom active" data-bs-toggle="pill" data-bs-target="#pane-dashboard" type="button" role="tab">
                    <i class="bi bi-calendar-plus-fill me-3"></i> Penjadwalan
                </button>
                <button class="nav-link-custom mt-2" data-bs-toggle="pill" data-bs-target="#pane-belajar" type="button" role="tab">
                    <i class="bi bi-journal-check me-3"></i> Riwayat Belajar
                </button>
                <button class="nav-link-custom mt-2" data-bs-toggle="pill" data-bs-target="#pane-keuangan" type="button" role="tab">
                    <i class="bi bi-wallet2 me-3"></i> Keuangan & Tagihan
                </button>
                
                <hr class="my-3 opacity-25">
                <button class="nav-link-custom text-primary bg-primary bg-opacity-10" data-bs-toggle="modal" data-bs-target="#panduanSiswaModal" type="button">
                    <i class="bi bi-book-half me-3"></i> Panduan Pengguna
                </button>
            </div>
            <div class="p-3 border-top">
                <form action="{{ url('/logout') }}" method="POST">@csrf
                    <button class="btn btn-outline-danger w-100 fw-bold rounded-pill"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </div>
        </div>

        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="offcanvasSidebar">
            <div class="offcanvas-header border-bottom p-4">
                <div class="d-flex align-items-center">
                    @if($setting && $setting->logo)
                        <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="40" class="me-2">
                    @endif
                    <h5 class="fw-bold text-primary mb-0">Satria Jayanti</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-3 d-flex flex-column nav flex-column nav-pills" role="tablist">
                <div class="flex-grow-1">
                    <div class="menu-label mb-2">Main Menu</div>
                    <button class="nav-link-custom active" data-bs-toggle="pill" data-bs-target="#pane-dashboard" type="button" role="tab">
                        <i class="bi bi-calendar-plus-fill me-3"></i> Penjadwalan
                    </button>
                    <button class="nav-link-custom mt-2" data-bs-toggle="pill" data-bs-target="#pane-belajar" type="button" role="tab">
                        <i class="bi bi-journal-check me-3"></i> Riwayat Belajar
                    </button>
                    <button class="nav-link-custom mt-2" data-bs-toggle="pill" data-bs-target="#pane-keuangan" type="button" role="tab">
                        <i class="bi bi-wallet2 me-3"></i> Keuangan & Tagihan
                    </button>
                </div>
                <div class="mt-auto pt-3 border-top">
                    <form action="{{ url('/logout') }}" method="POST">@csrf
                        <button class="btn btn-outline-danger w-100 fw-bold rounded-pill"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="flex-grow-1 p-3 p-md-4">
            @if(session('success')) <div class="alert alert-success border-0 shadow-sm fw-bold mb-4 rounded-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger border-0 shadow-sm fw-bold mb-4 rounded-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div> @endif

            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- PANE 1: DASHBOARD (PENJADWALAN) -->
                <div class="tab-pane fade show active" id="pane-dashboard" role="tabpanel">
                    <div class="card card-custom bg-primary text-white p-4 mb-4">
                        <h3 class="fw-bold mb-1">Halo, {{ explode(' ', $user->nama_lengkap)[0] }}!</h3>
                        <p class="mb-0 opacity-75 small">Kelola dan ajukan jadwal sesi latihan Anda di halaman ini.</p>
                    </div>

                    @php
                        $baseMaxSesi = $user->package->pertemuan ?? $user->package->jumlah_pertemuan ?? 1;
                        $isPromoManual15 = (strtolower($user->package->transmisi ?? '') == 'manual' && $baseMaxSesi == 15);
                    @endphp

                    @if($isPromoManual15)
                        <div class="alert border-0 shadow-sm mb-4 rounded-4 d-flex align-items-center" style="background-color: #fff9e6; border-left: 5px solid #ffc107 !important;">
                            <i class="bi bi-gift-fill fs-1 text-warning me-3"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Selamat! Anda Mendapatkan Promo Spesial! 🎉</h6>
                                <p class="small text-muted mb-0">Karena Anda mendaftar <strong>Paket Manual 15x Pertemuan</strong>, Anda berhak mendapatkan ekstra <strong>1x Pertemuan GRATIS</strong>. Total progres sesi latihan Anda telah disesuaikan menjadi 16 Sesi.</p>
                            </div>
                        </div>
                    @endif

                    <div class="row g-4">
                        <div class="col-lg-5 d-flex flex-column gap-3">
                            <div class="card card-custom p-4 bg-white shadow-sm border border-primary-subtle flex-grow-1">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Informasi Paket</h6>
                                <h5 class="fw-bold">{{ $user->package->nama_package ?? 'N/A' }}</h5>
                                <h6 class="fw-bold text-secondary mb-4">{{ $user->package->kategori ?? 'N/A' }}</h6>
                                
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Progres Latihan</span>
                                    @php 
                                        $max = $user->package->pertemuan ?? $user->package->jumlah_pertemuan ?? 1;
                                        if (strtolower($user->package->transmisi ?? '') == 'manual' && $max == 15) { $max += 1; }
                                        $done = $mySchedules->where('status', 'Selesai')->count();
                                        $percent = ($max > 0) ? ($done / $max) * 100 : 0;
                                    @endphp
                                    <span>{{ $done }}/{{ $max }} Sesi</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                </div>
                                <p class="small text-muted mb-0">Total sisa sesi yang dapat diambil: <strong>{{ $sisaSesi }} Sesi</strong></p>
                            </div>

                            <div class="card card-custom p-4 shadow-sm text-white mt-auto" style="background: linear-gradient(135deg, #25D366, #128C7E);">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="fw-bold mb-1">Butuh Bantuan?</h5>
                                        <p class="small mb-0 opacity-75">Chat admin cabang disini</p>
                                    </div>
                                    <a href="{{ $waUrlBantuan }}" target="_blank" class="btn btn-light text-success rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; text-decoration: none;">
                                        <i class="bi bi-whatsapp fs-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            @if($tagihanUtama && $tagihanUtama->status == 'Lunas')
                                <div class="card card-custom p-4 bg-white shadow-sm border border-success-subtle h-100">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-calendar-plus me-2 text-primary"></i>Ajukan Jadwal Baru</h5>
                                    
                                    <form action="{{ url('/siswa/simpan-jadwal') }}" method="POST" id="formAjukanJadwal">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="small fw-bold text-muted mb-1">Pilih Tanggal</label>
                                                <!-- 🔥 TRIGGER AJAX UNTUK CEK UNIT -->
                                                <input type="date" name="tanggal" id="tanggalJadwal" class="form-control shadow-sm" min="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="small fw-bold text-muted mb-1">Pilih Jam Latihan (1 Jam)</label>
                                                <!-- 🔥 JAM TER-DISABLE SEBELUM TANGGAL DIPILIH -->
                                                <select name="jam_mulai" id="jamMulaiSelect" class="form-select shadow-sm" required disabled>
                                                    <option value="">-- Pilih Tanggal Dahulu --</option>
                                                </select>
                                                <div id="loadingUnit" class="text-primary small fw-bold mt-1" style="display:none;"><span class="spinner-border spinner-border-sm me-1"></span>Mengecek ketersediaan...</div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm mt-2">Kirim Pengajuan</button>
                                    </form>
                                </div>
                            @else
                                <div class="card card-custom p-5 bg-white shadow-sm border h-100 d-flex flex-column justify-content-center align-items-center text-center">
                                    <i class="bi bi-lock-fill display-1 text-muted mb-3 opacity-25"></i>
                                    <h5 class="fw-bold">Fitur Penjadwalan Terkunci</h5>
                                    <p class="text-muted small mb-4">Silakan unggah bukti dan selesaikan pembayaran Paket Utama Anda untuk membuka fitur ini.</p>
                                    <button class="btn btn-primary rounded-pill px-4 fw-bold switch-tab-btn" data-target="#pane-keuangan">Buka Tab Keuangan</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- PANE 2: RIWAYAT BELAJAR -->
                <div class="tab-pane fade" id="pane-belajar" role="tabpanel">
                    <h4 class="fw-bold mb-4"><i class="bi bi-journal-check me-2 text-primary"></i>Riwayat & Evaluasi Belajar</h4>
                    <div class="row g-3">
                        @forelse($mySchedules as $index => $js)
                        <div class="col-md-6 col-xl-4">
                            <div class="card card-custom p-4 bg-white shadow-sm h-100">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-light text-primary border rounded-pill mb-2">Sesi Ke-{{ $mySchedules->count() - $index }}</span>
                                        <h6 class="fw-bold mb-0 text-dark">{{ date('d M Y', strtotime($js->tanggal)) }}</h6>
                                        <small class="text-muted">{{ date('H:i', strtotime($js->jam_mulai)) }} - {{ date('H:i', strtotime($js->jam_mulai) + 3600) }} WIB</small>
                                    </div>
                                    <div>
                                        @if($js->status == 'Selesai') <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">Selesai</span>
                                        @elseif($js->status == 'Disetujui') <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">Disetujui</span>
                                        @else <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">Pending</span> @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block"><i class="bi bi-person-badge me-1"></i>Instruktur:</small>
                                    <span class="fw-bold text-dark">{{ $js->instructor->nama_lengkap ?? 'Menunggu Plotting Admin' }}</span>
                                </div>

                                @if($js->status == 'Selesai')
                                    <div class="eval-note mb-3 small shadow-sm mt-auto">
                                        <h6 class="fw-bold text-primary mb-1"><i class="bi bi-clipboard2-check-fill me-1"></i>Catatan Instruktur:</h6>
                                        <p class="mb-0 text-dark">"{{ $js->catatan_evaluasi ?? 'Tidak ada catatan khusus.' }}"</p>
                                    </div>
                                    <div class="border-top pt-3">
                                        @if(!$js->rating)
                                            <button class="btn btn-dark btn-sm fw-bold w-100 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $js->id }}">
                                                <i class="bi bi-star-fill text-warning me-2"></i>Beri Penilaian Instruktur
                                            </button>
                                        @else
                                            <div class="bg-light p-2 rounded-3 border text-center">
                                                <div class="text-warning fw-bold mb-1">
                                                    @for($i=0; $i<$js->rating; $i++) <i class="bi bi-star-fill"></i> @endfor
                                                </div>
                                                <span class="small text-muted fw-bold fst-italic">"{{ $js->feedback_siswa }}"</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Modal Feedback -->
                        @if($js->status == 'Selesai' && !$js->rating)
                        <div class="modal fade" id="feedbackModal{{ $js->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered px-3">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <form action="{{ url('/siswa/feedback/'.$js->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4 text-center">
                                            <i class="bi bi-chat-square-heart-fill display-4 text-primary mb-3"></i>
                                            <h5 class="fw-bold">Penilaian Instruktur</h5>
                                            <p class="text-muted small">Bagaimana pengalaman belajarmu dengan <strong>{{ $js->instructor->nama_lengkap ?? 'Instruktur Kami' }}</strong>?</p>
                                            
                                            <div class="mb-3">
                                                <label class="small fw-bold d-block mb-2 text-muted">Rating Bintang</label>
                                                <select name="rating" class="form-select text-center fw-bold shadow-sm" required>
                                                    <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                    <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                                    <option value="3">⭐⭐⭐ (Cukup)</option>
                                                    <option value="2">⭐⭐ (Kurang)</option>
                                                    <option value="1">⭐ (Buruk)</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <textarea name="feedback_siswa" class="form-control shadow-sm" rows="3" placeholder="Tuliskan ulasan Anda mengenai sesi ini..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm">Kirim Penilaian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm border">
                            <i class="bi bi-calendar-x display-1 opacity-25 d-block mb-3"></i>
                            <h5 class="fw-bold text-muted">Belum Ada Riwayat</h5>
                            <p class="text-muted small">Anda belum memiliki riwayat sesi latihan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- PANE 3: KEUANGAN & TAGIHAN -->
                <div class="tab-pane fade" id="pane-keuangan" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Informasi Keuangan</h4>
                    </div>

                    <!-- 🔥 LOGIC BARU: SUMMARY CARDS -->
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-6">
                            <div class="card card-custom p-3 bg-white shadow-sm border-start border-4 border-success h-100">
                                <p class="small text-muted fw-bold mb-1">Total Terbayar</p>
                                <h4 class="fw-bolder text-success mb-0">Rp {{ number_format($totalTerbayar,0,',','.') }}</h4>
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="card card-custom p-3 bg-white shadow-sm border-start border-4 border-danger h-100">
                                <p class="small text-muted fw-bold mb-1">Belum Dibayar</p>
                                <h4 class="fw-bolder text-danger mb-0">Rp {{ number_format($totalBelumDibayar,0,',','.') }}</h4>
                            </div>
                        </div>
                    </div>

                    @php
                        $sudahAdaPelunasan = $tagihanTambahan->where('keterangan', 'Pelunasan Sisa Pembayaran Paket Utama')->count() > 0;
                    @endphp

                    <div class="row g-4">
                        <div class="col-lg-5">
                            <!-- TAGIHAN UTAMA CARD -->
                            @if($tagihanUtama)
                                <div class="card card-custom p-4 bg-white shadow-sm mb-4 {{ $tagihanUtama->status == 'Ditolak' ? 'border border-danger' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Tagihan Utama</h5>
                                        <!-- 🔥 CHECKBOX BULK PAYMENT -->
                                        @if($tagihanUtama->status != 'Lunas' && $tagihanUtama->status != 'Pending')
                                            <input type="checkbox" class="form-check-input bill-checkbox shadow-sm border-secondary" value="{{ $tagihanUtama->id }}" data-nominal="{{ $tagihanUtama->total_tagihan }}">
                                        @endif
                                    </div>
                                    
                                    <h4 class="fw-bolder text-dark mb-1">Rp {{ number_format($tagihanUtama->total_tagihan,0,',','.') }}</h4>
                                    <p class="small text-muted fw-bold mb-3">
                                        {{ $user->package->nama_package ?? 'Paket' }}
                                        @if($sudahAdaPelunasan) <span class="badge bg-warning text-dark ms-1">Status: Down Payment (DP)</span> @endif
                                    </p>

                                    @if($tagihanUtama->status == 'Ditolak')
                                        <div class="alert alert-danger border-0 shadow-sm p-3 mb-3 rounded-4">
                                            <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-circle-fill me-2"></i>Pembayaran Ditolak</h6>
                                            <p class="small text-danger mb-0">Alasan: <strong>{{ $tagihanUtama->penolakan ?? 'Bukti tidak sah.' }}</strong></p>
                                        </div>
                                    @endif

                                    @if(!$tagihanUtama->bukti_bayar || $tagihanUtama->status == 'Ditolak')
                                        <!-- Form Bayar Satuan / Standar -->
                                        <div class="single-payment-form">
                                            <div class="bg-light p-3 rounded-4 border mb-3">
                                                <p class="small fw-bold text-dark mb-2">Transfer ke Rekening Resmi:</p>
                                                <ul class="list-unstyled mb-0 small text-dark">
                                                    <li class="mb-1"><strong>BCA:</strong> 7410689523 (Ricky Rizqul M)</li>
                                                    <li class="mb-0"><strong>BRI:</strong> 211401000434303 (Satria Jayanti)</li>
                                                </ul>
                                            </div>
                                            
                                            <form action="{{ url('/siswa/bayar/' . $tagihanUtama->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @if(!$sudahAdaPelunasan && !$tagihanUtama->bukti_bayar)
                                                <div class="mb-3 p-3 border border-primary-subtle rounded-4 bg-white shadow-sm">
                                                    <label class="small text-muted fw-bold mb-2">Pilihan Pembayaran Awal:</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input single-payment-radio" type="radio" name="jenis_bayar" id="bayarFull" value="full" checked onchange="toggleDpInput()">
                                                            <label class="form-check-label fw-bold small text-dark" for="bayarFull">Full Payment</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input single-payment-radio" type="radio" name="jenis_bayar" id="bayarDp" value="dp" onchange="toggleDpInput()">
                                                            <label class="form-check-label fw-bold small text-dark" for="bayarDp">Down Payment (DP)</label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-3 pt-2 border-top" id="dpInputContainer" style="display: none;">
                                                        <label class="small text-muted fw-bold mb-1">Nominal DP (Rp):</label>
                                                        <input type="number" name="nominal_dp" id="nominalDp" class="form-control form-control-sm shadow-sm" placeholder="Min. 50000" min="50000" max="{{ $tagihanUtama->total_tagihan - 10000 }}">
                                                    </div>
                                                </div>
                                                @endif

                                                <select name="metode_pembayaran" class="form-select form-select-sm shadow-sm mb-3" onchange="toggleQris(this, 'qrisUtama')" required>
                                                    <option value="">-- Pilih Bank --</option>
                                                    <option value="BCA">Transfer ke BCA (7410689523)</option>
                                                    <option value="BRI">Transfer ke BRI (211401000434303)</option>
                                                    @if($user->branch && $user->branch->qris_image) <option value="QRIS">Scan QRIS</option> @endif
                                                </select>
                                                
                                                <div id="qrisUtama" class="text-center mt-3 mb-3 p-3 bg-white rounded-4 border shadow-sm" style="display: none;">
                                                    <p class="small fw-bold text-dark mb-2">Scan QR Code Berikut:</p>
                                                    @if($user->branch && $user->branch->qris_image)
                                                        <img src="{{ asset('storage/uploads/qris/' . $user->branch->qris_image) }}" alt="QRIS" class="img-fluid rounded border shadow-sm" style="max-height: 250px;">
                                                    @endif
                                                </div>

                                                <input type="file" name="bukti_bayar" class="form-control form-control-sm shadow-sm mb-3" required>
                                                <button class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">Kirim Bukti Pembayaran</button>
                                            </form>
                                        </div>
                                    @elseif($tagihanUtama->status == 'Pending')
                                        <div class="text-center p-4 bg-warning bg-opacity-10 rounded-4 border border-warning-subtle mt-3">
                                            <i class="bi bi-hourglass-split text-warning display-4 mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark mb-2">Bukti Sedang Dicek</h6>
                                            <a href="{{ $waUrl }}" target="_blank" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm w-100 mt-2">
                                                <i class="bi bi-whatsapp me-2"></i>Konfirmasi ke Admin
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center p-4 bg-success bg-opacity-10 rounded-4 border border-success-subtle mt-3">
                                            <i class="bi bi-check-circle-fill text-success display-4 mb-2 d-block"></i>
                                            <h5 class="fw-bold text-success mb-0">Pembayaran Berhasil</h5>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-7">
                            <!-- ACCORDION TAGIHAN TAMBAHAN -->
                            @if($tagihanTambahan && $tagihanTambahan->count() > 0)
                            <div class="accordion mb-4 shadow-sm" id="accordionTagihanTambahan">
                                <div class="accordion-item border-0 shadow-sm rounded-4 overflow-hidden">
                                    <h2 class="accordion-header">
                                        @php $adaTanggungan = $tagihanTambahan->where('status', '!=', 'Lunas')->count() > 0; @endphp
                                        <button class="accordion-button bg-white {{ $adaTanggungan ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTambahan">
                                            <i class="bi bi-receipt-cutoff me-2 text-warning fs-5"></i> 
                                            <strong>Tagihan Tambahan ({{ $tagihanTambahan->count() }})</strong>
                                            @if($tagihanTambahan->where('status', '!=', 'Lunas')->count() > 0)
                                                <span class="badge bg-danger rounded-pill ms-auto">Belum Lunas</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="collapseTambahan" class="accordion-collapse collapse {{ $adaTanggungan ? 'show' : '' }}" data-bs-parent="#accordionTagihanTambahan">
                                        <div class="accordion-body bg-light p-3">
                                            <div class="d-flex flex-column gap-3">
                                                @foreach($tagihanTambahan as $tb)
                                                    <div class="card p-3 shadow-sm border-0 {{ $tb->status == 'Ditolak' ? 'rejected-bill' : 'extra-bill' }} rounded-4 position-relative">
                                                        
                                                        <!-- 🔥 CHECKBOX BULK PAYMENT -->
                                                        @if($tb->status != 'Lunas' && $tb->status != 'Pending')
                                                            <div class="position-absolute top-0 end-0 p-3">
                                                                <input type="checkbox" class="form-check-input bill-checkbox shadow-sm border-secondary" value="{{ $tb->id }}" data-nominal="{{ $tb->total_tagihan }}">
                                                            </div>
                                                        @endif

                                                        <div class="pe-5">
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                @if($tb->status == 'Lunas') <span class="badge bg-success">Lunas</span>
                                                                @elseif($tb->status == 'Ditolak') <span class="badge bg-danger">Ditolak</span>
                                                                @elseif(!$tb->bukti_bayar) <span class="badge bg-danger">Belum Bayar</span>
                                                                @else <span class="badge bg-warning text-dark">Proses ACC</span> @endif
                                                            </div>
                                                            <h5 class="fw-bolder text-dark mb-1">Rp {{ number_format($tb->total_tagihan, 0, ',', '.') }}</h5>
                                                            <h6 class="fw-bold mb-2 text-secondary small lh-sm">{{ $tb->keterangan }}</h6>
                                                        </div>
                                                        
                                                        @if($tb->status == 'Ditolak')
                                                            <div class="alert alert-danger p-2 small mb-2 border-0">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Alasan: <strong>{{ $tb->penolakan ?? 'Bukti tidak sah.' }}</strong>
                                                            </div>
                                                        @endif

                                                        @if(!$tb->bukti_bayar || $tb->status == 'Ditolak')
                                                            <div class="single-payment-form">
                                                                <form action="{{ url('/siswa/bayar/' . $tb->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 border-top pt-3">
                                                                    @csrf
                                                                    <div class="row g-2 mb-2">
                                                                        <div class="col-md-6">
                                                                            <select name="metode_pembayaran" class="form-select form-select-sm shadow-sm" required>
                                                                                <option value="">- Pilih Bank -</option>
                                                                                <option value="BCA">BCA (7410689523)</option>
                                                                                <option value="BRI">BRI (211401000434303)</option>
                                                                                @if($user->branch && $user->branch->qris_image) <option value="QRIS">Scan QRIS</option> @endif
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="file" name="bukti_bayar" class="form-control form-control-sm shadow-sm" required>
                                                                        </div>
                                                                    </div>
                                                                    <button class="btn btn-dark btn-sm w-100 fw-bold rounded-pill">Bayar Satuan</button>
                                                                </form>
                                                            </div>
                                                        @elseif($tb->status == 'Pending')
                                                            <a href="{{ $waUrl }}" target="_blank" class="btn btn-success btn-sm fw-bold rounded-pill px-3 mt-2 shadow-sm d-inline-block">
                                                                <i class="bi bi-whatsapp me-1"></i>Konfirmasi Admin
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="card card-custom p-4 bg-white shadow-sm border-0">
                                <h6 class="fw-bold mb-3">Histori Transaksi</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 text-nowrap">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="py-2 px-3 text-muted small">Update</th>
                                                <th class="py-2 text-muted small">Detail</th>
                                                <th class="py-2 text-muted small">Nominal</th>
                                                <th class="py-2 text-muted small text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($riwayatPembayaran as $trx)
                                            <tr>
                                                <td class="px-3 text-muted small fw-bold">{{ date('d M Y', strtotime($trx->updated_at)) }}</td>
                                                <td>
                                                    <span class="fw-bold d-block text-dark small">{{ $trx->jenis_tagihan }}</span>
                                                    <span class="text-muted d-inline-block text-truncate" style="max-width: 150px; font-size: 0.7rem;" title="{{ $trx->keterangan }}">
                                                        {{ $trx->keterangan ?? 'Paket Kursus' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-success small">Rp {{ number_format($trx->total_tagihan, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    @if($trx->status == 'Lunas') 
                                                        <span class="badge-status bg-success bg-opacity-10 text-success"><i class="bi bi-check-circle-fill"></i></span>
                                                    @elseif($trx->status == 'Pending') 
                                                        <span class="badge-status bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></span>
                                                    @else 
                                                        <span class="badge-status bg-danger bg-opacity-10 text-danger"><i class="bi bi-x-circle-fill"></i></span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted small">Belum ada histori transaksi.</td>
                                            </tr>
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
    </div>

    <!-- 🔥 FLOATING BAR BULK PAYMENT -->
    <div id="floatingPaymentBar">
        <div>
            <p class="small text-muted fw-bold mb-0 lh-sm">Pembayaran Gabungan</p>
            <h5 class="fw-bolder text-primary mb-0" id="totalBulkText">Rp 0</h5>
            <small class="text-secondary fw-bold" id="countBulkText">0 Tagihan Terpilih</small>
        </div>
        <button class="btn btn-primary rounded-pill fw-bold shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalBulkPayment">
            Bayar Sekaligus <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>

    <!-- 🔥 MODAL BULK PAYMENT -->
    <div class="modal fade" id="modalBulkPayment" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-collection me-2"></i> Bayar Tagihan Terpilih</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/siswa/bayar-bulk') }}" method="POST" enctype="multipart/form-data" id="formBulkPayment">
                    @csrf
                    <!-- Input Hidden Array akan disisipkan via JS -->
                    <div id="bulkInputsContainer"></div>
                    
                    <div class="modal-body p-4">
                        <div class="alert alert-info border-0 rounded-3 shadow-sm mb-4 small">
                            <i class="bi bi-info-circle-fill me-2"></i> Total tagihan Anda adalah <strong id="modalBulkTotalText">Rp 0</strong>. Silakan transfer tepat sesuai nominal tersebut menggunakan 1 bukti transfer.
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Pilih Bank Tujuan Transfer:</label>
                            <select name="metode_pembayaran" class="form-select shadow-sm" onchange="toggleQris(this, 'qrisBulk')" required>
                                <option value="">-- Pilih Bank --</option>
                                <option value="BCA">Transfer ke BCA (7410689523)</option>
                                <option value="BRI">Transfer ke BRI (211401000434303)</option>
                                @if($user->branch && $user->branch->qris_image) <option value="QRIS">Scan QRIS</option> @endif
                            </select>
                        </div>
                        
                        <div id="qrisBulk" class="text-center mt-3 mb-3 p-3 bg-white rounded-4 border shadow-sm" style="display: none;">
                            <p class="small fw-bold text-dark mb-2">Scan QR Code Berikut:</p>
                            @if($user->branch && $user->branch->qris_image)
                                <img src="{{ asset('storage/uploads/qris/' . $user->branch->qris_image) }}" alt="QRIS" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted fw-bold mb-1">Upload 1 Bukti Transfer:</label>
                            <input type="file" name="bukti_bayar" class="form-control shadow-sm" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 bg-light rounded-bottom-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">Kirim Pembayaran Gabungan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL PANDUAN PENGGUNA & EXTRA CHARGE (SAMA SEPERTI ASLI) -->
    <div class="modal fade" id="panduanSiswaModal" tabindex="-1">
        <!-- ... Isian Modal Panduan (Sesuai original) ... -->
    </div>

    <div class="modal fade" id="modalConfirmExtraCharge" tabindex="-1" data-bs-backdrop="static">
        <!-- ... Isian Modal Extra Charge (Sesuai original) ... -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- 🔥 REVISI AJAX & JS LOGIC -->
    <script>
        // 1. Logic Tabs
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('show.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target');
                document.querySelectorAll(`[data-bs-target="${target}"]`).forEach(t => t.classList.add('active'));
                document.querySelectorAll(`[data-bs-toggle="pill"]:not([data-bs-target="${target}"])`).forEach(t => t.classList.remove('active'));
                const offcanvasEl = document.getElementById('offcanvasSidebar');
                if (offcanvasEl) { const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl); if (offcanvas) offcanvas.hide(); }
            });
        });

        document.querySelectorAll('.switch-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPane = this.getAttribute('data-target');
                const triggerTab = document.querySelector(`.sidebar-desktop [data-bs-target="${targetPane}"]`);
                if(triggerTab) triggerTab.click();
            });
        });

        function toggleQris(selectElement, targetDivId) {
            const qrisDiv = document.getElementById(targetDivId);
            if (selectElement.value === 'QRIS') { qrisDiv.style.display = 'block'; } 
            else { qrisDiv.style.display = 'none'; }
        }

        function toggleDpInput() {
            const isDp = document.getElementById('bayarDp') ? document.getElementById('bayarDp').checked : false;
            const dpContainer = document.getElementById('dpInputContainer');
            const dpInput = document.getElementById('nominalDp');
            if(dpContainer && dpInput) {
                if(isDp) { dpContainer.style.display = 'block'; dpInput.required = true; } 
                else { dpContainer.style.display = 'none'; dpInput.required = false; }
            }
        }

        // 2. 🔥 LOGIC CHECKBOX BULK PAYMENT
        const checkboxes = document.querySelectorAll('.bill-checkbox');
        const floatingBar = document.getElementById('floatingPaymentBar');
        const singleForms = document.querySelectorAll('.single-payment-form');
        const singleRadios = document.querySelectorAll('.single-payment-radio');

        function updateBulkTotal() {
            let totalBulk = 0;
            let checkedCount = 0;
            let selectedIds = [];

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    checkedCount++;
                    totalBulk += parseInt(cb.getAttribute('data-nominal'));
                    selectedIds.push(cb.value);
                }
            });

            // Tampilkan/Sembunyikan Floating Bar
            if (checkedCount > 0) {
                floatingBar.classList.add('show');
                // Sembunyikan form satuan agar user tidak bingung
                singleForms.forEach(form => form.style.opacity = '0.3');
                singleRadios.forEach(radio => radio.disabled = true);
            } else {
                floatingBar.classList.remove('show');
                // Kembalikan form satuan
                singleForms.forEach(form => form.style.opacity = '1');
                singleRadios.forEach(radio => radio.disabled = false);
            }

            // Update Text UI
            document.getElementById('totalBulkText').innerText = 'Rp ' + totalBulk.toLocaleString('id-ID');
            document.getElementById('countBulkText').innerText = checkedCount + ' Tagihan Terpilih';
            document.getElementById('modalBulkTotalText').innerText = 'Rp ' + totalBulk.toLocaleString('id-ID');

            // Inject Array Input Hidden ke dalam Form Modal
            const container = document.getElementById('bulkInputsContainer');
            container.innerHTML = ''; 
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'tagihan_ids[]'; // Format Array PHP
                input.value = id;
                container.appendChild(input);
            });
        }
        checkboxes.forEach(cb => cb.addEventListener('change', updateBulkTotal));

        // 3. 🔥 LOGIC AJAX CEK KETERSEDIAAN UNIT
        const inputTanggal = document.getElementById('tanggalJadwal');
        const selectJam = document.getElementById('jamMulaiSelect');
        const loadingIndicator = document.getElementById('loadingUnit');

        if(inputTanggal && selectJam) {
            inputTanggal.addEventListener('change', function() {
                const tgl = this.value;
                if(!tgl) {
                    selectJam.innerHTML = '<option value="">-- Pilih Tanggal Dahulu --</option>';
                    selectJam.disabled = true;
                    return;
                }

                // Tampilkan loading, bersihkan opsi jam
                loadingIndicator.style.display = 'block';
                selectJam.disabled = true;
                selectJam.innerHTML = '<option value="">-- Menghitung Unit... --</option>';

                // AJAX Fetch
                fetch(`/siswa/cek-unit?tanggal=${tgl}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = 'none';
                        selectJam.disabled = false;
                        selectJam.innerHTML = '<option value="">-- Pilih Jam Mulai --</option>';

                        data.forEach(slot => {
                            const endJam = parseInt(slot.jam_mulai.substring(0, 2)) + 1;
                            const endStr = endJam < 10 ? '0'+endJam+':00' : endJam+':00';
                            
                            const opt = document.createElement('option');
                            opt.value = slot.jam_mulai;
                            
                            // Visualisasi persis sesuai permintaan: 0/2, 2/2, 1/2
                            if(slot.tersedia <= 0) {
                                opt.innerHTML = `${slot.jam_mulai} - ${endStr} WIB (Penuh: 0/${slot.total} Unit)`;
                                opt.disabled = true;
                            } else {
                                opt.innerHTML = `${slot.jam_mulai} - ${endStr} WIB (Tersedia: ${slot.tersedia}/${slot.total} Unit)`;
                            }
                            selectJam.appendChild(opt);
                        });
                    })
                    .catch(error => {
                        loadingIndicator.style.display = 'none';
                        selectJam.innerHTML = '<option value="">-- Gagal memuat data --</option>';
                        alert('Terjadi kesalahan saat mengecek ketersediaan unit.');
                    });
            });
        }
    </script>
</body>
</html>