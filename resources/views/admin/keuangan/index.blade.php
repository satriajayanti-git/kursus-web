<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Keuangan - CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <style>
        .accordion-button:not(.collapsed) { background-color: #f8fafc; color: #0d6efd; font-weight: bold; box-shadow: none; border-bottom: 1px solid #e9ecef; }
        .accordion-item { border-radius: 15px !important; overflow: hidden; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1rem; }
        .select2-container--bootstrap-5 .select2-selection { font-size: 0.875rem; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); border-color: #dee2e6; }
        .list-group-custom .list-group-item { border: none; border-radius: 12px !important; margin-bottom: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">
        @include('admin.sidebar')
        <div class="flex-grow-1 p-4" style="max-height: 100vh; overflow-y: auto;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0">Mutasi Keuangan & Pembayaran</h3>
                    <p class="text-muted small">Validasi bukti transfer berdasarkan histori mutasi bulanan siswa.</p>
                </div>
                <button class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTagihanModal">
                    <i class="bi bi-receipt-cutoff me-2"></i>Buat Tagihan Tambahan
                </button>
            </div>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-white shadow-sm p-3 border-start border-success border-4 rounded-4 border-0">
                        <small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">Total Omset Terverifikasi</small>
                        <h3 class="fw-bold text-success mb-1 mt-1">Rp {{ number_format($total_omset, 0, ',', '.') }}</h3>
                        <small class="text-muted small">Periode Mutasi: <strong>{{ date('M Y', strtotime($bulan)) }}</strong></small>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 p-3 rounded-4 bg-white">
                <form action="{{ url('/admin/keuangan') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-search me-1"></i> Cari Siswa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari nama, username..." value="{{ $search ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-calendar-range me-1"></i> Bulan Mutasi</label>
                        <input type="month" name="bulan" class="form-control bg-light fw-bold" value="{{ $bulan }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1"><i class="bi bi-filter-square me-1"></i> Status Mutasi</label>
                        <select name="status_bayar" class="form-select bg-light fw-bold">
                            <option value="Semua" {{ $status_bayar == 'Semua' || $status_bayar == '' ? 'selected' : '' }}>-- Semua Status --</option>
                            <option value="Pending" {{ $status_bayar == 'Pending' ? 'selected' : '' }}>⏳ Menunggu Verifikasi</option>
                            <option value="Lunas" {{ $status_bayar == 'Lunas' ? 'selected' : '' }}>✅ Lunas Selesai</option>
                            <option value="Ditolak" {{ $status_bayar == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak / Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm py-2"><i class="bi bi-sliders me-1"></i> Filter</button>
                    </div>
                </form>
            </div>

            @if(session('success')) <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold rounded-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div> @endif

            <!-- 🔥 ACCORDION MUTASI PER SISWA -->
            <div class="accordion" id="accordionKeuangan">
                @forelse($siswasMutasi as $siswa)
                    @php
                        $totalTagihanBulanIni = $siswa->pembayarans->sum('total_tagihan');
                        $totalLunasBulanIni = $siswa->pembayarans->where('status', 'Lunas')->sum('total_tagihan');
                        $adaPending = $siswa->pembayarans->where('status', 'Pending')->count() > 0;
                        
                        // Deteksi Bulk Payment (Grup berdasarkan bukti bayar)
                        $groupedPayments = $siswa->pembayarans->groupBy(function($item) {
                            return $item->bukti_bayar ? $item->bukti_bayar : 'single_'.$item->id;
                        });
                    @endphp
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed p-3 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSiswa{{ $siswa->id }}">
                                <div class="d-flex w-100 justify-content-between align-items-center pe-3">
                                    <div style="width: 30%;">
                                        <h6 class="fw-bolder text-dark mb-1">{{ $siswa->nama_lengkap }}</h6>
                                        <small class="text-muted fw-bold">{{ $siswa->package->nama_package ?? 'Tanpa Paket' }} ({{ $siswa->package->transmisi ?? '-' }})</small>
                                    </div>
                                    <div style="width: 25%;">
                                        <small class="text-muted d-block lh-1">Total Mutasi</small>
                                        <span class="fw-bold text-dark">Rp {{ number_format($totalTagihanBulanIni,0,',','.') }}</span>
                                    </div>
                                    <div style="width: 25%;">
                                        <small class="text-muted d-block lh-1">Sudah Dibayar</small>
                                        <span class="fw-bold text-success">Rp {{ number_format($totalLunasBulanIni,0,',','.') }}</span>
                                    </div>
                                    <div style="width: 20%; text-align: right;">
                                        @if($adaPending)
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-hourglass-split me-1"></i>PENDING</span>
                                        @elseif($totalTagihanBulanIni == $totalLunasBulanIni)
                                            <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-check-circle me-1"></i>LUNAS</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm"><i class="bi bi-x-circle me-1"></i>UNPAID</span>
                                        @endif
                                    </div>
                                </div>
                            </button>
                        </h2>
                        
                        <div id="collapseSiswa{{ $siswa->id }}" class="accordion-collapse collapse bg-light border-top" data-bs-parent="#accordionKeuangan">
                            <div class="accordion-body p-4">
                                <h6 class="fw-bold text-secondary mb-3 small text-uppercase">Rincian Mutasi ({{ date('F Y', strtotime($bulan)) }})</h6>
                                
                                <div class="list-group list-group-custom">
                                    @foreach($groupedPayments as $key => $group)
                                        @if($group->count() > 1 && !str_starts_with($key, 'single_'))
                                            <!-- 🔥 PEMBAYARAN GABUNGAN -->
                                            @php 
                                                $totalGroup = $group->sum('total_tagihan'); 
                                                $statusGroup = $group->first()->status; 
                                                $tglGroup = $group->first()->updated_at;
                                                $modalId = 'bulkModal_' . str_replace(['.', '-'], '_', $key);
                                            @endphp
                                            <div class="list-group-item p-3 border-start border-4 border-primary bg-white">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <small class="text-muted fw-bold"><i class="bi bi-calendar3 me-2"></i>{{ date('d M Y, H:i', strtotime($tglGroup)) }}</small><br>
                                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-2 mb-2 px-3 py-1"><i class="bi bi-collection-fill me-1"></i> PEMBAYARAN GABUNGAN</span>
                                                        <ul class="list-unstyled ms-3 small mb-0 border-start border-primary ps-3 border-2">
                                                            @foreach($group as $g)
                                                                <li class="mb-1 text-dark fw-bold">↳ {{ $g->keterangan }} <span class="text-success ms-1">(Rp {{ number_format($g->total_tagihan,0,',','.') }})</span></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div class="text-end">
                                                        <h5 class="fw-bolder mb-2 text-dark">Rp {{ number_format($totalGroup,0,',','.') }}</h5>
                                                        @if($statusGroup == 'Lunas') <span class="badge bg-success">Lunas Selesai</span>
                                                        @elseif($statusGroup == 'Ditolak') <span class="badge bg-danger">Bukti Ditolak</span>
                                                        @else <span class="badge bg-warning text-dark shadow-sm border border-warning">⏳ Menunggu Verifikasi</span> @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 mt-3 pt-3 border-top">
                                                    <a href="{{ asset('storage/uploads/bukti/'.$key) }}" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill fw-bold px-4"><i class="bi bi-eye me-2"></i>Lihat Bukti Transfer</a>
                                                    @if($statusGroup != 'Lunas')
                                                        <button class="btn btn-primary btn-sm rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}"><i class="bi bi-check2-circle me-2"></i>Verifikasi Gabungan</button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Modal Verifikasi Gabungan -->
                                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                                        <div class="modal-header bg-primary text-white border-0">
                                                            <h5 class="modal-title fw-bold">Verifikasi Pembayaran Gabungan</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ url('/admin/keuangan/bulk-verify') }}" method="POST">
                                                            @csrf
                                                            @foreach($group as $g) <input type="hidden" name="tagihan_ids[]" value="{{ $g->id }}"> @endforeach
                                                            <div class="modal-body p-4 text-center">
                                                                <img src="{{ asset('storage/uploads/bukti/'.$key) }}" class="img-fluid rounded border mb-4 shadow-sm" style="max-height: 250px;">
                                                                <div class="alert alert-info border-0 shadow-sm small text-start">
                                                                    Dengan menekan <strong>Simpan & Lunas</strong>, Anda akan memverifikasi {{ $group->count() }} tagihan sekaligus senilai Rp {{ number_format($totalGroup,0,',','.') }}.
                                                                </div>
                                                                <div class="text-start mb-3">
                                                                    <label class="fw-bold small mb-2 text-uppercase text-muted">Ubah Status</label>
                                                                    <select name="status" class="form-select shadow-sm" onchange="toggleAlasan(this, 'tolakBulk{{ $modalId }}')" required>
                                                                        <option value="Lunas">Lunas (Validasi Semua Tagihan)</option>
                                                                        <option value="Ditolak">Tolak Pembayaran</option>
                                                                    </select>
                                                                </div>
                                                                <div class="text-start" id="tolakBulk{{ $modalId }}" style="display: none;">
                                                                    <textarea name="penolakan" class="form-control shadow-sm border-danger" rows="2" placeholder="Alasan penolakan..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light border-0">
                                                                <button type="button" class="btn btn-secondary rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan & Eksekusi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            <!-- 🔥 PEMBAYARAN SATUAN (SINGLE) -->
                                            @php $p = $group->first(); @endphp
                                            <div class="list-group-item p-3 border-start border-4 bg-white {{ $p->status == 'Lunas' ? 'border-success' : ($p->status == 'Ditolak' ? 'border-danger' : 'border-warning') }}">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <small class="text-muted fw-bold"><i class="bi bi-calendar3 me-2"></i>{{ date('d M Y, H:i', strtotime($p->updated_at)) }}</small><br>
                                                        <span class="fw-bolder text-dark d-block mt-1">{{ $p->jenis_tagihan }}</span>
                                                        <small class="text-muted fw-bold">{{ preg_replace('/\s*\(Via(?: Bank)?:.*?\)/', '', $p->keterangan) }}</small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h6 class="fw-bolder mb-1 text-dark">Rp {{ number_format($p->total_tagihan,0,',','.') }}</h6>
                                                        @if($p->status == 'Lunas') <span class="badge bg-success">Lunas</span>
                                                        @elseif($p->status == 'Ditolak') <span class="badge bg-danger">Ditolak</span>
                                                        @elseif(!$p->bukti_bayar) <span class="badge bg-secondary">Unpaid / Belum Bayar</span>
                                                        @else <span class="badge bg-warning text-dark border border-warning">⏳ Proses ACC</span> @endif
                                                    </div>
                                                </div>
                                                <div class="mt-3 pt-3 border-top">
                                                    <button class="btn btn-outline-primary btn-sm rounded-pill fw-bold px-4" data-bs-toggle="modal" data-bs-target="#kelolaModal{{ $p->id }}"><i class="bi bi-pencil-square me-2"></i>Kelola & Verifikasi</button>
                                                </div>
                                            </div>

                                            <!-- Modal Verifikasi Satuan (Persis Seperti Asli) -->
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
                                                                    <a href="{{ asset('storage/uploads/bukti/'.$p->bukti_bayar) }}" target="_blank">
                                                                        <img src="{{ asset('storage/uploads/bukti/'.$p->bukti_bayar) }}" class="img-fluid rounded border mb-4 shadow-sm" style="max-height: 200px;">
                                                                    </a>
                                                                @else
                                                                    <div class="bg-warning-subtle p-3 rounded mb-4 text-start shadow-sm border border-warning">
                                                                        <span class="fw-bold text-dark"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Belum Ada Bukti Transfer</span>
                                                                        <p class="small mb-0 ms-4 text-muted mt-1">Siswa belum upload bukti bayar. Gunakan form di bawah jika siswa membayar tunai (Offline).</p>
                                                                    </div>
                                                                @endif
                                                                
                                                                <div class="text-start bg-light p-3 rounded border mb-3 shadow-sm">
                                                                    <label class="fw-bold small mb-2 text-primary"><i class="bi bi-cloud-upload me-1"></i> Upload Manual (Opsional)</label>
                                                                    <input type="file" name="bukti_bayar" class="form-control form-control-sm mb-2" accept="image/jpeg,image/png,image/jpg">
                                                                    <select name="metode_pembayaran" class="form-select form-select-sm">
                                                                        <option value="">- Metode Bayar Manual -</option>
                                                                        <option value="Tunai / Cash">Tunai / Cash (Offline)</option>
                                                                        <option value="BCA">Transfer BCA</option>
                                                                        <option value="BRI">Transfer BRI</option>
                                                                    </select>
                                                                </div>

                                                                @php $sudahAdaPelunasan = \App\Models\Pembayaran::where('user_id', $p->user_id)->where('keterangan', 'Pelunasan Sisa Pembayaran Paket Utama')->exists(); @endphp
                                                                
                                                                @if($p->jenis_tagihan == 'Paket Utama' && !$sudahAdaPelunasan && !$p->bukti_bayar)
                                                                <div class="text-start bg-white p-3 rounded border border-primary-subtle mb-4 shadow-sm">
                                                                    <label class="fw-bold small mb-2 text-primary"><i class="bi bi-pie-chart-fill me-1"></i> Set Pembayaran (DP)</label>
                                                                    <select name="jenis_bayar" class="form-select form-select-sm mb-2" onchange="toggleAdminDp(this, 'adminDpInput{{ $p->id }}')">
                                                                        <option value="full">Full Payment</option>
                                                                        <option value="dp">Down Payment (DP)</option>
                                                                    </select>
                                                                    <div id="adminDpInput{{ $p->id }}" style="display: none;">
                                                                        <input type="number" name="nominal_dp" class="form-control form-control-sm mt-2" placeholder="Nominal DP (Rp)" min="50000" max="{{ $p->total_tagihan - 10000 }}">
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                <div class="text-start mb-3 border-top pt-3">
                                                                    <label class="fw-bold small mb-2 text-muted">Status Validasi</label>
                                                                    <select name="status" class="form-select shadow-sm" onchange="toggleAlasan(this, 'alasanTolak{{ $p->id }}')" required>
                                                                        <option value="Pending" {{ $p->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="Lunas" {{ $p->status == 'Lunas' ? 'selected' : '' }}>Lunas (Setujui)</option>
                                                                        <option value="Ditolak" {{ $p->status == 'Ditolak' ? 'selected' : '' }}>Tolak Bukti</option>
                                                                    </select>
                                                                </div>

                                                                <div class="text-start mb-2" id="alasanTolak{{ $p->id }}" style="display: {{ $p->status == 'Ditolak' ? 'block' : 'none' }};">
                                                                    <textarea name="penolakan" class="form-control shadow-sm border-danger" rows="2" placeholder="Alasan penolakan...">{{ $p->penolakan }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light border-0">
                                                                <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Simpan & Validasi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                        <i class="bi bi-wallet2 display-3 text-muted opacity-25 d-block mb-3"></i>
                        <h5 class="fw-bold text-muted">Tidak Ada Mutasi</h5>
                        <p class="text-muted small">Belum ada siswa yang melakukan mutasi pembayaran pada periode bulan ini.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Tambah Tagihan Tambahan -->
    <div class="modal fade" id="tambahTagihanModal" tabindex="-1">
        <!-- Struktur sama persis seperti file asli -->
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
                            <select name="user_id" class="form-select select2-siswa-keuangan shadow-sm" required>
                                <option value="">-- Cari Siswa --</option>
                                @foreach($siswas as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_lengkap }} ({{ $s->username }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Keterangan Biaya</label>
                            <input type="text" name="keterangan" class="form-control shadow-sm" placeholder="Contoh: Pindah Transmisi, dll" required>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold mb-1">Nominal Tagihan (Rp)</label>
                            <input type="number" name="total_tagihan" class="form-control shadow-sm" min="1000" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Kirim Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2-siswa-keuangan').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#tambahTagihanModal'),
                placeholder: '-- Ketik Nama / Username Siswa --',
                width: '100%'
            });
        });

        function toggleAlasan(selectElement, targetDivId) {
            const targetDiv = document.getElementById(targetDivId);
            const textArea = targetDiv.querySelector('textarea');
            if (selectElement.value === 'Ditolak') {
                targetDiv.style.display = 'block';
                if(textArea) textArea.setAttribute('required', 'required');
            } else {
                targetDiv.style.display = 'none';
                if(textArea) textArea.removeAttribute('required');
            }
        }

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