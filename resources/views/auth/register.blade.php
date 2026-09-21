<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pendaftaran Siswa - {{ $setting->nama_website ?? 'Satria Jayanti' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        /* Base & Font */
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { 
            background-color: #f4f7f6; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-font-smoothing: antialiased;
        }
        
        /* Desktop Cover Image */
        .desktop-cover {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.85) 0%, rgba(11, 94, 215, 0.95) 100%), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?q=80&w=2070&auto=format&fit=crop') center/cover;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 4rem;
            color: white;
        }

        /* Mobile Header */
        .mobile-header {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            border-radius: 0 0 2rem 2rem;
            padding: 2.5rem 1.5rem 4rem 1.5rem;
            text-align: center;
            color: white;
            margin-bottom: -3rem;
            box-shadow: 0 4px 20px rgba(13,110,253,0.15);
        }

        /* Form Card Layout */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .auth-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            padding: 2.5rem;
            width: 100%;
            max-width: 540px;
            position: relative;
            z-index: 10;
        }

        /* Inputs (Touch-friendly & App-like) */
        .form-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        
        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            color: #94a3b8;
        }

        .form-control, .form-select { 
            border-radius: 12px; 
            padding: 14px 16px; 
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            font-size: 0.95rem;
            font-weight: 500;
            color: #334155;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus { 
            background-color: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
        }

        .input-group:focus-within .input-group-text {
            border-color: #3b82f6;
            background-color: #ffffff;
            color: #3b82f6;
        }

        /* Submit Button */
        .btn-register {
            background: #0d6efd;
            border: none;
            padding: 16px;
            font-weight: 700;
            border-radius: 14px;
            font-size: 1.05rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(13,110,253,0.2);
        }

        .btn-register:hover, .btn-register:active {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13,110,253,0.3);
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .auth-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            }
            .auth-wrapper {
                align-items: flex-start;
                padding-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-lg-5 d-none d-lg-flex desktop-cover">
                <div class="d-flex align-items-center gap-3 mb-4">
                    @if(isset($setting) && $setting->logo)
                        <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="55" class="bg-white p-2 rounded-3 shadow-sm" alt="Logo">
                    @else
                        <div class="bg-white text-primary rounded-3 d-inline-flex align-items-center justify-content-center p-2 shadow-sm" style="width: 55px; height: 55px;">
                            <i class="bi bi-steering fs-3"></i>
                        </div>
                    @endif
                    <h4 class="fw-bold mb-0 text-white">{{ $setting->nama_website ?? 'Satria Jayanti' }}</h4>
                </div>
                
                <div>
                    <h1 class="fw-bolder display-5 mb-3" style="letter-spacing: -1px;">Langkah Awal<br>Menguasai Jalanan.</h1>
                    <p class="fs-5 fw-light text-white-50 mb-0">Platform pendaftaran digital yang cepat, aman, dan transparan.</p>
                </div>
                <div class="small fw-bold text-white-50">
                    &copy; {{ date('Y') }} {{ $setting->nama_website ?? 'Satria Jayanti' }}
                </div>
            </div>

            <div class="col-lg-7 bg-light auth-wrapper p-3 p-md-5">
                
                <div class="w-100" style="max-width: 540px;">
                    <div class="d-block d-lg-none mobile-header">
                        @if(isset($setting) && $setting->logo)
                            <img src="{{ asset('storage/uploads/settings/'.$setting->logo) }}" height="45" class="mb-2 bg-white p-2 rounded-3 shadow-sm" alt="Logo">
                        @else
                            <i class="bi bi-steering fs-1 text-white mb-2 d-block"></i>
                        @endif
                        <h4 class="fw-bold mb-1">{{ $setting->nama_website ?? 'Satria Jayanti' }}</h4>
                        <p class="small text-white-50 mb-0">Pendaftaran Siswa Baru</p>
                    </div>

                    <div class="auth-card mx-auto">
                        
                        <div class="text-center text-lg-start mb-4 d-none d-lg-block">
                            <h3 class="fw-bolder text-dark mb-1">Buat Akun Baru</h3>
                            <p class="text-muted small fw-medium">Lengkapi data diri Anda di bawah ini.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-start p-3 bg-danger bg-opacity-10 text-danger">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-5 mt-1"></i>
                                <ul class="mb-0 ps-0 small fw-bold" style="list-style-type: none;">
                                    @foreach ($errors->all() as $error)
                                        <li class="mb-1">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ url('/register') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap (Sesuai KTP)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="nama_lengkap" class="form-control border-start-0 ps-0" placeholder="Contoh: Rikardo Wafi Nugroho" value="{{ old('nama_lengkap') }}" required>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                                        <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="buatusername" value="{{ old('username') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-whatsapp"></i></span>
                                        <input type="number" name="no_telp" class="form-control border-start-0 ps-0" placeholder="08xxxxxxxx" value="{{ old('no_telp') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- 🔥 PENAMBAHAN FIELD ALAMAT DOMISILI -->
                            <div class="mb-3">
                                <label class="form-label">Alamat Domisili</label>
                                <div class="input-group">
                                    <span class="input-group-text align-items-start pt-3"><i class="bi bi-house-door"></i></span>
                                    <textarea name="alamat" class="form-control border-start-0 ps-0" rows="2" placeholder="Masukkan alamat lengkap tempat tinggal saat ini..." required>{{ old('alamat') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi Cabang Latihan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                    <select name="branch_id" class="form-select border-start-0 ps-0 fw-bold" required>
                                        <option value="" selected disabled>-- Pilih Cabang Terdekat --</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Kategori Kelas</label>
                                <div class="input-group mb-1">
                                    <span class="input-group-text bg-white"><i class="bi bi-award-fill text-primary"></i></span>
                                    <select id="kategoriSelect" class="form-select border-start-0 ps-0 fw-bold text-dark" required>
                                        <option value="" selected disabled>-- Tentukan Kategori Terlebih Dahulu --</option>
                                        <option value="Reguler" {{ old('kategori') == 'Reguler' ? 'selected' : '' }}>Paket Reguler</option>
                                        <option value="Non-Reguler" {{ old('kategori') == 'Non-Reguler' ? 'selected' : '' }}>Paket Non-Reguler</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Paket Kursus</label>
                                <div class="input-group mb-1">
                                    <span class="input-group-text bg-white"><i class="bi bi-box-seam-fill text-primary"></i></span>
                                    <select name="package_id" id="packageSelect" class="form-select border-start-0 ps-0 fw-bold text-dark" required disabled>
                                        <option value="" selected disabled>-- Pilih Paket Pelatihan --</option>
                                        @foreach($packages as $package)
                                            <option value="{{ $package->id_package }}" data-kategori="{{ $package->kategori }}" {{ old('package_id') == $package->id_package ? 'selected' : '' }}>
                                                {{ $package->nama_package }} - Rp {{ number_format($package->harga, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-text mt-1 small text-muted"><i class="bi bi-info-circle me-1"></i>Invoice otomatis dikirim setelah pendaftaran.</div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const kategoriSelect = document.getElementById('kategoriSelect');
                                    const packageSelect = document.getElementById('packageSelect');
                                    
                                    // 1. Ekstrak dan amankan semua opsi paket bawaan Blade ke dalam array memory objek
                                    const masterPackages = Array.from(packageSelect.querySelectorAll('option'))
                                        .filter(opt => opt.value !== "") // Singkirkan placeholder awal
                                        .map(opt => ({
                                            value: opt.value,
                                            text: opt.textContent.trim(),
                                            kategori: opt.getAttribute('data-kategori'),
                                            isSelected: opt.hasAttribute('selected') || opt.selected
                                        }));

                                    // 2. Pasang fungsi pembuat filter dinamis
                                    function updatePackageDropdown() {
                                        const selectedKategori = kategoriSelect.value;
                                        
                                        // Jika belum ada kategori yang dipilih, biarkan dropdown paket terkunci
                                        if (!selectedKategori) {
                                            packageSelect.innerHTML = '<option value="" selected disabled>-- Pilih Paket Pelatihan --</option>';
                                            packageSelect.setAttribute('disabled', 'disabled');
                                            return;
                                        }

                                        // Bersihkan isi dropdown paket untuk diisi ulang dengan data hasil filter
                                        packageSelect.innerHTML = '';
                                        
                                        // Tambahkan placeholder default yang dinamis menyesuaikan kategori kelas
                                        const placeholderOpt = document.createElement('option');
                                        placeholderOpt.value = "";
                                        placeholderOpt.disabled = true;
                                        placeholderOpt.selected = true;
                                        placeholderOpt.textContent = `-- Pilih List Paket ${selectedKategori} --`;
                                        packageSelect.appendChild(placeholderOpt);

                                        // Filter data paket yang sesuai dengan nilai ENUM kategori database
                                        const filteredPackages = masterPackages.filter(pkg => pkg.kategori === selectedKategori);

                                        // Suntikkan kembali opsi paket hasil saringan ke dalam element DOM select
                                        filteredPackages.forEach(pkg => {
                                            const optionEl = document.createElement('option');
                                            optionEl.value = pkg.value;
                                            optionEl.textContent = pkg.text;
                                            
                                            // Amankan state seleksi jika proses registrasi sebelumnya terkena error validasi Laravel (old input)
                                            if (pkg.isSelected) {
                                                optionEl.selected = true;
                                                placeholderOpt.selected = false;
                                            }
                                            packageSelect.appendChild(optionEl);
                                        });

                                        // Aktifkan kembali dropdown paket agar bisa dipilih oleh pendaftar
                                        packageSelect.removeAttribute('disabled');
                                    }

                                    // 3. Daftarkan event listener trigger ketika pilihan kategori berubah
                                    kategoriSelect.addEventListener('change', updatePackageDropdown);

                                    // 4. OTOMATISASI RETENTION DATA: Jika ada old input (misal validasi email gagal), pastikan form tidak reset
                                    if (kategoriSelect.value) {
                                        updatePackageDropdown();
                                    }
                                });
                            </script>

                            <div class="mb-3">
                                <label class="form-label">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="nama@email.com" value="{{ old('email') }}" required>
                                </div>
                            </div>

                            <div class="mb-4 pb-2">
                                <label class="form-label">Password Akun</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Minimal 6 karakter" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-register w-100 text-white d-flex justify-content-between align-items-center">
                                <span>Daftar Sekarang</span>
                                <i class="bi bi-arrow-right-circle-fill fs-5"></i>
                            </button>
                            
                            <div class="text-center mt-4 pt-3 border-top">
                                <span class="text-muted small fw-medium">Sudah menjadi siswa?</span> 
                                <a href="{{ url('/login') }}" class="fw-bold text-primary text-decoration-none ms-1">Login di sini</a>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>