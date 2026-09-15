<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Dashboard - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --sj-primary: #0d6efd;
            --sj-bg: #f8fafc;
            --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .nav-link-custom { 
            display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; 
            text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s;
        }
        .nav-link-custom:hover, .nav-link-custom.active { background: #fff; color: var(--sj-primary); box-shadow: var(--sj-card-shadow); font-weight: 600; }
        .sidebar { width: 280px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #e2e8f0; z-index: 1000; }
        .main-content { margin-left: 280px; padding: 2rem; min-height: 100vh; }
        .card-stat { border: none; border-radius: 20px; background: #fff; box-shadow: var(--sj-card-shadow); transition: 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .icon-shape { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }
        
        /* Interactive Branch Card */
        .card-branch { cursor: pointer; transition: all 0.3s ease; border: 1px solid transparent; }
        .card-branch:hover { border-color: var(--sj-primary); box-shadow: 0 15px 35px rgba(13, 110, 253, 0.1); transform: translateY(-5px); }
        
        /* Banner & Component Styling */
        .header-banner { 
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border-radius: 24px; padding: 40px; color: white; position: relative; overflow: hidden;
            box-shadow: 0 15px 35px rgba(13, 110, 253, 0.2);
        }
        .header-banner::after {
            content: ""; position: absolute; top: -50px; right: -50px; width: 200px; height: 200px;
            background: rgba(255, 255, 255, 0.1); border-radius: 50%;
        }
        .faq-accordion .accordion-item { border: none; border-radius: 15px; margin-bottom: 15px; box-shadow: var(--sj-card-shadow); overflow: hidden; }
        .faq-accordion .accordion-button { font-weight: 600; padding: 1.2rem; }
        .faq-accordion .accordion-button:not(.collapsed) { background-color: #eef2ff; color: var(--sj-primary); }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="sidebar p-3">
        <div class="d-flex align-items-center px-3 mb-5 mt-2">
            <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" alt="Logo" class="img-fluid" style="max-height: 45px;">
        </div>
        
        <div class="mb-4">
            <small class="text-uppercase text-muted fw-bold px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Main Menu</small>
            <nav class="mt-2">
                <a href="{{ route('management.dashboard') }}" class="nav-link-custom active">
                    <i class="bi bi-grid-fill me-3"></i> Dashboard
                </a>
                <a href="{{ url('/management/units') }}" class="nav-link-custom">
                    <i class="bi bi-truck me-3"></i> Manajemen Unit
                </a>
                <a href="{{ route('management.laporan.index') }}" class="nav-link-custom">
                    <i class="bi bi-file-earmark-bar-graph me-3"></i> Laporan Keuangan
                </a>
                <a href="{{ route('management.karyawan.index') }}" class="nav-link-custom">
                    <i class="bi bi-people me-3"></i> Data Karyawan
                </a>
                <a href="{{ route('management.cuti.index') }}" class="nav-link-custom">
                    <i class="bi bi-calendar-event me-3"></i> Pengajuan Cuti
                </a>
            </nav>
        </div>

        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link-custom text-danger border-0 bg-transparent w-100">
                    <i class="bi bi-box-arrow-left me-3"></i> Keluar Aplikasi
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header-banner mb-4">
            <div class="row align-items-center position-relative z-1">
                <div class="col-md-7">
                    <h2 class="fw-bold mb-2">Selamat Datang, Management!</h2>
                    <p class="m-0 opacity-75 fs-6">Pantau performa dan kendali penuh operasional Satria Jayanti.</p>
                    
                    <button class="btn btn-light text-primary fw-bold rounded-pill mt-4 shadow-sm px-4" data-bs-toggle="modal" data-bs-target="#faqModal">
                        <i class="bi bi-info-circle-fill me-2"></i> Pusat Bantuan / FAQ
                    </button>
                </div>
                
                <div class="col-md-5 mt-4 mt-md-0 d-flex flex-column align-items-md-end">
                    <!-- Profile User -->
                    <div class="d-inline-flex bg-white bg-opacity-25 p-3 rounded-4 mb-3 shadow-sm" style="backdrop-filter: blur(10px);">
                        <div class="text-end me-3 text-white">
                            <p class="m-0 fw-bold">{{ $user->nama_lengkap }}</p>
                            <p class="m-0 small opacity-75 text-uppercase" style="font-size: 0.65rem;">Level: {{ $user->role }}</p>
                        </div>
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 45px; height: 45px; font-size: 1.2rem;">
                            {{ substr($user->nama_lengkap, 0, 1) }}
                        </div>
                    </div>

                    <!-- Filter Data Berdasarkan Tahun -->
                    <form action="{{ route('management.dashboard') }}" method="GET" class="d-flex bg-white p-2 rounded-pill shadow-sm mt-2 w-100" style="max-width: 300px;">
                        <select name="tahun" class="form-select border-0 bg-transparent fw-bold" style="box-shadow: none;">
                            @for($i = date('Y'); $i >= date('Y') - 4; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>Tahun {{ $i }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Filter</button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(count($reminders['pajak']) > 0 || count($reminders['kir']) > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-stat p-4 border-start border-5 border-warning">
                    <h6 class="fw-bold text-warning mb-3"><i class="bi bi-bell-fill me-2"></i> Pengingat Penting (H-14 Jatuh Tempo)</h6>
                    <div class="row g-3">
                        @foreach($reminders['pajak'] as $pajak)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-warning-subtle rounded-4 border border-warning-subtle">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="m-0 small fw-bold text-dark">Pajak STNK: {{ $pajak->nama_mobil }} ({{ $pajak->nopol }})</p>
                                    <p class="m-0 text-muted" style="font-size: 0.75rem;">Jatuh Tempo: <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($pajak->tgl_jatuh_tempo_pajak)->format('d M Y') }}</span></p>
                                </div>
                                <a href="{{ url('/management/units') }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold small">Urus</a>
                            </div>
                        </div>
                        @endforeach

                        @foreach($reminders['kir'] as $kir)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-danger-subtle rounded-4 border border-danger-subtle">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-shield-exclamation"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="m-0 small fw-bold text-dark">KIR Unit: {{ $kir->nama_mobil }} ({{ $kir->nopol }})</p>
                                    <p class="m-0 text-muted" style="font-size: 0.75rem;">Masa Berlaku: <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($kir->tgl_jatuh_tempo_kir)->format('d M Y') }}</span></p>
                                </div>
                                <a href="{{ url('/management/units') }}" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold small">Urus</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <h5 class="fw-bold text-dark mb-3 mt-2">Statistik Per Cabang (Tahun {{ $tahun }})</h5>
        <div class="row g-4 mb-5">
            @foreach($branchStats as $index => $bs)
            <div class="col-md-4">
                <div class="card card-stat card-branch p-4 h-100 border-top border-4 border-primary" data-bs-toggle="modal" data-bs-target="#branchModal{{ $index }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 class="fw-bold text-primary m-0"><i class="bi bi-shop me-2"></i>{{ $bs['nama'] }}</h6>
                        <span class="text-muted"><i class="bi bi-box-arrow-in-up-right"></i></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <span class="text-muted small fw-bold">Pendaftar</span>
                        <span class="fw-bold">{{ $bs['siswa_year'] }} Siswa</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small fw-bold">Omzet Tercatat</span>
                        <span class="fw-bold text-success">Rp {{ number_format($bs['revenue_year'], 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-auto pt-2 text-center">
                        <span class="badge bg-light text-primary border rounded-pill small w-100 py-2">Klik untuk detail statistik</span>
                    </div>
                </div>
            </div>

            <!-- Modal Statistik Detail Per Cabang -->
            <div class="modal fade" id="branchModal{{ $index }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">
                        <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-4">
                            <h5 class="modal-title fw-bold"><i class="bi bi-bar-chart-fill me-2"></i>Statistik Detail: {{ $bs['nama'] }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 bg-light">
                            <!-- Metrik Global All-Time -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-4 shadow-sm border border-light">
                                        <p class="text-muted small fw-bold mb-1">Total Pendaftar Keseluruhan (All-Time)</p>
                                        <h3 class="fw-bold mb-0 text-dark">{{ $bs['siswa_all'] }} <span class="fs-6 text-muted fw-normal">Siswa</span></h3>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-4 shadow-sm border border-light">
                                        <p class="text-muted small fw-bold mb-1">Total Omzet Keseluruhan (All-Time)</p>
                                        <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($bs['revenue_all'], 0, ',', '.') }}</h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 🔥 REVISI: Penyesuaian Grafik Dampingan -->
                            <div class="row g-3">
                                <!-- Grafik 1: Tren Pendaftaran (Lebih Lebar) -->
                                <div class="col-lg-7">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                                        <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;"><i class="bi bi-graph-up text-warning me-2"></i>Tren Pendaftaran Siswa (Tahun {{ $tahun }})</h6>
                                        <div style="height: 220px; width: 100%;">
                                            <canvas id="pendaftaranChart{{ $index }}"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <!-- Grafik 2: Bar Chart Peminatan Transmisi (Lebih Ramping / Dikecilkan) -->
                                <div class="col-lg-5">
                                    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                                        <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;"><i class="bi bi-gear-fill text-primary me-2"></i>Peminatan Transmisi (Tahun {{ $tahun }})</h6>
                                        <div style="height: 220px; width: 100%;">
                                            <canvas id="transmisiChart{{ $index }}"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer border-0 bg-light p-4 pt-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Tutup Detail</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <h5 class="fw-bold text-dark mb-3">Tinjauan Pertumbuhan Omzet Global</h5>
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card card-stat p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark m-0">Kurva Pendapatan Bulanan ({{ $tahun }})</h6>
                    </div>
                    <div style="height: 350px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-3">Statistik Kumulatif Keseluruhan (All-Time)</h5>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold mb-1">TOTAL CABANG</p>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalCabang }}</h4>
                        </div>
                        <div class="icon-shape bg-primary-subtle text-primary">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold mb-1">TOTAL SISWA</p>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalSiswa }}</h4>
                        </div>
                        <div class="icon-shape bg-success-subtle text-success">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold mb-1">INSTRUKTUR</p>
                            <h4 class="fw-bold text-dark mb-0">{{ $totalInstruktur }}</h4>
                        </div>
                        <div class="icon-shape bg-warning-subtle text-warning">
                            <i class="bi bi-person-badge fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small fw-bold mb-1">TOTAL OMZET</p>
                            <h5 class="fw-bold text-dark mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                        </div>
                        <div class="icon-shape bg-info-subtle text-info">
                            <i class="bi bi-wallet2 fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal FAQ Bantuan -->
    <div class="modal fade" id="faqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-4">
                    <div>
                        <h5 class="modal-title fw-bold mb-1"><i class="bi bi-info-circle-fill me-2"></i> FAQ & Pusat Bantuan Management</h5>
                        <p class="mb-0 small opacity-75">Panduan mengelola data tingkat executive perusahaan.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="accordion faq-accordion" id="faqSj">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#f1">
                                    Bagaimana cara memantau laporan antar cabang?
                                </button>
                            </h2>
                            <div id="f1" class="accordion-collapse collapse show" data-bs-parent="#faqSj">
                                <div class="accordion-body text-muted small lh-lg">
                                    Pilih menu <strong>Laporan Keuangan</strong> di sidebar, lalu gunakan filter cabang yang tersedia untuk melihat data transaksi secara spesifik untuk masing-masing wilayah tugas. Anda juga dapat menggunakan tombol Filter Tahun di bagian atas dashboard untuk menyaring tren grafik per tahun.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#f2">
                                    Apa yang harus dilakukan jika unit kendaraan masuk masa jatuh tempo?
                                </button>
                            </h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#faqSj">
                                <div class="accordion-body text-muted small lh-lg">
                                    Instruksikan tim administrasi untuk segera melakukan perpanjangan dokumen legal ke Samsat/Dinas terkait. Setelah diperpanjang, update "Tanggal Jatuh Tempo" terbaru melalui menu <strong>Manajemen Unit</strong>. Sistem peringatan akan hilang secara otomatis.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Inisialisasi Grafik Pendapatan Garis (Line Chart Global)
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: @json($chartBulan),
                datasets: [{
                    label: 'Pendapatan Lunas',
                    data: @json($chartPendapatan),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    borderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        bodyFont: { family: 'Segoe UI', size: 13 },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true, 
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            callback: function(value) { 
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(0) + ' Jt';
                                return 'Rp ' + value;
                            } 
                        } 
                    }
                }
            }
        });

        // 2. Setup Grafik Dinamis untuk Modal Cabang (Bar Chart & Line Chart Baru)
        const branchStats = @json($branchStats);
        const chartsInstance = {}; 

        branchStats.forEach((bs, index) => {
            const modalEl = document.getElementById('branchModal' + index);
            
            modalEl.addEventListener('shown.bs.modal', function () {
                
                // Cek apakah grafik pendaftaran siswa sudah dirender, jika belum, buat baru
                if (!chartsInstance['pendaftaran' + index]) {
                    const ctxDaftar = document.getElementById('pendaftaranChart' + index).getContext('2d');
                    new Chart(ctxDaftar, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                            datasets: [{
                                label: 'Siswa Mendaftar',
                                data: bs.siswa_bulanan,
                                borderColor: '#ffc107', // Warna kuning elegan / warning
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    padding: 12,
                                    bodyFont: { family: 'Segoe UI', size: 12 },
                                    displayColors: false
                                }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                                y: { beginAtZero: true, border: { dash: [4, 4] }, ticks: { precision: 0, stepSize: 1, size: 11 } }
                            }
                        }
                    });
                    chartsInstance['pendaftaran' + index] = true; 
                }

                // Cek apakah grafik peminatan transmisi (yang sudah dikecilkan) sudah dirender
                if (!chartsInstance['transmisi' + index]) {
                    const ctxTransmisi = document.getElementById('transmisiChart' + index).getContext('2d');
                    new Chart(ctxTransmisi, {
                        type: 'bar',
                        data: {
                            labels: ['Manual', 'Matic'],
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: [bs.manual_count, bs.matic_count],
                                backgroundColor: ['#0d6efd', '#20c997'], // Kombinasi biru & hijau
                                borderRadius: 6,
                                barPercentage: 0.5 // 🔥 Membuat ukuran bar menjadi langsing dan proporsional
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }, 
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    padding: 12,
                                    bodyFont: { family: 'Segoe UI', size: 12 },
                                    displayColors: false
                                }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { weight: 'bold', size: 11 } } },
                                y: { beginAtZero: true, border: { dash: [4, 4] }, ticks: { precision: 0, stepSize: 1, size: 11 } }
                            }
                        }
                    });
                    chartsInstance['transmisi' + index] = true;
                }
            });
        });
    </script>
</body>
</html>