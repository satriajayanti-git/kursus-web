<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa - CMS Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root { --sj-primary: #0d6efd; --sj-bg: #f8fafc; }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; }
        .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .table > :not(caption) > * > * { padding: 1rem 1rem; }
        /* Style tambahan untuk kolom terkunci */
        .select-locked { background-color: #e9ecef !important; cursor: not-allowed !important; opacity: 1 !important; color: #6c757d; }
    </style>
</head>
<body>
    <div class="d-flex">
        @include('admin.sidebar')
        
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Kelola Data Siswa</h3>
                    <p class="text-muted m-0 small">Pantau semua siswa yang terdaftar di cabang Anda.</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="badge bg-primary px-4 py-2 fs-6 rounded-pill shadow-sm me-2">Total: {{ $students->count() }} Siswa</span>
                    <button class="btn btn-success fw-bold rounded-pill shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambahSiswa">
                        <i class="bi bi-person-plus-fill me-2"></i>Daftar Siswa Offline
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 p-3 rounded-4">
                <form action="{{ url('/admin/siswa') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik nama siswa atau ID (Cth: SJN072601)..." value="{{ $search ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm">Cari</button>
                </form>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 fw-bold mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-custom overflow-hidden">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-uppercase small fw-bold text-muted">
                                    <th class="ps-4">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kontak</th>
                                    <th>Lokasi Cabang</th>
                                    <th>Paket & Kategori</th>
                                    <th>Status Akun</th>
                                    <th>Username</th>
                                    <th>Tgl Daftar</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $s)
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $s->nama_lengkap }}</div>
                                        <span class="badge bg-secondary mb-1">{{ $s->id_siswa ?? 'SJN-LAMA' }}</span>
                                        <div class="small text-muted">{{ $s->email }}</div>
                                    </td>
                                    <td>
                                        @php 
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $s->no_telp);
                                            $waNumber = preg_replace('/^0/', '62', $cleanPhone); 
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="small fw-bold text-dark">{{ $s->no_telp }}</span>
                                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-2 py-0 shadow-sm d-flex align-items-center gap-1" style="font-size: 0.7rem; text-decoration: none;">
                                                <i class="bi bi-whatsapp"></i> Chat
                                            </a>
                                        </div>
                                    </td>
                                    <td><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $s->branch->nama_cabang ?? 'N/A' }}</td>
                                    
                                    <td>
                                        <div class="fw-bold small text-dark">{{ $s->package->nama_package ?? 'Belum Pilih' }}</div>
                                        <span class="badge {{ ($s->package->kategori ?? '') == 'Non-Reguler' ? 'bg-info text-dark' : 'bg-secondary' }} rounded-pill" style="font-size: 0.7rem;">
                                            {{ $s->package->kategori ?? 'Reguler' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($s->status == 'Aktif')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Aktif</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td><span class="badge bg-light text-dark border fw-medium">{{ $s->username }}</span></td>
                                    <td class="small text-muted">{{ $s->created_at->format('d M Y') }}</td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-circle p-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditSiswa{{ $s->id }}" title="Edit Siswa">
                                                <i class="bi bi-pencil-fill" style="line-height: 0;"></i>
                                            </button>

                                            <form action="{{ url('/admin/siswa/'.$s->id) }}" method="POST" id="formDelete{{ $s->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-outline-danger btn-sm rounded-circle p-2 shadow-sm" title="Hapus Siswa" onclick="confirmDelete({{ $s->id }}, '{{ $s->nama_lengkap }}')">
                                                    <i class="bi bi-trash-fill" style="line-height: 0;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- 🔥 MODAL EDIT SISWA -->
                                <div class="modal fade" id="modalEditSiswa{{ $s->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 rounded-4 shadow-lg">
                                            <form action="{{ url('/admin/siswa/'.$s->id) }}" method="POST" id="formEdit{{ $s->id }}" onsubmit="event.preventDefault(); confirmUpdate({{ $s->id }}, '{{ $s->nama_lengkap }}');">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-primary text-white border-0">
                                                    <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Data Siswa</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4 text-start">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Nama Lengkap Siswa</label>
                                                            <input type="text" name="nama_lengkap" class="form-control shadow-sm" value="{{ $s->nama_lengkap }}" required>
                                                        </div>

                                                        <!-- 🔥 LOGIC BARU: MENGUNCI PILIHAN PAKET JIKA AKUN AKTIF -->
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Pilih Paket Kursus</label>
                                                            @if($s->status == 'Aktif')
                                                                <!-- Form input disembunyikan agar nilai asli tetap terkirim saat disubmit -->
                                                                <input type="hidden" name="id_package" value="{{ $s->id_package }}">
                                                                <!-- Tampilan Dummy yang di-disable untuk indikator User Friendly -->
                                                                <select class="form-select shadow-sm select-locked" disabled>
                                                                    @foreach(\App\Models\Package::all() as $pkg)
                                                                        <option value="{{ $pkg->id_package }}" {{ $s->id_package == $pkg->id_package ? 'selected' : '' }}>
                                                                            {{ $pkg->nama_package }} ({{ $pkg->transmisi }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-danger d-block mt-1 fw-bold" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-lock-fill me-1"></i>Terkunci (Paket aktif tidak dapat diubah)
                                                                </small>
                                                            @else
                                                                <select name="id_package" class="form-select shadow-sm border-primary" required>
                                                                    <option value="">-- Pilih Paket & Transmisi --</option>
                                                                    @foreach(\App\Models\Package::all() as $pkg)
                                                                        <option value="{{ $pkg->id_package }}" {{ $s->id_package == $pkg->id_package ? 'selected' : '' }}>
                                                                            {{ $pkg->nama_package }} ({{ $pkg->transmisi }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Status Non-Aktif: Paket bebas diubah.</small>
                                                            @endif
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Username (Untuk Login)</label>
                                                            <input type="text" name="username" class="form-control shadow-sm" value="{{ $s->username }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Password <span class="text-danger small fw-normal">(Isi jika ingin diubah)</span></label>
                                                            <input type="password" name="password" class="form-control shadow-sm" placeholder="Kosongkan jika tidak diubah">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Email Aktif</label>
                                                            <input type="email" name="email" class="form-control shadow-sm" value="{{ $s->email }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Nomor Telepon / WhatsApp</label>
                                                            <input type="text" name="no_telp" class="form-control shadow-sm" value="{{ $s->no_telp }}" required>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="small fw-bold text-muted mb-1">Status Akun</label>
                                                            <select name="status" class="form-select shadow-sm border-secondary">
                                                                <option value="Aktif" {{ $s->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                                                <option value="Non-Aktif" {{ $s->status == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                                            </select>
                                                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Ubah ke "Aktif" jika pembayaran telah dikonfirmasi manual.</small>
                                                        </div>
                                                        <div class="col-md-12 mb-2">
                                                            <label class="small fw-bold text-muted mb-1">Alamat Domisili</label>
                                                            <textarea name="alamat" class="form-control shadow-sm" rows="3" required>{{ $s->alamat }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light border-0">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="bi bi-save-fill me-2"></i>Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <div class="mb-2"><i class="bi bi-inbox fs-1"></i></div>
                                        <p class="fst-italic m-0">Belum ada siswa yang mendaftar di cabang Anda.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔥 MODAL TAMBAH SISWA (WALK-IN) -->
    <div class="modal fade" id="modalTambahSiswa" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ url('/admin/siswa') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-2"></i>Pendaftaran Siswa Offline (Walk-in)</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-light border border-success-subtle small mb-4 text-dark shadow-sm">
                            <i class="bi bi-info-circle-fill text-success me-2"></i> 
                            Siswa yang didaftarkan melalui form ini akan otomatis mendapatkan <strong>Tagihan Baru</strong> di menu Keuangan. Status akun akan tetap <strong>Non-Aktif</strong> hingga Admin mengunggah bukti bayar dan melakukan konfirmasi Lunas.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Nama Lengkap Siswa</label>
                                <input type="text" name="nama_lengkap" class="form-control shadow-sm" required placeholder="Sesuai KTP">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Pilih Paket Kursus</label>
                                <select name="id_package" class="form-select shadow-sm border-success" required>
                                    <option value="">-- Pilih Paket & Transmisi --</option>
                                    @foreach(\App\Models\Package::all() as $pkg)
                                        <option value="{{ $pkg->id_package }}">
                                            {{ $pkg->nama_package }} ({{ $pkg->transmisi }}) - Rp {{ number_format($pkg->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Username (Untuk Login)</label>
                                <input type="text" name="username" class="form-control shadow-sm" required placeholder="Tanpa spasi">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Password</label>
                                <input type="password" name="password" class="form-control shadow-sm" required placeholder="Minimal 6 karakter">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Email Aktif</label>
                                <input type="email" name="email" class="form-control shadow-sm" required placeholder="Contoh: siswa@gmail.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small fw-bold text-muted mb-1">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="no_telp" class="form-control shadow-sm" required placeholder="08xxxxxx">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="small fw-bold text-muted mb-1">Alamat Domisili</label>
                                <textarea name="alamat" class="form-control shadow-sm" rows="3" required placeholder="Alamat lengkap siswa"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm"><i class="bi bi-save-fill me-2"></i>Daftarkan & Terbitkan Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Logic SweetAlert2 -->
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#198754',
                confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-4' }
            });
        @endif

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

        function confirmDelete(id, namaSiswa) {
            Swal.fire({
                title: 'Yakin hapus data ini?',
                html: `Semua data <b>${namaSiswa}</b> termasuk tagihan dan jadwal akan hilang permanen!`,
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
                    document.getElementById('formDelete' + id).submit();
                }
            });
        }

        function confirmUpdate(id, namaSiswa) {
            Swal.fire({
                title: 'Simpan Perubahan?',
                html: `Apakah Anda yakin ingin memperbarui data milik <b>${namaSiswa}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Cek Lagi',
                reverseButtons: true,
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formEdit' + id).submit();
                }
            });
        }
    </script>
</body>
</html>