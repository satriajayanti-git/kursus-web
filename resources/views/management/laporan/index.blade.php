<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Global - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s; font-weight: 500; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #f1f5f9; color: #0d6efd; font-weight: 700; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('management.sidebar')

        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            <div class="header-top d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border-start border-primary border-5 mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Laporan Manajerial Global</h4>
                    <p class="text-muted small m-0">Filter dan cetak laporan operasional dari seluruh cabang.</p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-custom p-4 p-md-5 bg-white">
                        <div class="text-center mb-4">
                            <i class="bi bi-printer text-primary display-4"></i>
                            <h4 class="fw-bold mt-3">Generate Laporan</h4>
                        </div>

                        <form action="{{ url('/management/laporan/cetak') }}" method="POST" target="_blank">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="small fw-bold text-muted mb-2 text-uppercase">Jenis Laporan</label>
                                <select name="jenis_laporan" id="jenis_laporan" class="form-select form-select-lg shadow-sm" required onchange="toggleAdminFilter()">
                                    <option value="keuangan">Laporan Keuangan & Pendapatan</option>
                                    <option value="siswa">Laporan Pendaftaran Siswa Baru</option>
                                </select>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Filter Cabang</label>
                                    <!-- Tambahan fungsi onchange untuk sinkronisasi dengan dropdown admin -->
                                    <select name="branch_id" id="branch_id" class="form-select form-select-lg shadow-sm" onchange="syncAdminOptions()">
                                        <option value="">-- SEMUA CABANG --</option>
                                        @foreach($branches as $b) 
                                            <option value="{{ $b->id }}">{{ $b->nama_cabang }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="filter_admin_container">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Filter PIC Admin</label>
                                    <select name="admin_id" id="admin_id" class="form-select form-select-lg shadow-sm">
                                        <option value="">-- SEMUA ADMIN --</option>
                                        @foreach($admins as $a) 
                                            <!-- 🔥 REVISI: Tarik nama_admin dan tambahkan identifier data-branch -->
                                            <option value="{{ $a->id }}" data-branch="{{ $a->branch_id }}">
                                                {{ $a->nama_admin ?? $a->nama_lengkap }} ({{ $a->branch->nama_cabang ?? 'Pusat' }})
                                            </option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Dari Tanggal</label>
                                    <input type="date" name="tgl_awal" class="form-control form-control-lg shadow-sm" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="small fw-bold text-muted mb-2 text-uppercase">Sampai Tanggal</label>
                                    <input type="date" name="tgl_akhir" class="form-control form-control-lg shadow-sm" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold shadow"><i class="bi bi-file-pdf me-2"></i>Cetak Dokumen</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAdminFilter() {
            var jenis = document.getElementById('jenis_laporan').value;
            var adminContainer = document.getElementById('filter_admin_container');
            if (jenis === 'siswa') { adminContainer.style.display = 'none'; } 
            else { adminContainer.style.display = 'block'; }
        }

        // 🔥 LOGIC JAVASCRIPT: Menyesuaikan Dropdown Admin sesuai pilihan Cabang
        function syncAdminOptions() {
            var branchId = document.getElementById('branch_id').value;
            var adminSelect = document.getElementById('admin_id');
            var options = adminSelect.querySelectorAll('option');

            options.forEach(function(opt) {
                if(opt.value === "") {
                    opt.style.display = ""; // Option 'SEMUA ADMIN' biarkan muncul
                } else {
                    var adminBranch = opt.getAttribute('data-branch');
                    // Jika cabang 'SEMUA CABANG' diplih, tampilkan semua admin
                    if(branchId === "" || adminBranch === branchId) {
                        opt.style.display = "";
                    } else {
                        opt.style.display = "none";
                    }
                }
            });

            // Reset otomatis ke 'SEMUA ADMIN' jika admin yg sedang dipilih ikut tersembunyi
            var selectedOption = adminSelect.options[adminSelect.selectedIndex];
            if(selectedOption && selectedOption.style.display === "none") {
                adminSelect.value = "";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>