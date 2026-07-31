<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4">
            <h3 class="fw-bold mb-1">Cetak Laporan</h3>
            <p class="text-muted small mb-4">Pilih jenis laporan dan rentang tanggal yang ingin dicetak.</p>

            <div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 600px;">
                <form action="{{ url('/admin/laporan/cetak') }}" method="POST" target="_blank">
                    @csrf
                    <div class="mb-4">
                        <label class="fw-bold small mb-2 text-primary">Pilih Jenis Laporan</label>
                        <select name="jenis_laporan" class="form-select shadow-sm" required>
                            <option value="siswa">Laporan Pendaftaran Siswa Baru</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold small mb-2">Dari Tanggal</label>
                            <input type="date" name="tgl_awal" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="fw-bold small mb-2">Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" class="form-control shadow-sm" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow"><i class="bi bi-printer-fill me-2"></i>Generate & Cetak Laporan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>