<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Global - Satria Jayanti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; font-family: 'Times New Roman', Times, serif; color: #000; }
        .kop-surat { border-bottom: 4px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .table-laporan th, .table-laporan td { border: 1px solid #000 !important; padding: 8px; font-size: 13px; vertical-align: middle; }
        .table-laporan th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; text-align: center; }
        .text-sm { font-size: 11.5px; color: #444; }
        
        /* 🔥 Styling tambahan untuk print PDF agar badge tidak hilang warnanya */
        .badge-print { 
            display: inline-block; padding: 3px 8px; font-size: 11px; font-weight: bold; 
            border-radius: 4px; border: 1px solid #000; 
            background-color: #e9ecef !important; -webkit-print-color-adjust: exact; color: #000; 
        }
        @media print { .no-print { display: none !important; } body { margin: 0; padding: 20px; } }
    </style>
</head>
<body onload="window.print()">
    <div class="container-fluid py-4">
        <div class="mb-4 no-print text-center"><button onclick="window.close()" class="btn btn-secondary btn-sm rounded-pill px-4">Tutup Halaman</button></div>

        <div class="kop-surat text-center">
            <h2 class="fw-bold mb-1 text-uppercase">{{ $setting->nama_website ?? 'PT. SATRIA JAYANTI' }}</h2>
            <p class="mb-0 fw-bold">LAPORAN MANAJERIAL EKSEKUTIF</p>
            <p class="small mb-0">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold text-uppercase">@if($jenis == 'keuangan') REKAPITULASI PENDAPATAN KEUANGAN @else REKAPITULASI PENDAFTARAN SISWA @endif</h4>
            <p class="mb-0 fw-bold">Area: {{ strtoupper($nama_cabang) }} @if($jenis == 'keuangan') | PIC: {{ strtoupper($nama_admin) }} @endif</p>
            <p class="mb-0">Periode: {{ date('d/m/Y', strtotime($tgl_awal)) }} - {{ date('d/m/Y', strtotime($tgl_akhir)) }}</p>
        </div>

        <table class="table table-laporan w-100">
            <thead>
                @if($jenis == 'keuangan')
                    <tr>
                        <th width="3%">No</th>
                        <th width="9%">Tgl Lunas</th>
                        <th width="12%">Cabang</th>
                        <th width="20%">ID & Nama Siswa</th>
                        <th width="18%">Kategori & Paket</th>
                        <th width="12%">Metode Bayar</th>
                        <th width="13%">PIC Admin</th> 
                        <th width="13%">Nominal (Rp)</th>
                    </tr>
                @else
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tgl Daftar</th>
                        <th width="15%">Cabang Asal</th>
                        <th width="25%">ID & Nama Siswa</th>
                        <th width="40%">Kategori & Paket Terdaftar</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    @if($jenis == 'keuangan')
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->updated_at)) }}</td>
                            <td class="text-center fw-bold">{{ $item->branch->nama_cabang ?? 'Pusat' }}</td>
                            
                            <!-- 🔥 REVISI: Tampilan ID Siswa -->
                            <td>
                                <span class="fw-bold">{{ $item->user->id_siswa ?? '-' }}</span><br>
                                {{ $item->user->nama_lengkap ?? 'Unknown' }}
                            </td>
                            
                            <!-- 🔥 REVISI: Menampilkan Kategori Kelas (Reguler/Non-Reguler) -->
                            <td>
                                <span class="badge-print mb-1">{{ strtoupper($item->user->package->kategori ?? 'REGULER') }}</span><br>
                                <span class="text-sm fw-bold">{{ $item->jenis_tagihan == 'Tambahan' ? '[Tambahan]' : '[Utama]' }}</span>
                                <span class="text-sm">{{ $item->jenis_tagihan == 'Tambahan' ? $item->keterangan : ($item->user->package->nama_package ?? '-') }}</span>
                            </td>
                            
                            <!-- 🔥 REVISI: Menampilkan Metode Pembayaran BCA/BRI/Tunai -->
                            <td class="text-center fw-bold text-sm">
                                @php
                                    $metode = $item->metode_pembayaran ?? '';
                                    if(empty($metode)){
                                        // Backup parsing logic buat data lama jika di DB masih numpuk di keterangan
                                        if(stripos($item->keterangan, 'BCA') !== false) $metode = 'BCA';
                                        elseif(stripos($item->keterangan, 'BRI') !== false) $metode = 'BRI';
                                        else $metode = 'Tunai (Cabang)';
                                    }
                                @endphp
                                {{ $metode }}
                            </td>
                            
                            <!-- 🔥 REVISI: Nama PIC dari Controller -->
                            <td class="text-center fw-bold fst-italic">
                                {{ $item->pic_name }}
                            </td> 
                            <td class="text-end fw-bold">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                            <td class="text-center fw-bold">{{ $item->branch->nama_cabang ?? 'Pusat' }}</td>
                            
                            <!-- 🔥 REVISI: Tampilan ID Siswa Laporan Pendaftaran -->
                            <td>
                                <span class="fw-bold">{{ $item->id_siswa ?? '-' }}</span><br>
                                {{ $item->nama_lengkap }}
                            </td>
                            
                            <!-- 🔥 REVISI: Tampilan Kategori di Pendaftaran -->
                            <td>
                                <span class="badge-print mb-1">{{ strtoupper($item->package->kategori ?? 'REGULER') }}</span><br>
                                <span class="text-sm">{{ $item->package->nama_package ?? 'Belum Pilih Paket' }}</span>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="{{ $jenis == 'keuangan' ? '8' : '5' }}" class="text-center py-4 fst-italic">Tidak ada data transaksi pada periode ini.</td></tr>
                @endforelse
            </tbody>
            
            @if($jenis == 'keuangan' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end pe-3 fw-bold">TOTAL PENDAPATAN :</th>
                        <th class="text-end fw-bold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            @elseif($jenis == 'siswa' && count($data) > 0)
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end pe-3 fw-bold">TOTAL SISWA BARU :</th>
                        <th class="text-center fw-bold fs-5">{{ $total }} Orang</th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
</body>
</html>