<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        
        /* Banner Style */
        .welcome-banner {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 20px;
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::after {
            content: "\F5D1";
            font-family: "bootstrap-icons";
            position: absolute;
            right: -20px;
            bottom: -40px;
            font-size: 15rem;
            opacity: 0.1;
            transform: rotate(-15deg);
        }

        /* Statistical Cards Style */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            height: 100%;
            transition: transform 0.2s ease-in-out;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-card.blue { border-left: 5px solid #0d6efd; }
        .stat-card.green { border-left: 5px solid #20c997; }
        .stat-card.yellow { border-left: 5px solid #ffc107; }
        .stat-card.purple { border-left: 5px solid #6f42c1; }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1.2rem;
        }
        .stat-icon.blue { background-color: #3b82f6; }
        .stat-icon.green { background-color: #22c55e; }
        .stat-icon.yellow { background-color: #eab308; }
        .stat-icon.purple { background-color: #8b5cf6; }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            line-height: 1;
        }

        /* Custom Accordion for FAQ */
        .accordion-button:not(.collapsed) {
            background-color: #e7f1ff;
            color: #0d6efd;
            font-weight: bold;
            box-shadow: none;
        }
        .accordion-button:focus { box-shadow: none; border-color: rgba(0,0,0,.125); }
        .accordion-item { border-radius: 12px !important; overflow: hidden; margin-bottom: 10px; border: 1px solid #e2e8f0; }
        .accordion-button { font-weight: 600; color: #495057; padding: 1.2rem 1.5rem; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('admin.sidebar')

        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="welcome-banner shadow-sm mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="position-relative z-1">
                    <span class="badge bg-white text-primary mb-2 px-3 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-geo-alt-fill me-1"></i> Admin Cabang: {{ Auth::user()->branch->nama_cabang ?? 'Pusat' }}
                    </span>
                    <h2 class="fw-bolder mb-1">Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'Admin' }}!</h2>
                    <p class="mb-0 opacity-75">Pusat kendali operasional, jadwal, dan keuangan Satria Jayanti.</p>
                </div>
                <div class="position-relative z-1">
                    <button class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#panduanAdminModal">
                        <i class="bi bi-patch-question-fill me-2 fs-5 align-middle"></i> Buka Panduan & FAQ
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end text-muted small fw-bold mb-3">
                <i class="bi bi-calendar-event me-2"></i> {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card purple">
                        <div class="stat-icon purple"><i class="bi bi-diagram-3-fill"></i></div>
                        <div class="stat-label">Total Keseluruhan</div>
                        <h3 class="stat-value">{{ $siswaKeseluruhan ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card blue">
                        <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-label">Siswa Aktif</div>
                        <h3 class="stat-value">{{ $siswaAktif ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card green">
                        <div class="stat-icon green"><i class="bi bi-calendar2-check-fill"></i></div>
                        <div class="stat-label">Siswa Bulan Ini</div>
                        <h3 class="stat-value">{{ $siswaBulanIni ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card yellow">
                        <div class="stat-icon yellow"><i class="bi bi-person-lines-fill"></i></div>
                        <div class="stat-label">Pendaftar Baru Hari Ini</div>
                        <h3 class="stat-value">{{ $pendaftaranBaruHariIni ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- GRAFIK PENDAFTARAN -->
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Statistik Pendaftaran (6 Bulan Terakhir)</h5>
                </div>
                <div style="height: 300px; width: 100%;">
                    <canvas id="siswaChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="panduanAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white border-0 rounded-top-4 p-4">
                    <div>
                        <h5 class="modal-title fw-bold mb-1"><i class="bi bi-journal-bookmark-fill me-2"></i> Pusat Bantuan Admin</h5>
                        <p class="mb-0 small opacity-75">Panduan lengkap mengelola fitur kompleks PT. Satria Jayanti.</p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="accordion" id="faqAdmin">
                        
                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    <i class="bi bi-person-check-fill text-success me-3 fs-5"></i> 1. Bagaimana cara mengaktifkan akun siswa baru?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAdmin">
                                <div class="accordion-body text-muted small lh-lg">
                                    Masuk ke menu <strong>Keuangan</strong>. Cari tagihan bersatus <em>"Pending"</em> dengan jenis <strong>Paket Utama</strong>. Klik untuk melihat foto bukti transfer. Jika dana sudah masuk, ubah status menjadi <strong>Lunas</strong>. Sistem akan otomatis mengubah status siswa tersebut menjadi "Aktif" agar ia bisa mulai memilih jadwal latihan.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    <i class="bi bi-calendar-x-fill text-danger me-3 fs-5"></i> 2. Kenapa saya gagal mem-plot Instruktur (Sistem Menolak)?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAdmin">
                                <div class="accordion-body text-muted small lh-lg">
                                    Sistem kita dilengkapi <strong>Smart Scheduling</strong>. Jika muncul peringatan merah "SISTEM MENOLAK", artinya:
                                    <ul class="mb-0 mt-2">
                                        <li>Instruktur tersebut sedang <strong>Cuti</strong> pada tanggal tersebut.</li>
                                        <li>Instruktur sudah memiliki jadwal <strong>Bentrok</strong> dengan siswa lain di tanggal dan jam yang sama persis.</li>
                                    </ul>
                                    <strong>Solusi:</strong> Pilih instruktur lain, atau lakukan Reschedule jam/tanggal latihan siswa.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    <i class="bi bi-receipt-cutoff text-warning me-3 fs-5"></i> 3. Cara membuat tagihan tambahan (Biaya SIM, dll)?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAdmin">
                                <div class="accordion-body text-muted small lh-lg">
                                    Masuk ke menu <strong>Keuangan</strong>. Cari tombol/form <strong>Buat Tagihan Tambahan</strong>. Pilih nama siswa yang dituju, ketik nominal angka (tanpa titik), dan berikan keterangan jelas (Contoh: "Biaya Pembuatan SIM A"). Tagihan akan langsung muncul di dashboard siswa untuk segera dilunasi.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i> 4. Apa itu status "Selesai Latihan"?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAdmin">
                                <div class="accordion-body text-muted small lh-lg">
                                    Jika siswa sudah menyelesaikan seluruh pertemuan dan kuotanya habis, buka menu <strong>Siswa</strong> lalu edit akunnya. Ubah Status Akun menjadi <strong>"Selesai Latihan"</strong>. Ini akan memindahkan mereka dari hitungan "Siswa Aktif" tanpa menghapus rekam jejak mereka sebagai alumni (tetap dihitung di Total Keseluruhan).
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-2 bg-light">
                    <button type="button" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm" data-bs-dismiss="modal">Saya Paham, Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('siswaChart').getContext('2d');
            const siswaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendaftar Baru',
                        data: @json($chartData),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#0d6efd',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: '#e2e8f0' },
                            ticks: { precision: 0, color: '#64748b' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { weight: 'bold' } }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>