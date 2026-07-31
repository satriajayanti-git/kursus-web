<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Cuti - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-bg: #f8fafc; --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 24px; padding: 1.5rem; background: #fff; box-shadow: var(--sj-card-shadow); }
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
                <h4 class="fw-bold text-dark"><i class="bi bi-calendar-x me-2 text-danger"></i>Pengajuan Cuti & Izin Libur</h4>
                <p class="text-muted small">Ajukan cuti Anda dari jauh hari agar sistem tidak memplot nama Anda di jadwal siswa.</p>
            </div>

            @if(session('success')) 
                <div class="alert alert-success shadow-sm rounded-4 p-3 mb-4 fw-bold">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div> 
            @endif

            @if($errors->any())
                <div class="alert alert-danger shadow-sm rounded-4 p-3 mb-4 small fw-bold">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card card-custom border-top border-danger border-4">
                        <h6 class="fw-bold mb-3">Formulir Pengajuan</h6>
                        <form action="{{ url('/instruktur/cuti') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="small fw-bold mb-1 text-muted">Mulai Tgl</label>
                                    <input type="date" name="tanggal_mulai" class="form-control shadow-sm" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-6 mb-3">
                                    <!-- Diperbaiki menjadi tanggal_selesai -->
                                    <label class="small fw-bold mb-1 text-muted">Sampai Tgl</label>
                                    <input type="date" name="tanggal_selesai" class="form-control shadow-sm" min="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold mb-1 text-muted">Alasan Cuti / Kendala</label>
                                <textarea name="alasan" class="form-control shadow-sm bg-light" rows="3" placeholder="Contoh: Acara keluarga di luar kota, sakit, dll..." required></textarea>
                            </div>
                            <button class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">Kirim Pengajuan Cuti</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card card-custom">
                        <h6 class="fw-bold mb-3">Riwayat Cuti Anda</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-nowrap small">
                                <thead class="table-light">
                                    <tr>
                                        <th>Waktu Libur</th>
                                        <th>Alasan</th>
                                        <th class="text-center">Status ACC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cutis ?? [] as $c)
                                    <tr>
                                        <td class="fw-bold">
                                            <i class="bi bi-calendar-event text-secondary me-2"></i>
                                            <!-- Logic tampilan disesuaikan menggunakan tanggal_selesai -->
                                            @if($c->tanggal_mulai == $c->tanggal_selesai)
                                                {{ date('d M Y', strtotime($c->tanggal_mulai)) }}
                                            @else
                                                {{ date('d M', strtotime($c->tanggal_mulai)) }} - {{ date('d M Y', strtotime($c->tanggal_selesai)) }}
                                            @endif
                                        </td>
                                        <td class="text-muted text-truncate" style="max-width: 150px;">{{ $c->alasan }}</td>
                                        <td class="text-center">
                                            @if($c->status == 'Disetujui') <span class="badge bg-success rounded-pill">Disetujui</span>
                                            @elseif($c->status == 'Ditolak') <span class="badge bg-danger rounded-pill">Ditolak</span>
                                            @else <span class="badge bg-warning text-dark rounded-pill">Pending</span> @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4 fst-italic">Belum ada data pengajuan cuti.</td>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>