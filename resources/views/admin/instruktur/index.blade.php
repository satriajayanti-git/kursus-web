<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Instruktur - CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">Data Instruktur</h3>
                    <p class="text-muted small m-0">Pantau instruktur yang bertugas di cabang Anda dan reset password jika diperlukan.</p>
                </div>
            </div>

            @if(session('success')) 
                <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold rounded-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div> 
            @endif

            @if(session('error')) 
                <div class="alert alert-danger border-0 shadow-sm mb-4 fw-bold rounded-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                </div> 
            @endif

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3 px-4">Nama Instruktur</th>
                            <th class="py-3">Kontak</th>
                            <!-- 🔥 Kolom Baru Khusus Unit Pegangan -->
                            <th class="py-3">Unit Pegangan</th> 
                            <th class="py-3">Spesialisasi Transmisi</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $ins)
                        <tr>
                            <!-- 1. Kolom Instruktur (Hanya Nama & Username) -->
                            <td class="px-4">
                                <div class="fw-bold text-dark">{{ $ins->nama_lengkap }}</div>
                                <small class="text-muted fw-normal">&#64;{{ $ins->username }}</small>
                            </td>
                            
                            <!-- 2. Kolom Kontak -->
                            <td>
                                <span class="d-block small"><i class="bi bi-whatsapp text-success me-1"></i>{{ $ins->no_telp }}</span>
                                <small class="text-muted">{{ $ins->email }}</small>
                            </td>

                            <!-- 3. Kolom Unit Pegangan (Plat Nomor & Nama Kendaraan) -->
                            <td>
                                @if($ins->unit_pegangan)
                                    <div class="fw-bold text-primary"><i class="bi bi-car-front-fill me-1"></i>{{ $ins->unit_pegangan->nopol }}</div>
                                    <small class="text-muted">{{ $ins->unit_pegangan->nama_mobil }}</small>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border rounded-pill" style="font-size: 0.7rem;">
                                        <i class="bi bi-exclamation-circle me-1"></i>Instruktur Backup
                                    </span>
                                @endif
                            </td>

                            <!-- 4. Kolom Transmisi -->
                            <td>
                                @if($ins->kategori_transmisi == 'Manual & Matic')
                                    <span class="badge bg-success rounded-pill px-3">Manual & Matic</span>
                                @elseif($ins->kategori_transmisi == 'Matic')
                                    <span class="badge bg-info text-dark rounded-pill px-3">Khusus Matic</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3">Khusus Manual</span>
                                @endif
                            </td>

                            <!-- 5. Kolom Aksi -->
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#resetModal{{ $ins->id }}">
                                    <i class="bi bi-key-fill me-1"></i>Reset Password
                                </button>
                            </td>
                        </tr>

                        <!-- Modal HANYA untuk Reset Password -->
                        <div class="modal fade" id="resetModal{{ $ins->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header bg-warning border-0">
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-shield-lock-fill me-2"></i>Reset Password Instruktur</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ url('/admin/instruktur/'.$ins->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-body p-4 text-start">
                                            <div class="alert alert-light border border-warning text-dark small mb-4">
                                                Anda hanya diberikan akses untuk mereset password akun <strong>{{ $ins->nama_lengkap }}</strong>. Penambahan atau perubahan data diri hanya dapat dilakukan oleh Management Pusat.
                                            </div>

                                            <div class="mb-3">
                                                <label class="small fw-bold mb-1">Password Baru</label>
                                                <input type="password" name="password" class="form-control shadow-sm" placeholder="Masukkan password baru (min. 6 karakter)" required minlength="6">
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light border-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-warning px-4 fw-bold rounded-pill shadow-sm">Simpan Password</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <!-- Diubah jadi colspan 5 biar tabelnya lurus kalau kosong -->
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                Belum ada instruktur yang ditugaskan ke cabang ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>