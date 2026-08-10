<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sj-primary: #0d6efd; --sj-bg: #f8fafc; --sj-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04); }
        body { background-color: var(--sj-bg); font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        .sidebar { width: 280px; height: 100vh; position: fixed; background: #fff; border-right: 1px solid #e2e8f0; z-index: 1000; }
        .nav-link-custom { display: flex; align-items: center; padding: 0.85rem 1.5rem; color: #64748b; text-decoration: none; border-radius: 12px; margin: 0.2rem 0; transition: 0.3s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #eef2ff; color: var(--sj-primary); font-weight: 600; }
        .main-content { margin-left: 280px; padding: 2rem; min-height: 100vh; }
        .card-custom { border: none; border-radius: 20px; background: #fff; box-shadow: var(--sj-card-shadow); margin-bottom: 2rem; }
        .table > :not(caption) > * > * { padding: 1rem 1.5rem; }
    </style>
</head>
<body>
    <div class="sidebar p-3 shadow-sm">
        <div class="text-center py-4 mb-4">
            <img src="{{ asset('storage/uploads/settings/'.($setting->logo ?? 'default.png')) }}" alt="Logo" class="img-fluid" style="max-height: 45px;">
        </div>
        <nav>
            <small class="text-uppercase text-muted fw-bold px-3" style="font-size: 0.7rem;">Main Menu</small>
            <a href="{{ route('admin.dashboard') }}" class="nav-link-custom mt-2"><i class="bi bi-grid-fill me-3"></i> Dashboard</a>
            <a href="{{ url('/admin/siswa') }}" class="nav-link-custom"><i class="bi bi-people me-3"></i> Data Siswa</a>
            <a href="{{ url('/admin/instruktur') }}" class="nav-link-custom"><i class="bi bi-person-badge me-3"></i> Instruktur</a>
            <a href="{{ route('admin.jadwal.index') }}" class="nav-link-custom"><i class="bi bi-calendar-check me-3"></i> Penjadwalan</a>
            <a href="{{ url('/admin/keuangan') }}" class="nav-link-custom"><i class="bi bi-wallet2 me-3"></i> Keuangan</a>
            <a href="{{ url('/admin/settings') }}" class="nav-link-custom active"><i class="bi bi-gear-fill me-3"></i> Pengaturan</a>
        </nav>
        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold"><i class="bi bi-box-arrow-left me-2"></i> Keluar</button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <header class="mb-4">
            <h3 class="fw-bold text-dark m-0">Pengaturan Sistem</h3>
            <p class="text-muted m-0">Kelola paket kursus, cabang, galeri, dan identitas Satria Jayanti.</p>
        </header>

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan:</div>
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="bi bi-journal-text me-2 text-primary"></i> Daftar Paket Kursus</h5>
                        <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Paket
                        </button>
                    </div>
                    <div class="table-responsive px-2 pb-4">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Kategori</th>
                                    <th>Transmisi</th>
                                    <th>Sesi</th>
                                    <th>Harga</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($packages as $p)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $p->nama_package }}</span></td>
                                    <td>
                                        <span class="badge {{ $p->kategori == 'Non-Reguler' ? 'bg-info text-dark' : 'bg-secondary' }} rounded-pill px-3">
                                            {{ $p->kategori ?? 'Reguler' }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">{{ $p->transmisi ?? 'Manual' }}</span></td>
                                    <td class="fw-bold text-muted">{{ $p->pertemuan }} Sesi</td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalEditPaket{{ $p->id_package }}"><i class="bi bi-pencil text-primary"></i></button>
                                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="modal" data-bs-target="#modalHapusPaket{{ $p->id_package }}"><i class="bi bi-trash text-danger"></i></button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEditPaket{{ $p->id_package }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow-lg">
                                            <form action="{{ url('/admin/settings/update/package/'.$p->id_package) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header border-0">
                                                    <h5 class="fw-bold mb-0">Edit Paket Kursus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Nama Paket</label>
                                                        <input type="text" name="nama_package" class="form-control" value="{{ $p->nama_package }}" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold text-muted mb-1">Jumlah Sesi</label>
                                                            <input type="number" name="pertemuan" class="form-control" value="{{ $p->pertemuan }}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold text-muted mb-1">Harga (Rp)</label>
                                                            <input type="number" name="harga" class="form-control" value="{{ $p->harga }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold text-muted mb-1">Transmisi</label>
                                                            <select name="transmisi" class="form-select" required>
                                                                <option value="Manual" {{ $p->transmisi == 'Manual' ? 'selected' : '' }}>Manual</option>
                                                                <option value="Matic" {{ $p->transmisi == 'Matic' ? 'selected' : '' }}>Matic</option>
                                                                <option value="Manual & Matic" {{ $p->transmisi == 'Manual & Matic' ? 'selected' : '' }}>Manual & Matic</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small fw-bold text-muted mb-1">Kategori</label>
                                                            <select name="kategori" class="form-select" required>
                                                                <option value="Reguler" {{ ($p->kategori ?? 'Reguler') == 'Reguler' ? 'selected' : '' }}>Reguler</option>
                                                                <option value="Non-Reguler" {{ ($p->kategori ?? '') == 'Non-Reguler' ? 'selected' : '' }}>Non-Reguler (VIP)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="small fw-bold text-muted mb-1">Detail / Benefit</label>
                                                        <textarea name="detail" class="form-control" rows="3">{{ $p->detail }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalHapusPaket{{ $p->id_package }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 rounded-4">
                                            <div class="modal-body p-4 text-center">
                                                <i class="bi bi-exclamation-circle text-danger mb-3 d-block" style="font-size: 3rem;"></i>
                                                <h6 class="fw-bold mb-1">Hapus Paket Ini?</h6>
                                                <p class="small text-muted mb-4">Aksi ini tidak dapat dibatalkan.</p>
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ url('/admin/settings/delete/package/'.$p->id_package) }}" method="POST" class="w-100">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-custom shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Lokasi Cabang</h5>
                        <button class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahCabang">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Cabang
                        </button>
                    </div>
                    
                    <div class="table-responsive px-3 pb-4">
                        <table class="table table-hover align-middle mb-0">
                            <tbody>
                                @forelse($branches as $b)
                                <tr>
                                    <td style="width: 80px;">
                                        <img src="{{ asset('storage/uploads/branches/'. ($b->foto ?? $b->foto_cabang)) }}" class="rounded-3 shadow-sm border" style="width: 70px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <h6 class="fw-bold text-dark mb-1">{{ $b->nama_cabang }}</h6>
                                        <p class="small text-muted mb-1"><i class="bi bi-pin-map text-danger me-1"></i>{{ $b->lokasi }}</p>
                                        
                                        <div class="d-flex flex-wrap gap-2 mt-1">
                                            @if($b->link_gmaps) <span class="badge bg-danger-subtle text-danger" style="font-size: 0.65rem;"><i class="bi bi-map-fill me-1"></i>Maps OK</span> @endif
                                            @if($b->no_telp_admin) <span class="badge bg-success-subtle text-success" style="font-size: 0.65rem;"><i class="bi bi-whatsapp me-1"></i>WA OK</span> @endif
                                            <!-- 🔥 BADGE INDIKATOR QRIS -->
                                            @if($b->qris_image) <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle" style="font-size: 0.65rem;"><i class="bi bi-qr-code-scan me-1"></i>QRIS Aktif</span> @endif
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light border rounded-circle me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEditBranch{{ $b->id }}"><i class="bi bi-pencil-fill text-primary"></i></button>
                                        <button class="btn btn-sm btn-light border rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalHapusBranch{{ $b->id }}"><i class="bi bi-trash-fill text-danger"></i></button>
                                    </td>
                                </tr>
                                
                                <div class="modal fade" id="modalEditBranch{{ $b->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow-lg">
                                            <form action="{{ url('/admin/settings/update/branch/'.$b->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf @method('PUT')
                                                <div class="modal-header border-0 pb-0 pt-4 px-4">
                                                    <h5 class="fw-bold mb-0"><i class="bi bi-building-gear text-primary me-2"></i>Edit Data Cabang</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Nama Cabang</label>
                                                        <input type="text" name="nama_cabang" class="form-control bg-light" value="{{ $b->nama_cabang }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Alamat/Lokasi Lengkap</label>
                                                        <input type="text" name="lokasi" class="form-control bg-light" value="{{ $b->lokasi }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Detail Kontak Tambahan</label>
                                                        <input type="text" name="detail" class="form-control bg-light" value="{{ $b->detail }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Link Google Maps</label>
                                                        <div class="input-group shadow-sm">
                                                            <span class="input-group-text bg-white"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                                            <input type="url" name="link_gmaps" class="form-control border-start-0" value="{{ $b->link_gmaps }}" placeholder="https://maps.app.goo.gl/...">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">WhatsApp Admin Cabang</label>
                                                        <div class="input-group shadow-sm">
                                                            <span class="input-group-text bg-white"><i class="bi bi-whatsapp text-success"></i></span>
                                                            <input type="text" name="no_telp_admin" class="form-control border-start-0" value="{{ $b->no_telp_admin }}" placeholder="Contoh: 081234567890">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="small fw-bold text-muted mb-1">Ganti Foto Cabang (Opsional)</label>
                                                        <input type="file" name="foto" class="form-control" accept="image/*">
                                                    </div>
                                                    <!-- 🔥 FORM EDIT GAMBAR QRIS -->
                                                    <div class="mb-0">
                                                        <label class="small fw-bold text-muted mb-1">Upload / Ganti Gambar QRIS Cabang (Opsional)</label>
                                                        @if($b->qris_image)
                                                            <div class="mb-2">
                                                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle"><i class="bi bi-check-circle-fill me-1"></i>QRIS Cabang Saat Ini Tersedia</span>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="qris_image" class="form-control" accept="image/*">
                                                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Maksimal 2MB. Kosongkan jika tidak ingin merubah QRIS.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="modalHapusBranch{{ $b->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content border-0 rounded-4 shadow-lg">
                                            <div class="modal-body p-4 text-center">
                                                <div class="bg-danger-subtle rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                                                    <i class="bi bi-exclamation-triangle-fill text-danger fs-1"></i>
                                                </div>
                                                <h5 class="fw-bold mb-2">Hapus Cabang?</h5>
                                                <p class="small text-muted mb-4">Tindakan ini tidak dapat dibatalkan dan dapat mempengaruhi data siswa.</p>
                                                <div class="d-flex gap-2">
                                                    <button type="button" class="btn btn-light w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ url('/admin/settings/delete/branch/'.$b->id) }}" method="POST" class="w-100">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold shadow-sm">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-buildings fs-3 d-block mb-2 text-secondary"></i>
                                        Belum ada data cabang yang didaftarkan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card card-custom">
                    <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="bi bi-images me-2 text-success"></i> Galeri</h5>
                        <button class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahGallery">Unggah</button>
                    </div>
                    <div class="row g-2 px-4 pb-4">
                        @foreach($galleries as $g)
                        <div class="col-4 position-relative group">
                            <img src="{{ asset('storage/uploads/gallery/'.$g->foto) }}" class="rounded-3 shadow-sm w-100 object-fit-cover" style="height: 80px;">
                            <div class="position-absolute top-0 end-0 p-1">
                                <form action="{{ url('/admin/settings/delete/gallery/'.$g->id) }}" method="POST">@csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle p-1" style="line-height: 0;"><i class="bi bi-x" style="font-size: 0.8rem;"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-info-circle me-2 text-warning"></i> Identitas Perusahaan</h5>
                    <form action="{{ url('/admin/settings/update-general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Nama Aplikasi/PT</label>
                            <input type="text" name="nama_website" class="form-control" value="{{ $setting->nama_website ?? 'Satria Jayanti' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Ganti Logo Perusahaan</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold py-2 mt-2 shadow-sm">Simpan Identitas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH PAKET -->
    <div class="modal fade" id="modalTambahPaket" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ url('/admin/settings/add-package') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold mb-0">Tambah Paket Kursus Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Nama Paket</label>
                            <input type="text" name="nama_package" class="form-control rounded-3" placeholder="Contoh: Paket Pemula Manual" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Jumlah Sesi</label>
                                <input type="number" name="pertemuan" class="form-control rounded-3" placeholder="Cth: 10" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Harga (Rp)</label>
                                <input type="number" name="harga" class="form-control rounded-3" placeholder="Cth: 1500000" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Transmisi</label>
                                <select name="transmisi" class="form-select rounded-3" required>
                                    <option value="Manual">Manual</option>
                                    <option value="Matic">Matic</option>
                                    <option value="Manual & Matic">Manual & Matic</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Kategori</label>
                                <select name="kategori" class="form-select rounded-3" required>
                                    <option value="Reguler">Reguler</option>
                                    <option value="Non-Reguler">Non-Reguler (VIP/Bebas Jam)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted mb-1">Detail Paket (Opsional)</label>
                            <textarea name="detail" class="form-control rounded-3" rows="3" placeholder="Sebutkan fasilitas paket..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm">Tambahkan Paket</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH CABANG -->
    <div class="modal fade" id="modalTambahCabang" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ url('/admin/settings/add-branch') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="fw-bold mb-0">Daftarkan Cabang Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Nama Cabang</label>
                            <input type="text" name="nama_cabang" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Alamat/Lokasi Lengkap</label>
                            <input type="text" name="lokasi" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Detail Kontak Tambahan</label>
                            <input type="text" name="detail" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Link Google Maps (Opsional)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                                <input type="url" name="link_gmaps" class="form-control border-start-0" placeholder="https://maps.app.goo.gl/...">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">WhatsApp Admin Cabang (Opsional)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="bi bi-whatsapp text-success"></i></span>
                                <input type="text" name="no_telp_admin" class="form-control border-start-0" placeholder="Contoh: 081234567890">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Foto Cabang</label>
                            <input type="file" name="foto" class="form-control rounded-3" accept="image/*" required>
                        </div>
                        <!-- 🔥 FORM UPLOAD GAMBAR QRIS -->
                        <div class="mb-0">
                            <label class="small fw-bold text-muted mb-1">Foto QRIS Pembayaran (Opsional)</label>
                            <input type="file" name="qris_image" class="form-control rounded-3" accept="image/*">
                            <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Upload gambar QRIS khusus untuk cabang ini.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold">Simpan Cabang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH GALLERY -->
    <div class="modal fade" id="modalTambahGallery" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <form action="{{ url('/admin/settings/add-gallery') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="fw-bold mb-0">Tambah Foto Galeri</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Judul Foto</label>
                            <input type="text" name="judul" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted mb-1">File Gambar (Maks 2MB)</label>
                            <input type="file" name="image" class="form-control rounded-3" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">Unggah Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>