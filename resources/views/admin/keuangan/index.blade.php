<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Keuangan - CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .table-custom { border-radius: 15px; overflow: hidden; border: none; }
        .card-stat { border-radius: 15px; border: none; }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">Mutasi Keuangan & Pembayaran</h3>
                    <p class="text-muted small">Kelola validasi bukti transfer siswa dengan skema mutasi bank per bulan.</p>
                </div>
                <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTagihanModal">
                    <i class="bi bi-receipt-cutoff me-2"></i>Buat Tagihan Tambahan
                </button>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card card-stat bg-white shadow-sm p-3 border-start border-success border-4">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Total Omset Terverifikasi</small>
                        <h3 class="fw-bold text-success mb-1 mt-1">Rp {{ number_format($total_omset, 0, ',', '.') }}</h3>
                        <small class="text-muted small">Periode: <strong>{{ date('M Y', strtotime($bulan)) }}</strong></small>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 p-3 rounded-4 bg-white">
                <form action="{{ url('/admin/keuangan') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-search me-1"></i> Cari Siswa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama, username, atau ID Siswa..." value="{{ $search ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-calendar-range me-1"></i> Periode Bulan Mutasi</label>
                        <input type="month" name="bulan" class="form-control bg-light fw-bold" value="{{ $bulan }}">
                    </div>

                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-filter-square me-1"></i> Status Transaksi</label>
                        <select name="status_bayar" class="form-select bg-light fw-bold">
                            <option value="Semua" {{ $status_bayar == 'Semua' || $status_bayar == '' ? 'selected' : '' }}>-- Semua Status --</option>
                            <option value="Pending" {{ $status_bayar == 'Pending' ? 'selected' : '' }}>⏳ Pending (Proses ACC)</option>
                            <option value="Lunas" {{ $status_bayar == 'Lunas' ? 'selected' : '' }}>✅ Lunas Disetujui</option>
                            <option value="Ditolak" {{ $status_bayar == 'Ditolak' ? 'selected' : '' }}>❌ Bukti Ditolak</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm py-2"><i class="bi bi-sliders me-1"></i> Filter Data</button>
                    </div>
                </form>
            </div>

            @if(session('success')) <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div> @endif

            <div class="card table-custom shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-4">Tanggal Masuk</th>
                                <th class="py-3">Nama Siswa</th>
                                <th class="py-3">Rincian Tagihan</th>
                                <th class="py-3">Total (Rp)</th>
                                <th class="py-3 text-center">Metode Bayar</th>
                                <th class="py-3 text-center">Status Bukti</th>
                                <th class="py-3 text-center">Aksi / Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembayarans as $p)
                            
                            @php
                                $metode = 'Belum Ada';
                                $ket_bersih = $p->keterangan;
                                
                                if (preg_match('/\(Via(?: Bank)?: (.*?)\)/', $p->keterangan, $matches)) {
                                    $metode = $matches[1];
                                    $ket_bersih = preg_replace('/\s*\(Via(?: Bank)?:.*?\)/', '', $p->keterangan);
                                } elseif ($p->bukti_bayar) {
                                    $metode = 'Upload Manual';
                                }
                            @endphp

                            <tr>
                                <td class="px-4 text-muted small fw-bold">{{ date('d M Y, H:i', strtotime($p->created_at)) }} WIB</td>
                                
                                <td class="fw-bold text-dark">
                                    @if($p->user && $p->user->id_siswa)
                                        <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">{{ $p->user->id_siswa }}</span><br>
                                    @endif
                                    {{ $p->user->nama_lengkap ?? 'Dihapus' }}
                                    @if($p->user && $p->user->username)
                                        <br><small class="text-muted fw-normal" style="font-size: 0.75rem;">(&#64;{{ $p->user->username }})</small>
                                    @endif
                                </td>

                                <td>
                                    @if($p->jenis_tagihan == 'Tambahan')
                                        <span class="badge bg-warning text-dark mb-1">Tagihan Tambahan</span><br>
                                        <small class="fw-bold text-muted text-wrap d-inline-block" style="max-width: 250px;">{{ $ket_bersih }}</small>
                                    @else
                                        <span class="badge bg-primary mb-1">Paket Utama</span><br>
                                        <small class="fw-bold text-muted">{{ $p->user->package->nama_package ?? 'Tanpa Paket' }}</small>
                                    @endif
                                </td>
                                <td class="fw-bold text-success">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</td>
                                
                                <td class="text-center">
                                    @if($metode == 'Belum Ada')
                                        <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.7rem;">Belum Bayar</span>
                                    @else
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-3 py-1 shadow-sm" style="font-size: 0.7rem;">
                                            <i class="bi bi-wallet2 me-1"></i>{{ $metode }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($p->status == 'Lunas') 
                                        <span class="badge bg-success rounded-pill px-3">Lunas Disetujui</span>
                                    @elseif($p->status == 'Ditolak')
                                        <span class="badge bg-danger rounded-pill px-3">Bukti Ditolak</span>
                                    @elseif(!$p->bukti_bayar) 
                                        <span class="badge bg-secondary rounded-pill px-3">Belum Dibayar</span>
                                    @else 
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu ACC</span> 
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#kelolaModal{{ $p->id }}">Cek Bukti</button>
                                </td>
                            </tr>

                            <div class="modal fade" id="kelolaModal{{ $p->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-primary text-white border-0">
                                            <h5 class="modal-title fw-bold">Verifikasi Pembayaran</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        
                                        <form action="{{ url('/admin/keuangan/'.$p->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4 text-center">
                                                
                                                @if($p->bukti_bayar)
                                                    <p class="small fw-bold text-muted mb-2">Bukti Transfer Saat Ini</p>
                                                    <a href="{{ asset('storage/uploads/bukti/'.$p->bukti_bayar) }}" target="_blank">
                                                        <img src="{{ asset('storage/uploads/bukti/'.$p->bukti_bayar) }}" class="img-fluid rounded border mb-4" style="max-height: 200px;">
                                                    </a>
                                                @else
                                                    <div class="bg-light p-3 rounded mb-4 text-muted border border-warning text-start shadow-sm">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4 me-2"></i>
                                                            <span class="fw-bold text-dark">Belum Ada Bukti Pembayaran</span>
                                                        </div>
                                                        <p class="small mb-0 ms-4">Siswa belum mengunggah bukti, atau siswa ini didaftarkan melalui jalur <strong>Offline (Admin)</strong>.</p>
                                                    </div>
                                                @endif
                                                
                                                <div class="text-start bg-light p-3 rounded border border-secondary-subtle mb-3 shadow-sm">
                                                    <label class="fw-bold small mb-2 text-uppercase text-primary"><i class="bi bi-cloud-upload me-1"></i> Upload / Ganti Bukti (Opsional)</label>
                                                    <input type="file" name="bukti_bayar" class="form-control form-control-sm mb-2" accept="image/jpeg,image/png,image/jpg">
                                                    
                                                    <select name="metode_pembayaran" class="form-select form-select-sm shadow-sm">
                                                        <option value="">-- Metode Pembayaran (Pilih Jika Upload Bukti) --</option>
                                                        <option value="Tunai / Cash">Tunai / Cash (Bayar di Cabang)</option>
                                                        <option value="BCA">Transfer BCA</option>
                                                        <option value="BRI">Transfer BRI</option>
                                                        <option value="QRIS">Scan QRIS</option>
                                                    </select>
                                                    <small class="text-muted lh-1 mt-1 d-block" style="font-size: 0.7rem;">Gunakan opsi ini jika menerima uang pendaftaran siswa secara Offline.</small>
                                                </div>

                                                <!-- 🔥 TAMBAHAN LOGIC SPLIT DP UNTUK ADMIN -->
                                                @php
                                                    $sudahAdaPelunasan = \App\Models\Pembayaran::where('user_id', $p->user_id)
                                                        ->where('keterangan', 'Pelunasan Sisa Pembayaran Paket Utama')
                                                        ->exists();
                                                @endphp
                                                
                                                @if($p->jenis_tagihan == 'Paket Utama' && !$sudahAdaPelunasan)
                                                <div class="text-start bg-white p-3 rounded border border-primary-subtle mb-4 shadow-sm">
                                                    <label class="fw-bold small mb-2 text-uppercase text-primary"><i class="bi bi-pie-chart-fill me-1"></i> Set Pembayaran (DP/Full)</label>
                                                    <select name="jenis_bayar" class="form-select shadow-sm mb-2" onchange="toggleAdminDp(this, 'adminDpInput{{ $p->id }}')">
                                                        <option value="full">Full Payment (Sesuai Tagihan)</option>
                                                        <option value="dp">Down Payment (DP)</option>
                                                    </select>
                                                    <div id="adminDpInput{{ $p->id }}" style="display: none;">
                                                        <label class="fw-bold small mb-1 text-muted">Nominal DP yang Diterima (Rp)</label>
                                                        <input type="number" name="nominal_dp" class="form-control shadow-sm" placeholder="Contoh: 500000" min="50000" max="{{ $p->total_tagihan - 10000 }}">
                                                        <small class="text-danger d-block mt-1 lh-1" style="font-size:0.7rem;"><i class="bi bi-info-circle me-1"></i>Sisa tagihan otomatis dipecah dan dibuatkan tagihan Pelunasan ke dashboard siswa.</small>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="text-start mb-3 border-top pt-3">
                                                    <label class="fw-bold small mb-2 text-uppercase text-muted">Ubah Status Invoice</label>
                                                    <select name="status" class="form-select shadow-sm" onchange="toggleAlasan(this, 'alasanTolak{{ $p->id }}')" required>
                                                        <option value="Pending" {{ $p->status == 'Pending' ? 'selected' : '' }}>Pending (Menunggu/Belum Lunas)</option>
                                                        <option value="Lunas" {{ $p->status == 'Lunas' ? 'selected' : '' }}>Lunas (ACC & Aktifkan Akun Siswa)</option>
                                                        <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>Tolak Pembayaran</option>
                                                    </select>
                                                </div>

                                                <div class="text-start mb-3" id="alasanTolak{{ $p->id }}" style="display: {{ $p->status == 'Ditolak' ? 'block' : 'none' }};">
                                                    <label class="fw-bold small mb-2 text-uppercase text-danger"><i class="bi bi-exclamation-circle me-1"></i> Alasan Penolakan</label>
                                                    <textarea name="penolakan" class="form-control shadow-sm border-danger" rows="2" placeholder="Contoh: Bukti transfer buram, mohon upload foto yang lebih jelas...">{{ $p->penolakan }}</textarea>
                                                    <small class="text-muted" style="font-size: 0.75rem;">Siswa akan melihat pesan ini di dashboard mereka.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light border-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Keputusan & Validasi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">Tidak ditemukan mutasi transaksi pada periode filter bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH TAGIHAN TAMBAHAN -->
    <div class="modal fade" id="tambahTagihanModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Buat Tagihan Tambahan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/admin/keuangan/tambahan') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Pilih Siswa</label>
                            <select name="user_id" class="form-select shadow-sm" required>
                                <option value="">-- Cari Siswa --</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Keterangan Biaya</label>
                            <input type="text" name="keterangan" class="form-control shadow-sm" placeholder="Contoh: Pembuatan SIM A, Pindah Transmisi, dll" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Nominal Tagihan (Rp)</label>
                            <input type="number" name="total_tagihan" class="form-control shadow-sm" placeholder="Contoh: 150000" min="1000" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Kirim Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleAlasan(selectElement, targetDivId) {
            const targetDiv = document.getElementById(targetDivId);
            const textArea = targetDiv.querySelector('textarea');
            
            if (selectElement.value === 'Ditolak') {
                targetDiv.style.display = 'block';
                textArea.setAttribute('required', 'required');
            } else {
                targetDiv.style.display = 'none';
                textArea.removeAttribute('required');
            }
        }

        // 🔥 FUNGSI TOGGLE INPUT DP UNTUK ADMIN
        function toggleAdminDp(selectElement, targetId) {
            const dpInput = document.getElementById(targetId);
            if (selectElement.value === 'dp') {
                dpInput.style.display = 'block';
                dpInput.querySelector('input').setAttribute('required', 'required');
            } else {
                dpInput.style.display = 'none';
                dpInput.querySelector('input').removeAttribute('required');
            }
        }
    </script>
</body>
</html>