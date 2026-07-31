<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Karyawan - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Memanggil SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s; font-weight: 500; }
        .nav-link-custom:hover { background: #f1f5f9; color: #0d6efd; }
        .nav-link-custom.active { background: #e7f1ff; color: #0d6efd; font-weight: 700; border-left: 4px solid #0d6efd; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('management.sidebar')

        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border-start border-primary border-5 mb-4">
                <div>
                    <h4 class="fw-bold m-0 text-dark">Manajemen SDM</h4>
                    <p class="text-muted small m-0">Tambah, mutasi, atau hapus akun Admin dan Instruktur cabang.</p>
                </div>
                <button class="btn btn-primary rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#modalTambahKaryawan">
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Karyawan Baru
                </button>
            </div>

            <!-- Menyembunyikan Alert Bootstrap bawaan agar tergantikan elegan oleh SweetAlert -->
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4 fw-bold">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-custom p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Karyawan</th>
                                <th>Role / Posisi</th>
                                <th>Cabang Tugas</th>
                                <th>Kontak</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawans as $k)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $k->nama_lengkap }}</div>
                                    <div class="text-muted small">@ {{ $k->username }}</div>
                                </td>
                                <td>
                                    @if($k->role == 'admin')
                                        <span class="badge bg-danger rounded-pill px-3">ADMIN</span>
                                    @else
                                        <span class="badge bg-primary rounded-pill px-3">INSTRUKTUR ({{ $k->kategori_transmisi ?? 'Belum Diatur' }})</span>
                                    @endif
                                </td>
                                <td><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $k->branch->nama_cabang ?? '-' }}</td>
                                <td>{{ $k->no_telp }}</td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm me-1" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $k->id }}"><i class="bi bi-pencil-square text-primary"></i></button>
                                    
                                    <!-- Form Hapus Data Karyawan -->
                                    <form action="{{ url('/management/karyawan/'.$k->id) }}" method="POST" class="d-inline" id="formDelete{{ $k->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm" onclick="confirmDelete({{ $k->id }}, '{{ $k->nama_lengkap }}')">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalEdit{{ $k->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0"><h5 class="fw-bold">Edit / Mutasi Karyawan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <form action="{{ url('/management/karyawan/'.$k->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3"><label class="small fw-bold mb-1">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="{{ $k->nama_lengkap }}" required></div>
                                                <div class="mb-3"><label class="small fw-bold mb-1">Ubah Cabang Penugasan (Mutasi)</label>
                                                    <select name="branch_id" class="form-select" required>
                                                        @foreach($branches as $b) <option value="{{ $b->id }}" {{ $k->branch_id == $b->id ? 'selected' : '' }}>{{ $b->nama_cabang }}</option> @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3"><label class="small fw-bold mb-1">Jabatan</label>
                                                    <select name="role" class="form-select" required>
                                                        <option value="admin" {{ $k->role == 'admin' ? 'selected' : '' }}>Admin Cabang</option>
                                                        <option value="instruktur" {{ $k->role == 'instruktur' ? 'selected' : '' }}>Instruktur Lapangan</option>
                                                    </select>
                                                </div>
                                                @if($k->role == 'instruktur')
                                                <div class="mb-3"><label class="small fw-bold mb-1">Spesialisasi Transmisi</label>
                                                    <select name="kategori_transmisi" class="form-select">
                                                        <option value="Manual" {{ $k->kategori_transmisi == 'Manual' ? 'selected' : '' }}>Manual</option>
                                                        <option value="Matic" {{ $k->kategori_transmisi == 'Matic' ? 'selected' : '' }}>Matic</option>
                                                        <option value="Manual & Matic" {{ $k->kategori_transmisi == 'Manual & Matic' ? 'selected' : '' }}>Manual & Matic</option>
                                                    </select>
                                                </div>
                                                @endif
                                                <div class="mb-3"><label class="small fw-bold mb-1">No WhatsApp</label><input type="text" name="no_telp" class="form-control" value="{{ $k->no_telp }}" required></div>
                                                <div class="mb-0"><label class="small fw-bold mb-1">Ganti Password (Kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-control"></div>
                                            </div>
                                            <div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Perubahan</button></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTambahKaryawan" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white border-0"><h5 class="fw-bold mb-0">Daftarkan Karyawan Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <form action="{{ url('/management/karyawan') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3"><label class="small fw-bold mb-1">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">Username</label><input type="text" name="username" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">Email</label><input type="email" name="email" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">Password Awal</label><input type="password" name="password" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">No WhatsApp</label><input type="text" name="no_telp" class="form-control" required></div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">Jabatan</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin">Admin Cabang</option>
                                    <option value="instruktur">Instruktur Lapangan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3"><label class="small fw-bold mb-1">Penugasan Cabang</label>
                                <select name="branch_id" class="form-select" required>
                                    @foreach($branches as $b) <option value="{{ $b->id }}">{{ $b->nama_cabang }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-12"><label class="small fw-bold mb-1">Khusus Instruktur: Transmisi</label>
                                <select name="kategori_transmisi" class="form-select">
                                    <option value="">-- Pilih Jika Instruktur --</option>
                                    <option value="Manual">Manual</option>
                                    <option value="Matic">Matic</option>
                                    <option value="Manual & Matic">Manual & Matic</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0"><button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Daftarkan Sekarang</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Logic SweetAlert2 -->
    <script>
        // Menangkap Session Success dari Controller
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#0d6efd',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-4' }
            });
        @endif

        // Menangkap Session Error dari Controller
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-4' }
            });
        @endif

        // Fungsi Alert Konfirmasi Hapus Data
        function confirmDelete(id, namaKaryawan) {
            Swal.fire({
                title: 'Yakin hapus karyawan ini?',
                html: `Data akun <b>${namaKaryawan}</b> akan dihapus secara permanen dari sistem!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Eksekusi submit form jika user klik 'Ya, Hapus!'
                    document.getElementById('formDelete' + id).submit();
                }
            });
        }
    </script>
</body>
</html>