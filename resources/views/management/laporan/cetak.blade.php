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
        
        /* 🔥 Styling untuk print agar badge tidak pudar saat di-generate ke PDF */
        .badge-print { 
            display: inline-block; padding: 2px 6px; font-size: 11.5px; font-weight: bold; 
            border-radius: 4px; border: 1px solid #333; 
            background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; color: #000; 
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
            <p class="mb-0 fw-bold">Area: {{ strtoupper($nama_cabang) }} @if($jenis == 'keuangan') | PIC: {{ strtoupper($nama_admin) }} @endif</p>
        </div>

        <table class="table table-laporan w-100">
            <thead>
                @if($jenis == 'keuangan')
                    <tr>
                        <!-- 🔥 KEMBALIKAN KOLOM CABANG, NAMA SISWA LENGKAP & PIC ADMIN -->
                        <th width="3%">No</th>
                        <th width="10%">Tgl Lunas</th>
                        <th width="12%">Cabang</th>
                        <th width="20%">ID & Nama Siswa</th>
                        <th width="25%">Keterangan Pembayaran</th>
                        <th width="15%">PIC Admin</th>
                        <th width="15%">Nominal (Rp)</th>
                    </tr>
                @else
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tgl Daftar</th>
                        <th width="25%">ID & Nama Siswa</th>
                        <th width="20%">No Telp / WA</th>
                        <th width="35%">Kategori, Paket & Transmisi</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    @if($jenis == 'keuangan')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->updated_at)) }}</td>
                            
                            <!-- 🔥 DATA CABANG -->
                            <td class="text-center fw-bold">{{ $item->branch->nama_cabang ?? 'Pusat' }}</td>
                            
                            <!-- 🔥 DATA ID & NAMA SISWA -->
                            <td>
                                <span class="fw-bold">{{ $item->user->id_siswa ?? '-' }}</span><br>
                                {{ $item->user->nama_lengkap ?? 'Unknown' }}
                            </td>
                            
                            <td>
                                @php
                                    $metode = '';
                                    if(preg_match('/\(Via(?: Bank)?: (.*?)\)/', $item->keterangan, $match)) {
                                        $metode = trim($match[1]);
                                    } elseif (stripos($item->keterangan, 'QRIS') !== false) {
                                        $metode = 'QRIS';
                                    } elseif (stripos($item->keterangan, 'BCA') !== false) {
                                        $metode = 'BCA';
                                    } elseif (stripos($item->keterangan, 'BRI') !== false) {
                                        $metode = 'BRI';
                                    } elseif (stripos($item->keterangan, 'Tunai') !== false || stripos($item->keterangan, 'Cash') !== false) {
                                        $metode = 'Tunai (Cabang)';
                                    }
                                    
                                    $keteranganBersih = preg_replace('/\s*\(Via(?: Bank)?:.*?\)/', '', $item->keterangan);
                                @endphp

                                @if($item->jenis_tagihan == 'Tambahan')
                                    <strong>[Tambahan]</strong> {{ $keteranganBersih }}
                                @else
                                    [Paket Utama] {{ $item->user->package->nama_package ?? '-' }}
                                @endif
                                
                                @if($metode)
                                    <br><span class="badge-print mt-1">Metode: {{ $metode }}</span>
                                @endif
                            </td>
                            
                            <!-- 🔥 DATA PIC ADMIN -->
                            <td class="text-center fw-bold fst-italic">{{ $item->pic_name }}</td>
                            
                            <td class="text-end fw-bold">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                            
                            <td>
                                <span class="fw-bold">{{ $item->id_siswa ?? '-' }}</span><br>
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
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="{{ $jenis == 'keuangan' ? '7' : '5' }}" class="text-center py-4 fst-italic">Tidak ada data pada periode ini.</td></tr>
                @endforelse
            </tbody>
            
            @if($jenis == 'keuangan' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end pe-3 fw-bold">TOTAL PENDAPATAN :</th>
                        <th class="text-end fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            @elseif($jenis == 'siswa' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end pe-3 fw-bold">TOTAL SISWA BARU :</th>
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
                <p class="small">Management / Admin</p>
            </div>
        </div>
    </div>
</body>
</html>