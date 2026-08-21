<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; font-family: 'Times New Roman', Times, serif; color: #000; }
        .kop-surat { border-bottom: 3px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .table-laporan th, .table-laporan td { border: 1px solid #000 !important; padding: 8px; font-size: 14px; vertical-align: middle; }
        .table-laporan th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; text-align: center; }
        
        .badge-print { 
            display: inline-block; padding: 2px 6px; font-size: 12px; font-weight: bold; 
            border-radius: 4px; border: 1px solid #000; 
            background-color: #e9ecef !important; -webkit-print-color-adjust: exact; color: #000; 
        }
        .text-sm { font-size: 12.5px; color: #444; }

        @media print { .no-print { display: none !important; } body { margin: 0; padding: 20px; } }
    </style>
</head>
<body onload="window.print()">

    <div class="container-fluid py-4">
        <div class="mb-4 no-print text-center">
            <button onclick="window.close()" class="btn btn-secondary btn-sm rounded-pill px-4">Kembali / Tutup</button>
        </div>

        <div class="kop-surat text-center">
            <h2 class="fw-bold mb-1 text-uppercase">{{ $setting->nama_website ?? 'PT. SATRIA JAYANTI' }}</h2>
            <p class="mb-0">Pusat Kursus Mengemudi Terpercaya</p>
            <p class="small mb-0">Laporan di-generate pada: {{ date('d F Y, H:i') }} WIB</p>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">
                @if($jenis == 'keuangan') LAPORAN KEUANGAN & PENDAPATAN @else LAPORAN PENDAFTARAN SISWA @endif
            </h4>
            <p class="mb-0">Periode: {{ date('d M Y', strtotime($tgl_awal)) }} s/d {{ date('d M Y', strtotime($tgl_akhir)) }}</p>
            <p class="mb-0 fw-bold">Cabang: {{ $admin->branch->nama_cabang ?? 'Pusat' }}</p>
        </div>

        <table class="table table-laporan w-100">
            <thead>
                @if($jenis == 'keuangan')
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Tgl Lunas</th>
                        <th width="20%">Nama Siswa</th>
                        <th width="28%">Keterangan Pembayaran</th>
                        <th width="15%">PIC Admin</th> <!-- 🔥 Kolom Admin Handler Keuangan -->
                        <th width="20%">Nominal (Rp)</th>
                    </tr>
                @else
                    <tr>
                        <th width="5%">No</th>
                        <th width="12%">Tgl Daftar</th>
                        <th width="20%">ID & Nama Siswa</th>
                        <th width="18%">No Telp / WA</th>
                        <th width="25%">Kategori & Paket</th>
                        <th width="20%">PIC Pendaftar</th> <!-- 🔥 Kolom Admin Handler Pendaftaran -->
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    @if($jenis == 'keuangan')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->updated_at)) }}</td>
                            <td>{{ $item->user->nama_lengkap ?? 'Unknown' }}</td>
                            <td>
                                @if($item->jenis_tagihan == 'Tambahan')
                                    <strong>[Tambahan]</strong> {{ $item->keterangan }}
                                @else
                                    [Paket Utama] {{ $item->user->package->nama_package ?? '-' }}
                                @endif
                            </td>
                            <!-- Memanggil admin yang melakukan approval pembayaran -->
                            <td class="text-center fw-bold fst-italic">{{ $item->approver->nama_lengkap ?? 'Sistem Pusat' }}</td>
                            <td class="text-end">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                            
                            <td>
                                <span class="fw-bold">{{ $item->id_siswa ?? 'SJN-LAMA' }}</span><br>
                                {{ $item->nama_lengkap }}
                            </td>
                            
                            <td class="text-center">{{ $item->no_telp }}</td>
                            
                            <td>
                                <span class="badge-print mb-1">{{ strtoupper($item->package->kategori ?? 'REGULER') }}</span><br>
                                {{ $item->package->nama_package ?? 'Belum Pilih Paket' }}
                                @if($item->package)
                                    <br><span class="text-sm fw-bold">Transmisi: {{ $item->package->transmisi ?? '-' }}</span>
                                @endif
                            </td>
                            
                            <!-- Memanggil admin yang mendaftarkan siswa ini -->
                            <td class="text-center fw-bold fst-italic">
                                @if($item->registrar)
                                    {{ $item->registrar->nama_lengkap }}
                                @else
                                    Pendaftaran Mandiri (Online)
                                @endif
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="6" class="text-center py-4 fst-italic">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
            
            @if($jenis == 'keuangan' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end pe-3 fw-bold">TOTAL PENDAPATAN :</th>
                        <th class="text-end fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            @elseif($jenis == 'siswa' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end pe-3 fw-bold">TOTAL SISWA BARU :</th>
                        <th class="text-center fw-bold">{{ $total }} Orang</th>
                    </tr>
                </tfoot>
            @endif
        </table>

        <div class="row mt-5 pt-4">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p class="mb-5">Bekasi, {{ date('d F Y') }}</p>
                <p class="fw-bold mb-0 text-decoration-underline">{{ Auth::user()->nama_lengkap }}</p>
                <p class="small">Admin / Pencetak</p>
            </div>
        </div>
    </div>
</body>
</html>