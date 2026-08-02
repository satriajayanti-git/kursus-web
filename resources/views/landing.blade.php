<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $setting->nama_website ?? 'Satria Jayanti - Professional Driving School' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --sj-primary: #0d6efd; 
            --sj-primary-soft: #e7f1ff;
            --sj-info: #0dcaf0; 
            --sj-dark: #0f172a; 
            --sj-text: #475569; 
            --sj-bg: #f8fafc; 
            --sj-surface: #ffffff;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            color: var(--sj-text); 
            background-color: var(--sj-bg); 
            overflow-x: hidden; 
        }
        
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Montserrat', sans-serif; }

        /* NAVBAR - Slim & Floating Static */
        .navbar-wrapper {
            position: absolute; 
            top: 15px;
            left: 0;
            width: 100%;
            z-index: 1030;
            padding: 0 15px;
        }
        .navbar-floating { 
            background: rgba(255, 255, 255, 0.95) !important; 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 40px;
            padding: 0.6rem 1.8rem; 
            box-shadow: 0 8px 30px rgba(13, 110, 253, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.7);
            max-width: 1100px;
            margin: 0 auto;
        }
        .navbar-brand { font-weight: 800; font-size: 1.2rem; color: var(--sj-dark) !important; }
        .nav-link { 
            color: var(--sj-text) !important; 
            font-weight: 700; 
            font-size: 0.8rem;
            margin: 0 6px; 
            text-transform: uppercase;
        }
        .btn-login-portal {
            background: linear-gradient(135deg, var(--sj-primary), var(--sj-info));
            color: #fff !important;
            font-weight: 700;
            padding: 8px 22px;
            border-radius: 40px;
            font-size: 0.85rem;
            border: none;
            transition: 0.3s;
        }

        /* HERO SECTION */
        .hero-section { 
            position: relative;
            background: linear-gradient(90deg, rgba(15, 23, 42, 0.98) 0%, rgba(15, 23, 42, 0.75) 45%, rgba(15, 23, 42, 0.1) 100%), 
                        url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            padding: 180px 0 100px; 
            color: #fff;
        }
        .hero-badge-modern {
            display: inline-flex;
            align-items: center;
            background: rgba(13, 202, 240, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(13, 202, 240, 0.3);
            color: var(--sj-info);
            padding: 6px 20px;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .hero-section h1 { 
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 900; 
            line-height: 1.1; 
            margin-bottom: 1.2rem; 
        }
        .hero-section p { 
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85); 
            margin-bottom: 2.2rem; 
            max-width: 500px;
            line-height: 1.6;
        }

        /* SECTION TITLES */
        .section-header-title { font-weight: 900; color: var(--sj-dark); font-size: clamp(1.8rem, 4vw, 2.2rem); }
        .section-accent-line {
            width: 60px; height: 5px;
            background: linear-gradient(90deg, var(--sj-primary), var(--sj-info));
            border-radius: 10px; margin: 1rem auto 0;
        }
        .section-accent-line.start-align { margin-left: 0; }

        /* GLOBAL CARD HOVER (Goyang/Lift Effect) */
        .card-vision-mission, .pricing-card, .branch-modern-card, .flow-step-item, .gallery-photo-item {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }
        .card-vision-mission:hover, .pricing-card:hover, .branch-modern-card:hover, .flow-step-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(13, 110, 253, 0.12) !important;
        }

        /* VISI MISI */
        .card-vision-mission { 
            background: var(--sj-surface); border-radius: 20px; padding: 2.5rem; height: 100%;
            position: relative; overflow: hidden; border: 1px solid #f1f5f9;
        }
        .card-vision-mission::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(90deg, var(--sj-primary), var(--sj-info));
        }
        .card-icon-header {
            font-size: 2.2rem; color: var(--sj-primary); margin-bottom: 1.5rem;
            background: var(--sj-primary-soft); width: 65px; height: 65px;
            display: flex; align-items: center; justify-content: center; border-radius: 15px;
        }

        /* WHY US - FIXED PARAGRAPH POSITION */
        .why-us-content { padding: 80px 0; }
        .why-us-description { 
            font-size: 1.05rem; line-height: 1.8; color: var(--sj-text); 
            margin-bottom: 2rem; border-left: 4px solid var(--sj-info); padding-left: 1.5rem;
            max-width: 800px;
        }
        .stat-item-box {
            padding: 1.8rem; background: var(--sj-surface); border-radius: 20px; 
            border: 1px solid rgba(13, 202, 240, 0.08); box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        }

        /* PAKET PELATIHAN */
        .pricing-card { 
            background: var(--sj-surface); border: 1px solid #edf2f7; border-radius: 25px; 
            overflow: hidden; height: 100%;
        }
        .pricing-header-top { background-color: var(--sj-primary-soft); padding: 2rem 1.5rem 1.5rem; }
        .pricing-amount { font-size: 2.2rem; font-weight: 800; color: var(--sj-dark); }

        /* CABANG */
        .branch-modern-card { border-radius: 20px; overflow: hidden; background: var(--sj-surface); height: 100%; border: 1px solid #f1f5f9; }
        .branch-frame-img { width: 100%; height: 220px; object-fit: cover; transition: 0.5s; }
        .branch-modern-card:hover .branch-frame-img { transform: scale(1.05); }

        /* ALUR PENDAFTARAN - ASSETS FIXED */
        .steps-flow-container { position: relative; padding: 20px 0; }
        @media (min-width: 992px) {
            .steps-connector-dashed {
                position: absolute; top: 48%; left: 15%; width: 70%; height: 2px;
                border-top: 2px dashed #cbd5e1; z-index: 1;
            }
        }
        .flow-step-item { 
            text-align: center; padding: 2.5rem 1.5rem; background: var(--sj-surface); border-radius: 25px;
            height: 100%; border: 1px solid #f1f5f9; position: relative; z-index: 2;
        }
        .flow-icon-visual { font-size: 3rem; color: var(--sj-primary); margin-bottom: 1.5rem; display: inline-block; transition: 0.3s; }
        .flow-step-item:hover .flow-icon-visual { transform: scale(1.1); color: var(--sj-info); }

        /* GALLERY */
        .gallery-photo-item { position: relative; border-radius: 15px; overflow: hidden; height: 240px; border: 1px solid #f1f5f9; }
        .gallery-photo-item:hover { transform: scale(1.03); z-index: 5; }
        .gallery-hover-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, transparent 100%); display: flex; align-items: flex-end; padding: 1.5rem; opacity: 0; transition: 0.4s; }
        .gallery-photo-item:hover .gallery-hover-overlay { opacity: 1; }

        /* FOOTER */
        .footer-modern-section { background: var(--sj-surface); padding: 80px 0 40px; border-top: 1px solid #edf2f7; }
        .social-circle-btn { width: 45px; height: 45px; border-radius: 50%; color: #fff; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s; }
        .btn-social-ig { background: linear-gradient(45deg, #f09433, #bc1888); }
        .btn-social-fb { background: #1877F2; }
        .btn-social-wa { background: #25D366; }
        .social-circle-btn:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        @media (max-width: 991px) {
            .hero-section { padding: 160px 0 80px; text-align: center; }
            .hero-section p { margin: 0 auto 2rem; }
            .why-us-description { padding-left: 0; border-left: none; text-align: center; margin: 0 auto 2.5rem; }
        }
    </style>
</head>
<body>

    <div class="navbar-wrapper">
        <nav class="navbar navbar-expand-lg navbar-floating">
            <div class="container-fluid px-2">
                <a class="navbar-brand d-flex align-items-center text-decoration-none" href="#">
                    @if(isset($setting) && $setting->logo)
                        <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" alt="Logo" height="35" class="me-2 rounded">
                    @else
                        <i class="bi bi-car-front-fill me-2 fs-4" style="color: var(--sj-primary);"></i>
                    @endif
                    <span class="text-dark">{{ $setting->nama_website ?? 'Satria Jayanti' }}</span>
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon" style="width: 1.2em; height: 1.2em;"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="#profil">Profil</a></li>
                        <li class="nav-item"><a class="nav-link" href="#paket">Paket</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cabang">Lokasi</a></li>
                        <li class="nav-item"><a class="nav-link" href="#alur">Alur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#galeri">Gallery</a></li>
                    </ul>
                    <div class="d-flex mt-3 mt-lg-0">
                        <a href="{{ url('/login') }}" class="btn btn-login-portal w-100">Login</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-7">
                    <div class="hero-badge-modern"><i class="bi bi-shield-lock-fill me-2"></i>Sekolah Mengemudi Berstandar Nasional</div>
                    <h1>Berkendara Aman,<br>Mulai dari <span>Sini.</span></h1>
                    <p>PT. Satria Jayanti membentuk pengemudi berkarakter, tangkas, dan bertanggung jawab. Dibimbing instruktur profesional dengan armada tersertifikasi.</p>
                    <a href="{{ url('/register') }}" class="btn btn-primary px-5 py-3 rounded-pill shadow fw-bold border-0" style="background: var(--sj-primary);">Daftar Kursus <i class="bi bi-arrow-right-circle-fill ms-2"></i></a>
                </div>
            </div>
        </div>
    </section>

    <section id="profil" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-header-title">Profil Perusahaan</h2>
                <div class="section-accent-line mx-auto"></div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-5">
                    <div class="card-vision-mission">
                        <div class="card-icon-header"><i class="bi bi-rocket-takeoff-fill"></i></div>
                        <h4 class="fw-bold text-dark mb-3">Visi Kami</h4>
                        <p class="text-muted fst-italic">"Menjadi lembaga pendidikan dan pelatihan sekolah mengemudi yang unggul dan mampu bersaing di tingkat nasional."</p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card-vision-mission">
                        <div class="card-icon-header"><i class="bi bi-award-fill"></i></div>
                        <h4 class="fw-bold text-dark mb-3">Misi Kami</h4>
                        <ul class="list-unstyled text-muted small mb-0">
                            <li class="mb-2 d-flex align-items-start"><i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i> <span>Menyiapkan pengemudi aman, sopan, dan bertanggung jawab.</span></li>
                            <li class="mb-2 d-flex align-items-start"><i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i> <span>Menjadikan SATRIA JAYANTI sebagai pusat kursus unggulan.</span></li>
                            <li class="d-flex align-items-start"><i class="bi bi-patch-check-fill text-primary me-2 mt-1"></i> <span>Lembaga terpercaya, profesional, dan responsif terhadap masyarakat.</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="why-us-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-4 text-center text-lg-start">
                    <h2 class="section-header-title">Mengapa Memilih Kami?</h2>
                    <div class="section-accent-line start-align mb-4"></div>
                    <p class="why-us-description">
                        Kami mengintegrasikan <strong>kurikulum Nasional (SKKNI)</strong> yang berorientasi pada pembentukan karakter. Didukung oleh tenaga pendidik berpengalaman, menguasai medan, dan ditunjang fasilitas memadai untuk membentuk Anda menjadi pengemudi berpengetahuan luas.
                    </p>
                </div>
            </div>
            <div class="row g-4 text-center">
                <div class="col-4">
                    <div class="stat-item-box shadow-sm">
                        <h3 class="fw-bold text-primary mb-1">100%</h3>
                        <span class="small fw-bold text-muted text-uppercase tracking-tighter">Safety Focus</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item-box shadow-sm">
                        <h3 class="fw-bold text-primary mb-1">PRO</h3>
                        <span class="small fw-bold text-muted text-uppercase tracking-tighter">Certified</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="stat-item-box shadow-sm">
                        <h3 class="fw-bold text-primary mb-1">SKKNI</h3>
                        <span class="small fw-bold text-muted text-uppercase tracking-tighter">Standard</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="alur" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-header-title">Alur Pendaftaran</h2>
                <div class="section-accent-line mx-auto"></div>
            </div>
            <div class="steps-flow-container">
                <div class="steps-connector-dashed"></div>
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="flow-step-item shadow-sm">
                            <div class="flow-icon-visual"><i class="bi bi-person-plus-fill"></i></div>
                            <h6 class="fw-bold mb-2">Buat Akun</h6>
                            <p class="text-muted small mb-0">Daftarkan diri Anda di sistem kami.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="flow-step-item shadow-sm">
                            <div class="flow-icon-visual"><i class="bi bi-ui-checks-grid"></i></div>
                            <h6 class="fw-bold mb-2">Pilih Paket</h6>
                            <p class="text-muted small mb-0">Tentukan paket dan cabang terdekat.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="flow-step-item shadow-sm">
                            <div class="flow-icon-visual"><i class="bi bi-credit-card-2-front-fill"></i></div>
                            <h6 class="fw-bold mb-2">Pembayaran</h6>
                            <p class="text-muted small mb-0">Upload bukti bayar untuk verifikasi.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="flow-step-item shadow-sm">
                            <div class="flow-icon-visual" style="color: var(--sj-primary);">
                                <i class="bi bi-car-front-fill"></i>
                            </div>
                            <h6 class="fw-bold mb-2">Mulai Kursus</h6>
                            <p class="text-muted small mb-0">Mulai latihan dengan instruktur ahli kami.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
   <style>
    /* 🔥 ULTRA-PREMIUM UI CSS */
    .hover-lift { transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); border: 1px solid rgba(0,0,0,0.03); }
    .hover-lift:hover { transform: translateY(-10px); box-shadow: 0 1.5rem 4rem rgba(0,0,0,.1)!important; border-color: transparent; z-index: 10; }
    .filter-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(15px); border-radius: 1.5rem; border: 1px solid rgba(0,0,0,0.05); }
    .badge-premium { font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 800; }
    .text-gradient { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .btn-gradient { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; border: none; transition: all 0.3s ease; }
    .btn-gradient:hover { background: linear-gradient(135deg, #0a58ca 0%, #043f96 100%); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(13, 110, 253, 0.25); color: white; }
    .custom-select-icon { background-color: #f8fafc; border-radius: 0.75rem; transition: 0.3s; }
    .custom-select-icon:hover { background-color: #e9ecef; }
</style>

<section id="paket" class="py-5" style="background-color: #f8fafc; position: relative; overflow: hidden;">
    <div class="position-absolute top-0 start-0 translate-middle rounded-circle" style="width: 400px; height: 400px; background: radial-gradient(circle, rgba(13,110,253,0.05) 0%, rgba(255,255,255,0) 70%);"></div>
    <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle" style="width: 500px; height: 500px; background: radial-gradient(circle, rgba(25,135,84,0.03) 0%, rgba(255,255,255,0) 70%);"></div>

    <div class="container py-4 position-relative z-index-1">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary badge-premium px-3 py-2 mb-2 rounded-pill">Satria Jayanti Driving School</span>
            <h2 class="fw-bolder text-dark mt-2" style="font-size: 2.5rem; letter-spacing: -1px;">Pilihan Paket Pelatihan</h2>
            <div class="mx-auto mt-3 mb-4" style="width: 80px; height: 4px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); border-radius: 2px;"></div>
            <p class="text-muted" style="font-size: 1.1rem;">Sesuaikan kebutuhan belajar mengemudi Anda dengan instruktur profesional kami.</p>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="filter-panel shadow p-2 p-md-3">
                    <div class="row g-2">
                        <div class="col-md-6 position-relative">
                            <div class="custom-select-icon p-2 h-100">
                                <label class="small fw-bold text-muted text-uppercase mb-1 ms-2" style="font-size: 0.7rem;"><i class="bi bi-award-fill text-primary me-1"></i> Kategori Kelas</label>
                                <select class="form-select form-select-lg border-0 bg-transparent fw-bolder text-dark" id="filterKategori" style="cursor: pointer; box-shadow: none;">
                                    <option value="" selected disabled>-- Pilih Kategori --</option>
                                    <option value="Reguler">Tampilkan Paket Reguler</option>
                                    <option value="Non-Reguler">Tampilkan Paket Non-Reguler</option>
                                </select>
                            </div>
                            <div class="d-none d-md-block position-absolute top-50 end-0 translate-middle-y" style="width: 1px; height: 50%; background-color: rgba(0,0,0,0.1);"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="custom-select-icon p-2 h-100">
                                <label class="small fw-bold text-muted text-uppercase mb-1 ms-2" style="font-size: 0.7rem;"><i class="bi bi-car-front-fill text-primary me-1"></i> Jenis Transmisi</label>
                                <select class="form-select form-select-lg border-0 bg-transparent fw-bolder text-dark" id="filterTransmisi" style="cursor: pointer; box-shadow: none;">
                                    <option value="" selected disabled>-- Pilih Transmisi --</option>
                                    <option value="Semua">Semua Transmisi (Bebas)</option>
                                    <option value="Manual">Transmisi Manual</option>
                                    <option value="Matic">Transmisi Matic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center" id="paketContainer">
            
            <div class="col-12 text-center py-5" id="initialMessage">
                <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle bg-white shadow-sm mb-4" style="width: 100px; height: 100px;">
                    <i class="bi bi-hand-index text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h4 class="fw-bolder text-dark">Tentukan Pilihan Kelas</h4>
                <p class="text-muted mx-auto" style="max-width: 400px;">Silakan tentukan Kategori dan Transmisi pada panel di atas untuk membuka daftar harga paket eksklusif kami.</p>
            </div>

            @forelse($packages as $p)
                <div class="col-md-6 col-lg-4 paket-card d-none" data-kategori="{{ $p->kategori }}" data-transmisi="{{ $p->transmisi }}">
                    <div class="card h-100 bg-white rounded-4 shadow-sm hover-lift overflow-hidden position-relative border-0">
                        
                        <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-2">
                            @if($p->transmisi == 'Matic')
                                <span class="badge bg-warning text-dark rounded-pill shadow-sm badge-premium px-3 py-2 border border-warning"><i class="bi bi-gear-wide-connected me-1"></i> Matic</span>
                            @else
                                <span class="badge bg-danger text-white rounded-pill shadow-sm badge-premium px-3 py-2"><i class="bi bi-gear-fill me-1"></i> Manual</span>
                            @endif
                        </div>

                        <div class="bg-light pt-4 pb-2 px-4 border-bottom border-light">
                            <span class="badge {{ $p->kategori == 'Reguler' ? 'bg-primary' : 'bg-dark' }} badge-premium rounded-pill px-3 py-1 mb-2">
                                Paket {{ $p->kategori }}
                            </span>
                            <h5 class="fw-bolder mb-0 text-dark lh-sm" style="font-size: 1.35rem; min-height: 3rem;">{{ $p->nama_package }}</h5>
                        </div>

                        <div class="card-body p-4 d-flex flex-column text-start mt-2">
                            <div class="mb-4 text-center">
                                <span class="fs-6 text-muted fw-bold align-top">Rp</span>
                                <span class="display-5 fw-bolder text-gradient">{{ number_format($p->harga, 0, ',', '.') }}</span>
                            </div>

                            <ul class="list-unstyled text-muted mb-4 flex-grow-1">
                                <li class="mb-3 d-flex align-items-center">
                                    <div class="bg-success-subtle text-success rounded-circle p-1 me-3"><i class="bi bi-check2 d-flex"></i></div>
                                    <span class="fw-bold text-dark">{{ $p->pertemuan }}x Sesi Latihan</span>
                                </li>
                                <li class="mb-3 d-flex align-items-start">
                                    <div class="bg-primary-subtle text-primary rounded-circle p-1 me-3 mt-1"><i class="bi bi-journal-check d-flex"></i></div>
                                    <span>{{ $p->detail }}</span>
                                </li>
                                <li class="mb-3 d-flex align-items-center">
                                    <div class="bg-light text-secondary rounded-circle p-1 me-3"><i class="bi bi-person-badge d-flex"></i></div>
                                    Instruktur Tersertifikasi
                                </li>
                                <li class="mb-2 d-flex align-items-center">
                                    <div class="bg-light text-secondary rounded-circle p-1 me-3"><i class="bi bi-car-front d-flex"></i></div>
                                    Mobil Full AC & Safety
                                </li>
                            </ul>

                            <a href="{{ url('/register') }}" class="btn btn-gradient rounded-pill w-100 fw-bold py-3 mt-auto d-flex justify-content-center align-items-center">
                                Ambil Paket Ini <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 d-none empty-db-message">
                    <p class="text-muted fw-bold">Data paket belum tersedia di database saat ini.</p>
                </div>
            @endforelse
            
            <div class="col-12 text-center py-5 d-none" id="emptyFilterMessage">
                <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle bg-light mb-3" style="width: 80px; height: 80px;">
                    <i class="bi bi-search text-muted" style="font-size: 2rem;"></i>
                </div>
                <h5 class="fw-bold text-dark">Paket Tidak Ditemukan</h5>
                <p class="text-muted mx-auto" style="max-width: 350px;">Maaf, saat ini tidak ada paket yang sesuai dengan kombinasi Kategori dan Transmisi tersebut.</p>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterKategori = document.getElementById('filterKategori');
        const filterTransmisi = document.getElementById('filterTransmisi');
        const paketCards = document.querySelectorAll('.paket-card');
        const initialMessage = document.getElementById('initialMessage');
        const emptyMessage = document.getElementById('emptyFilterMessage');
        const emptyDbMessage = document.querySelector('.empty-db-message');

        function runFilter() {
            const valKategori = filterKategori.value;
            const valTransmisi = filterTransmisi.value;
            let visibleCount = 0;

            if (emptyDbMessage) {
                emptyDbMessage.classList.remove('d-none');
                return;
            }

            // Wajib pilih Kategori dulu agar paket muncul
            if (!valKategori) {
                if (initialMessage) initialMessage.classList.remove('d-none');
                paketCards.forEach(card => card.classList.add('d-none'));
                emptyMessage.classList.add('d-none');
                return;
            }

            if (initialMessage) initialMessage.classList.add('d-none');

            paketCards.forEach(card => {
                // Membaca langsung nilai murni dari atribut data (sinkron dengan ENUM)
                const cardKat = card.getAttribute('data-kategori');
                const cardTrans = card.getAttribute('data-transmisi');
                
                // Pencocokan ketat
                const matchKategori = (cardKat === valKategori);
                const matchTransmisi = (!valTransmisi || valTransmisi === 'Semua' || cardTrans === valTransmisi);

                if (matchKategori && matchTransmisi) {
                    card.classList.remove('d-none');
                    // Trik animasi masuk yang smooth
                    card.style.opacity = '0';
                    setTimeout(() => { card.style.transition = 'opacity 0.4s ease'; card.style.opacity = '1'; }, 50);
                    visibleCount++;
                } else {
                    card.classList.add('d-none');
                }
            });

            if (visibleCount === 0) {
                emptyMessage.classList.remove('d-none');
            } else {
                emptyMessage.classList.add('d-none');
            }
        }

        if (filterKategori) filterKategori.addEventListener('change', runFilter);
        if (filterTransmisi) filterTransmisi.addEventListener('change', runFilter);
    });
</script>

    <style>
    .branch-modern-card { 
        border-radius: 1.25rem; 
        border: 1px solid rgba(0,0,0,0.05); 
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
        background: #ffffff; 
        overflow: hidden; 
    }
    .branch-modern-card:hover { 
        transform: translateY(-8px); 
        box-shadow: 0 1.5rem 4rem rgba(13, 110, 253, 0.12)!important; 
        border-color: #0d6efd; 
    }
    .branch-frame-img { 
        width: 100%; 
        height: 220px; 
        object-fit: cover; 
        transition: transform 0.5s ease; 
    }
    .branch-modern-card:hover .branch-frame-img { 
        transform: scale(1.05); 
    }
    
    /* Tombol WhatsApp Green Gradient */
    .btn-wa-premium { 
        background: linear-gradient(135deg, #25D366 0%, #1ebe57 100%); 
        color: white; 
        border: none; 
        transition: all 0.3s ease; 
    }
    .btn-wa-premium:hover { 
        background: linear-gradient(135deg, #1ebe57 0%, #128c7e 100%); 
        color: white; 
        transform: translateY(-2px); 
        box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3); 
    }
    
    /* 🔥 Tombol Google Maps Premium - Menggunakan Google Brand Blue */
    .btn-maps-premium { 
        background: linear-gradient(135deg, #4285F4 0%, #1a73e8 100%); 
        color: white; 
        border: none; 
        transition: all 0.3s ease; 
    }
    .btn-maps-premium:hover { 
        background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); 
        color: white; 
        transform: translateY(-2px); 
        box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3); 
    }
</style>

<section id="cabang" class="py-5" style="background-color: #f8fafc;">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 mb-2 rounded-pill fw-bolder text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">Jangkauan Kami</span>
            <h2 class="fw-bolder text-dark mt-2" style="font-size: 2.2rem; letter-spacing: -0.5px;">Lokasi Jaringan Kami</h2>
            <div class="mx-auto mt-3 mb-3" style="width: 60px; height: 4px; background: linear-gradient(90deg, #0d6efd, #0dcaf0); border-radius: 2px;"></div>
            <p class="text-muted">Temukan cabang PT. Satria Jayanti terdekat di kota Anda. Kunjungi kami atau hubungi admin secara langsung.</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            @forelse($branches as $b)
            <div class="col-md-6 col-lg-4">
                <div class="branch-modern-card d-flex flex-column h-100 shadow-sm position-relative">
                    
                    <div class="position-relative overflow-hidden">
                        @if($b->foto)
                            <img src="{{ asset('storage/uploads/branches/'.$b->foto) }}" class="branch-frame-img" alt="{{ $b->nama_cabang }}">
                        @else
                            <div class="bg-light branch-frame-img d-flex align-items-center justify-content-center text-muted">
                                <i class="bi bi-buildings opacity-25" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        
                        <div class="position-absolute bottom-0 start-0 m-3 z-index-2">
                            <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill border fw-bold" style="font-size: 0.8rem;">
                                <i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $b->nama_cabang }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 flex-grow-1 d-flex flex-column">
                        <p class="text-primary fw-bolder small mb-2 text-uppercase"><i class="bi bi-signpost-2-fill me-1"></i> {{ $b->lokasi }}</p>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">{{ $b->detail ?? 'Cabang resmi pelatihan mengemudi PT. Satria Jayanti dengan fasilitas lengkap dan armada yang terawat.' }}</p>
                        
                        <div class="d-flex gap-2 mt-auto pt-3 border-top border-light">
                            @if($b->link_gmaps)
                                <a href="{{ $b->link_gmaps }}" target="_blank" class="btn btn-maps-premium w-100 rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.85rem;">
                                    <i class="bi bi-geo-alt-fill me-2"></i> Google Maps
                                </a>
                            @else
                                <button class="btn btn-light w-100 rounded-pill fw-bold py-2 text-muted border" disabled style="font-size: 0.85rem;">
                                    <i class="bi bi-map me-1"></i> Maps Kosong
                                </button>
                            @endif

                            @if($b->no_telp_admin)
                                @php
                                    // Mengubah angka 0 di depan menjadi 62 secara otomatis untuk link wa.me
                                    $waNumber = preg_replace('/^0/', '62', $b->no_telp_admin);
                                @endphp
                                <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-wa-premium w-100 rounded-pill fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.85rem;">
                                    <i class="bi bi-whatsapp me-2"></i> WhatsApp
                                </a>
                            @else
                                <button class="btn btn-light w-100 rounded-pill fw-bold py-2 text-muted border" disabled style="font-size: 0.85rem;">
                                    <i class="bi bi-whatsapp me-1"></i> WA Kosong
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="d-inline-flex align-items-center justify-content-center p-4 rounded-circle bg-white shadow-sm mb-3" style="width: 100px; height: 100px;">
                    <i class="bi bi-buildings text-muted opacity-50" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bolder text-dark">Cabang Belum Tersedia</h5>
                <p class="text-muted mx-auto" style="max-width: 400px;">Data lokasi cabang pelatihan sedang dalam proses pembaruan oleh sistem manajemen.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

    <section id="galeri" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-header-title">Galeri Satria Jayanti</h2>
                <div class="section-accent-line mx-auto"></div>
            </div>
            <div class="row g-3">
                @forelse($galleries as $g)
                <div class="col-6 col-md-3">
                    <div class="gallery-photo-item shadow-sm">
                        @if($g->foto)
                            <img src="{{ asset('storage/uploads/gallery/'.$g->foto) }}" alt="Galeri" class="w-100 h-100 object-fit-cover">
                        @else
                            <div class="bg-white h-100 d-flex align-items-center justify-content-center text-muted small">No Photo</div>
                        @endif
                        <div class="gallery-hover-overlay">
                            <h6 class="small text-white">{{ $g->judul ?? 'Kegiatan Kami' }}</h6>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">Galeri belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <footer class="footer-modern-section">
        <div class="container text-center">
            <div class="d-flex align-items-center justify-content-center mb-4">
                @if(isset($setting) && $setting->logo)
                    <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" alt="Logo Footer" height="45" class="me-3 rounded shadow-sm">
                @endif
                <h4 class="fw-bold text-dark mb-0 text-uppercase tracking-wider">{{ $setting->nama_website ?? 'Satria Jayanti' }}</h4>
            </div>
            <p class="mb-4 text-muted small" style="max-width: 500px; margin: 0 auto; line-height: 1.8;">Berkomitmen penuh membentuk pengemudi cerdas, berpengetahuan luas, dan bertanggung jawab.</p>
            
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="social-circle-btn btn-social-ig shadow-sm"><i class="bi bi-instagram fs-5"></i></a>
                <a href="#" class="social-circle-btn btn-social-fb shadow-sm"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="social-circle-btn btn-social-wa shadow-sm"><i class="bi bi-whatsapp fs-5"></i></a>
            </div>
            <div class="pt-3 border-top border-light opacity-75">
                <p class="small text-muted mb-0">&copy; {{ date('Y') }} {{ $setting->nama_website ?? 'Satria Jayanti' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>