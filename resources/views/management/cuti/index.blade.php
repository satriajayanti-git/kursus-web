<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Approval Center Cuti - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .table-hover tbody tr:hover { background-color: #f1f5f9; }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s; font-weight: 500; }
        .nav-link-custom:hover { background: #f1f5f9; color: #0d6efd; }
        .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('management.sidebar')

        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="header-top d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border-start border-primary border-5 mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Approval Center Cuti</h4>
                    <p class="text-muted small m-0">Tinjau dan proses permohonan cuti dari Instruktur dan Admin cabang.</p>
                </div>
            </div>

            @if(session('success')) 
                <div class="alert alert-success border-0 shadow-sm rounded-4 p-3 mb-4 fw-bold">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div> 
            @endif

            <div class="card card-custom p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3">Identitas Karyawan</th>
                                <th class="py-3">Tanggal Cuti</th>
                                <th class="py-3">Alasan</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center">Aksi (Direksi)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cutis as $c)
                            <tr>
                                <td class="px-3">
                                    <div class="fw-bold text-dark">{{ $c->user->nama_lengkap ?? 'Dihapus' }}</div>
                                    <div class="small mt-1">
                                        @if($c->user->role == 'admin')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill">ADMINISTRATOR</span>
                                        @else
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill">INSTRUKTUR</span>
                                        @endif
                                        <span class="text-muted ms-1"><i class="bi bi-geo-alt-fill"></i> {{ $c->user->branch->nama_cabang ?? 'Pusat' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold"><i class="bi bi-calendar-event me-1"></i> {{ date('d M Y', strtotime($c->tanggal_mulai)) }}</div>
                                    <div class="small text-muted">s/d {{ date('d M Y', strtotime($c->tanggal_selesai)) }}</div>
                                </td>
                                <td style="max-width: 250px;"><small class="text-muted fw-medium">{{ $c->alasan }}</small></td>
                                <td class="text-center">
                                    @if($c->status == 'Disetujui') <span class="badge bg-success rounded-pill px-3 py-2">Disetujui</span>
                                    @elseif($c->status == 'Pending') <span class="badge bg-warning text-dark rounded-pill px-3 py-2">Menunggu Keputusan</span>
                                    @else <span class="badge bg-secondary rounded-pill px-3 py-2">Ditolak</span> @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCuti{{ $c->id }}">Tinjau</button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalCuti{{ $c->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-dark text-white border-0 rounded-top-4">
                                            <h5 class="modal-title fw-bold">Verifikasi Cuti Karyawan</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ url('/management/cuti/'.$c->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                
                                                <div class="bg-light border p-3 rounded-4 mb-4">
                                                    <div class="row text-start mb-2">
                                                        <div class="col-4 small text-muted">Pemohon</div>
                                                        <div class="col-8 fw-bold">{{ $c->user->nama_lengkap ?? '-' }} ({{ strtoupper($c->user->role) }})</div>
                                                    </div>
                                                    <div class="row text-start mb-2">
                                                        <div class="col-4 small text-muted">Cabang Tugas</div>
                                                        <div class="col-8 fw-bold text-primary">{{ $c->user->branch->nama_cabang ?? 'Pusat' }}</div>
                                                    </div>
                                                    <div class="row text-start">
                                                        <div class="col-4 small text-muted">Alasan</div>
                                                        <div class="col-8 small fw-medium fst-italic">"{{ $c->alasan }}"</div>
                                                    </div>
                                                </div>

                                                @if($c->user->role == 'admin')
                                                <div class="alert alert-danger border-0 rounded-4 small p-3 d-flex gap-3 mb-4">
                                                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                                    <div>
                                                        <strong>Perhatian Khusus!</strong><br>
                                                        Jika Anda menyetujui cuti ini, cabang <strong>{{ $c->user->branch->nama_cabang ?? 'Pusat' }}</strong> akan kehilangan Admin operasional. Anda WAJIB menugaskan Admin pengganti.
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="text-start mb-2">
                                                    <label class="fw-bold small mb-2 text-muted text-uppercase">Ubah Status</label>
                                                    <select name="status" class="form-select form-select-lg shadow-sm" required>
                                                        <option value="Pending" {{ $c->status == 'Pending' ? 'selected' : '' }}>Pending (Biarkan Menunggu)</option>
                                                        <option value="Disetujui" {{ $c->status == 'Disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                                                        <option value="Ditolak" {{ $c->status == 'Ditolak' ? 'selected' : '' }}>Tolak Permohonan</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0 rounded-bottom-4">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Simpan Keputusan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada pengajuan cuti saat ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>