<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        .sidebar-desktop { width: 280px; min-height: 100vh; position: sticky; top: 0; background: #fff; z-index: 1000; }
        .menu-label { font-size: 0.7rem; text-transform: uppercase; font-weight: 800; color: #adb5bd; letter-spacing: 1px; padding-left: 15px; }
        .nav-link-custom { color: #495057; text-decoration: none; padding: 12px 15px; display: flex; align-items: center; border-radius: 12px; margin-bottom: 5px; transition: 0.3s; font-weight: 500; cursor: pointer; border: none; background: transparent; width: 100%; text-align: left; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; }
        
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .eval-note { background: #f1f5f9; border-left: 4px solid #0d6efd; padding: 15px; border-radius: 8px; font-style: italic; }
        .extra-bill { border-left: 4px solid #ffc107; background: #fffbf0; }
        .rejected-bill { border-left: 4px solid #dc3545 !important; background: #fff5f5 !important; }
        
        .badge-status { padding: 8px 15px; border-radius: 50px; font-weight: 600; font-size: 0.8rem; }
        .accordion-button:not(.collapsed) { background-color: #e7f1ff; color: #0d6efd; font-weight: bold; box-shadow: none; }
        .accordion-button { border-radius: 15px !important; font-weight: 600; padding: 1rem 1.25rem; }
        .accordion-item { border: 1px solid #e9ecef; border-radius: 15px !important; overflow: hidden; margin-bottom: 1rem; }
    </style>
</head>
<body class="bg-light">

    @php
        // LOGIC WHATSAPP ADMIN CABANG
        $adminPhone = $user->branch->no_telp_admin ?? '';
        $cleanPhone = preg_replace('/[^0-9]/', '', $adminPhone);
        if (strpos($cleanPhone, '0') === 0) {
            $waAdmin = '62' . substr($cleanPhone, 1);
        } else {
            $waAdmin = $cleanPhone;
        }
        $namaSiswa = urlencode(explode(' ', $user->nama_lengkap)[0]);
        $namaPaket = urlencode($user->package->nama_package ?? 'Paket');
        $waUrl = "https://wa.me/{$waAdmin}?text=Halo%20Admin,%20saya%20{$namaSiswa}%20baru%20saja%20mengunggah%20bukti%20pembayaran%20untuk%20{$namaPaket}.%20Mohon%20bantuannya%20untuk%20diverifikasi%20ya.";
    @endphp

    <!-- MOBILE NAVBAR -->
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
        <!-- DESKTOP SIDEBAR -->
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
                <!-- 🔥 Navigasi Tabs via Sidebar -->
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

        <!-- OFFCANVAS SIDEBAR MOBILE -->
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
        
        <!-- MAIN CONTENT (TABS CONTENT) -->
        <div class="flex-grow-1 p-3 p-md-4">
            
            @if(session('success')) <div class="alert alert-success border-0 shadow-sm fw-bold mb-4 rounded-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div> @endif
            @if(session('error')) <div class="alert alert-danger border-0 shadow-sm fw-bold mb-4 rounded-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div> @endif

            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- ============================ -->
                <!-- PANE 1: DASHBOARD (PENJADWALAN) -->
                <!-- ============================ -->
                <div class="tab-pane fade show active" id="pane-dashboard" role="tabpanel">
                    
                    <div class="card card-custom bg-primary text-white p-4 mb-4">
                        <h3 class="fw-bold mb-1">Halo, {{ explode(' ', $user->nama_lengkap)[0] }}!</h3>
                        <h6 class="fw-bold mb-2">ID Pendaftaran : {{ $user->id_siswa ?? '-' }}</h6>
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
                        <div class="col-lg-5">
                            <div class="card card-custom p-4 bg-white shadow-sm border border-primary-subtle h-100">
                                <h6 class="text-muted small fw-bold text-uppercase mb-3">Informasi Paket</h6>
                                <h5 class="fw-bold">{{ $user->package->nama_package ?? 'N/A' }}</h5>
                                <h6 class="fw-bold text-secondary mb-4">{{ $user->package->kategori ?? 'N/A' }}</h6>
                                
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Progres Latihan</span>
                                    @php 
                                        $max = $user->package->pertemuan ?? $user->package->jumlah_pertemuan ?? 1;
                                        if (strtolower($user->package->transmisi ?? '') == 'manual' && $max == 15) {
                                            $max += 1; 
                                        }
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
                                                <input type="date" name="tanggal" class="form-control shadow-sm" min="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="small fw-bold text-muted mb-1">Pilih Jam Latihan (1 Jam)</label>
                                                <select name="jam_mulai" id="jamMulaiSelect" class="form-select shadow-sm" required>
                                                    <option value="">-- Jam Mulai --</option>
                                                    <option value="08:00">08:00 - 09:00 WIB</option>
                                                    <option value="09:00">09:00 - 10:00 WIB</option>
                                                    <option value="10:00">10:00 - 11:00 WIB</option>
                                                    <option value="11:00">11:00 - 12:00 WIB</option>
                                                    <option value="12:00">12:00 - 13:00 WIB</option>
                                                    <option value="13:00">13:00 - 14:00 WIB</option>
                                                    <option value="14:00">14:00 - 15:00 WIB</option>
                                                    <option value="15:00">15:00 - 16:00 WIB</option>
                                                    <option value="16:00">16:00 - 17:00 WIB</option>
                                                    <option value="17:00">17:00 - 18:00 WIB</option>
                                                    <option value="18:00">18:00 - 19:00 WIB</option>
                                                    <option value="19:00">19:00 - 20:00 WIB</option>
                                                </select>
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

                <!-- ============================ -->
                <!-- PANE 2: RIWAYAT BELAJAR -->
                <!-- ============================ -->
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
                                        <small class="text-muted">{{ $js->jam_mulai }} WIB</small>
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

                <!-- ============================ -->
                <!-- PANE 3: KEUANGAN & TAGIHAN -->
                <!-- ============================ -->
                <div class="tab-pane fade" id="pane-keuangan" role="tabpanel">
                    <h4 class="fw-bold mb-4"><i class="bi bi-wallet2 me-2 text-primary"></i>Informasi Tagihan & Keuangan</h4>

                    <div class="row g-4">
                        <div class="col-lg-5">
                            <!-- TAGIHAN UTAMA CARD -->
                            @if($tagihanUtama)
                                <div class="card card-custom p-4 bg-white shadow-sm mb-4 {{ $tagihanUtama->status == 'Ditolak' ? 'border border-danger' : '' }}">
                                    <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2 text-primary"></i>Tagihan Paket Utama</h5>
                                    <h4 class="fw-bolder text-dark mb-1">Rp {{ number_format($tagihanUtama->total_tagihan,0,',','.') }}</h4>
                                    <p class="small text-muted fw-bold">{{ $user->package->nama_package ?? 'Paket' }}</p>

                                    @if($tagihanUtama->status == 'Ditolak')
                                        <div class="alert alert-danger border-0 shadow-sm p-3 mb-3 rounded-4">
                                            <h6 class="fw-bold text-danger mb-2"><i class="bi bi-x-circle-fill me-2"></i>Pembayaran Ditolak</h6>
                                            <p class="small text-danger mb-0">Alasan: <strong>{{ $tagihanUtama->penolakan ?? 'Bukti tidak sah.' }}</strong></p>
                                        </div>
                                    @endif

                                    @if(!$tagihanUtama->bukti_bayar || $tagihanUtama->status == 'Ditolak')
                                        <div class="bg-light p-3 rounded-4 border mb-3">
                                            <p class="small fw-bold text-dark mb-2">Informasi Transfer Rekening Resmi:</p>
                                            <ul class="list-unstyled mb-0 small text-dark">
                                                <li class="mb-1"><i class="bi bi-bank me-2 text-primary"></i><strong>BCA:</strong> 7410689523 (Ricky Rizqul Mubaroq)</li>
                                                <li class="mb-0"><i class="bi bi-bank me-2 text-primary"></i><strong>BRI:</strong> 211401000434303 (Satria Jayanti Sejahtera)</li>
                                            </ul>
                                        </div>
                                        
                                        <!-- 🔥 FORM UPLOAD (QRIS DINAMIS) -->
                                        <form action="{{ url('/siswa/bayar/' . $tagihanUtama->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="small text-muted fw-bold mb-1">Pilih Bank Tujuan Transfer:</label>
                                                <select name="metode_pembayaran" class="form-select form-select-sm shadow-sm" onchange="toggleQris(this, 'qrisUtama')" required>
                                                    <option value="">-- Pilih Bank --</option>
                                                    <option value="BCA">Transfer ke BCA (7410689523)</option>
                                                    <option value="BRI">Transfer ke BRI (211401000434303)</option>
                                                    <!-- Tampilkan opsi QRIS HANYA jika cabang ini punya foto QRIS -->
                                                    @if($user->branch && $user->branch->qris_image)
                                                        <option value="QRIS">Scan QRIS (Otomatis & Mudah)</option>
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <!-- 🔥 DIV UNTUK MUNCULKAN QR CODE TAGIHAN UTAMA -->
                                            <div id="qrisUtama" class="text-center mt-3 mb-3 p-3 bg-white rounded-4 border shadow-sm" style="display: none;">
                                                <p class="small fw-bold text-dark mb-2">Scan QR Code Berikut:</p>
                                                @if($user->branch && $user->branch->qris_image)
                                                    <img src="{{ asset('storage/uploads/qris/' . $user->branch->qris_image) }}" alt="QRIS {{ $user->branch->nama_cabang }}" class="img-fluid rounded border shadow-sm" style="max-height: 250px;">
                                                @else
                                                    <span class="text-danger small fw-bold">QRIS belum disetting oleh Admin.</span>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="small text-muted fw-bold mb-1">Upload Bukti Transfer (Resi):</label>
                                                <input type="file" name="bukti_bayar" class="form-control form-control-sm shadow-sm" required>
                                            </div>
                                            <button class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">Kirim Bukti Pembayaran</button>
                                        </form>

                                    @elseif($tagihanUtama->status == 'Pending')
                                        <div class="text-center p-4 bg-warning bg-opacity-10 rounded-4 border border-warning-subtle mt-3">
                                            <i class="bi bi-hourglass-split text-warning display-4 mb-2 d-block"></i>
                                            <h6 class="fw-bold text-dark mb-2">Bukti Sedang Dicek</h6>
                                            <p class="small text-muted mb-3">Sistem kami sedang memproses bukti pembayaran Anda. Konfirmasi via WhatsApp ke Admin Cabang agar diverifikasi lebih cepat.</p>
                                            
                                            <a href="{{ $waUrl }}" target="_blank" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm w-100">
                                                <i class="bi bi-whatsapp me-2"></i>Konfirmasi ke Admin
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center p-4 bg-success bg-opacity-10 rounded-4 border border-success-subtle mt-3">
                                            <i class="bi bi-check-circle-fill text-success display-4 mb-2 d-block"></i>
                                            <h5 class="fw-bold text-success mb-0">Pembayaran Lunas</h5>
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
                                        @php $adaTanggungan = $tagihanTambahan->whereIn('status', ['Pending', 'Ditolak'])->count() > 0; @endphp
                                        <button class="accordion-button bg-white {{ $adaTanggungan ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTambahan">
                                            <i class="bi bi-receipt-cutoff me-2 text-warning fs-5"></i> 
                                            <strong>Tagihan Tambahan ({{ $tagihanTambahan->count() }})</strong>
                                            @if($tagihanTambahan->where('status', 'Pending')->whereNull('bukti_bayar')->count() > 0)
                                                <span class="badge bg-danger rounded-pill ms-auto">Belum Dibayar</span>
                                            @endif
                                        </button>
                                    </h2>
                                    <div id="collapseTambahan" class="accordion-collapse collapse {{ $adaTanggungan ? 'show' : '' }}" data-bs-parent="#accordionTagihanTambahan">
                                        <div class="accordion-body bg-light p-3">
                                            <div class="d-flex flex-column gap-3">
                                                @foreach($tagihanTambahan as $tb)
                                                    <div class="card p-3 shadow-sm border-0 {{ $tb->status == 'Ditolak' ? 'rejected-bill' : 'extra-bill' }} rounded-4">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="fw-bold mb-0 text-dark small lh-sm">{{ $tb->keterangan }}</h6>
                                                            @if($tb->status == 'Lunas') <span class="badge bg-success">Lunas</span>
                                                            @elseif($tb->status == 'Ditolak') <span class="badge bg-danger">Ditolak</span>
                                                            @elseif(!$tb->bukti_bayar) <span class="badge bg-danger">Belum Bayar</span>
                                                            @else <span class="badge bg-warning text-dark">Proses ACC</span> @endif
                                                        </div>
                                                        <h5 class="fw-bolder text-dark mb-2">Rp {{ number_format($tb->total_tagihan, 0, ',', '.') }}</h5>
                                                        
                                                        @if($tb->status == 'Ditolak')
                                                            <div class="alert alert-danger p-2 small mb-2 border-0">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Alasan: <strong>{{ $tb->penolakan ?? 'Bukti tidak sah.' }}</strong>
                                                            </div>
                                                        @endif

                                                        @if(!$tb->bukti_bayar || $tb->status == 'Ditolak')
                                                            <form action="{{ url('/siswa/bayar/' . $tb->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 border-top pt-2">
                                                                @csrf
                                                                <!-- Tambahan Dropdown Bank + Fungsi QRIS Dinamis -->
                                                                <select name="metode_pembayaran" class="form-select form-select-sm mb-2 shadow-sm" onchange="toggleQris(this, 'qrisTambahan{{ $tb->id }}')" required>
                                                                    <option value="">- Pilih Bank -</option>
                                                                    <option value="BCA">BCA (7410689523)</option>
                                                                    <option value="BRI">BRI (211401000434303)</option>
                                                                    @if($user->branch && $user->branch->qris_image)
                                                                        <option value="QRIS">Scan QRIS (Otomatis & Mudah)</option>
                                                                    @endif
                                                                </select>

                                                                <!-- 🔥 DIV UNTUK MUNCULKAN QR CODE TAGIHAN TAMBAHAN -->
                                                                <div id="qrisTambahan{{ $tb->id }}" class="text-center mb-3 p-2 bg-white rounded-3 border shadow-sm" style="display: none;">
                                                                    <p class="small fw-bold text-dark mb-2">Scan QR Code Berikut:</p>
                                                                    @if($user->branch && $user->branch->qris_image)
                                                                        <img src="{{ asset('storage/uploads/qris/' . $user->branch->qris_image) }}" alt="QRIS {{ $user->branch->nama_cabang }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                                                                    @else
                                                                        <span class="text-danger small fw-bold">QRIS belum disetting oleh Admin.</span>
                                                                    @endif
                                                                </div>

                                                                <div class="input-group input-group-sm">
                                                                    <input type="file" name="bukti_bayar" class="form-control" required>
                                                                    <button class="btn btn-dark fw-bold px-3">Kirim</button>
                                                                </div>
                                                            </form>
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

                            <!-- TABEL RIWAYAT TRANSAKSI -->
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

    <!-- MODAL PANDUAN PENGGUNA -->
    <div class="modal fade" id="panduanSiswaModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable px-3">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold"><i class="bi bi-info-circle-fill me-2"></i> Panduan Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Selamat datang di portal siswa <strong>{{ $setting->nama_website ?? 'Satria Jayanti' }}</strong>! Ikuti panduan berikut.</p>
                    
                    <div class="d-flex mb-4">
                        <div class="me-3"><div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; font-weight: bold;">1</div></div>
                        <div>
                            <h6 class="fw-bold mb-1">Upload Bukti Pembayaran</h6>
                            <p class="small text-muted mb-0">Masuk ke menu <strong>Keuangan & Tagihan</strong>. Pilih metode bank, transfer, lalu upload buktinya. Lakukan konfirmasi WA agar admin cepat memverifikasi.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3"><div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; font-weight: bold;">2</div></div>
                        <div>
                            <h6 class="fw-bold mb-1">Pilih Jadwal Latihan</h6>
                            <p class="small text-muted mb-0">Setelah Lunas, kembali ke menu <strong>Penjadwalan</strong>. Pilih tanggal dan jam latihan, lalu tunggu konfirmasi plot instruktur.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="me-3"><div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; font-weight: bold;">3</div></div>
                        <div>
                            <h6 class="fw-bold mb-1">Beri Penilaian</h6>
                            <p class="small text-muted mb-0">Di menu <strong>Riwayat Belajar</strong>, jika status latihan sudah Selesai, jangan lupa berikan penilaian kepuasan (*rating*) kepada instruktur Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light w-100 rounded-pill fw-bold shadow-sm" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 MODAL CONFIRM EXTRA CHARGE -->
    <div class="modal fade" id="modalConfirmExtraCharge" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-warning text-dark border-0 rounded-top-4">
                    <h6 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Perubahan Jadwal</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-clock-history display-1 text-warning mb-3 d-block opacity-75"></i>
                    <h6 class="fw-bold text-dark lh-base">Apakah Anda yakin untuk melakukan penjadwalan di jam Non-Reguler?</h6>
                    <p class="text-muted small mt-2 mb-0">Anda akan diminta untuk membayar biaya *charge* untuk perubahan dari paket Reguler ke Non-Reguler sebesar <strong class="text-danger">Rp 20.000</strong>.</p>
                </div>
                <div class="modal-footer bg-light border-0 p-3 d-flex flex-nowrap rounded-bottom-4">
                    <button type="button" class="btn btn-secondary w-50 rounded-pill fw-bold shadow-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnConfirmExtra" class="btn btn-warning w-50 rounded-pill fw-bold text-dark shadow-sm">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // SCRIPT SINKRONISASI TABS AGAR JALAN MULUS DI DESKTOP & MOBILE
        document.querySelectorAll('[data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('show.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target');
                document.querySelectorAll(`[data-bs-target="${target}"]`).forEach(t => t.classList.add('active'));
                document.querySelectorAll(`[data-bs-toggle="pill"]:not([data-bs-target="${target}"])`).forEach(t => t.classList.remove('active'));
                
                const offcanvasEl = document.getElementById('offcanvasSidebar');
                if (offcanvasEl) {
                    const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) offcanvas.hide();
                }
            });
        });

        // Script Tombol "Buka Tab Keuangan" dari Dashboard
        document.querySelectorAll('.switch-tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPane = this.getAttribute('data-target');
                const triggerTab = document.querySelector(`.sidebar-desktop [data-bs-target="${targetPane}"]`);
                if(triggerTab) triggerTab.click();
            });
        });

        // 🔥 LOGIC JAVASCRIPT UNTUK POPUP JAM NON-REGULER
        const formAjukan = document.getElementById('formAjukanJadwal');
        if (formAjukan) {
            formAjukan.addEventListener('submit', function(e) {
                const isReguler = "{{ $user->package->kategori ?? 'Reguler' }}" === "Reguler";
                const jamMulaiVal = document.getElementById('jamMulaiSelect').value;
                
                if(jamMulaiVal) {
                    const jamInt = parseInt(jamMulaiVal.substring(0, 2));

                    if (isReguler && jamInt >= 16) {
                        if (!document.getElementById('acc_extra_charge_input')) {
                            e.preventDefault(); 
                            var myModal = new bootstrap.Modal(document.getElementById('modalConfirmExtraCharge'));
                            myModal.show();
                        }
                    }
                }
            });
        }

        const btnConfirm = document.getElementById('btnConfirmExtra');
        if (btnConfirm) {
            btnConfirm.addEventListener('click', function() {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.id = 'acc_extra_charge_input';
                input.name = 'acc_extra_charge';
                input.value = '1';
                formAjukan.appendChild(input);
                formAjukan.submit();
            });
        }

        // 🔥 FUNGSI JAVASCRIPT UNTUK MENAMPILKAN / MENYEMBUNYIKAN GAMBAR QRIS
        function toggleQris(selectElement, targetDivId) {
            const qrisDiv = document.getElementById(targetDivId);
            if (selectElement.value === 'QRIS') {
                qrisDiv.style.display = 'block'; 
            } else {
                qrisDiv.style.display = 'none'; 
            }
        }
    </script>
</body>
</html>