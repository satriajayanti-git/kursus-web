<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Unit, LaporanUnit, Jadwal};
use Illuminate\Support\Facades\Storage; // 🔥 Mengganti File dengan Storage
use Carbon\Carbon;

class UnitControlController extends Controller
{
    public function index()
    {
        $units = Unit::with(['branch', 'instruktur'])->orderBy('created_at', 'desc')->get();

        $laporans = LaporanUnit::with(['unit', 'instruktur'])
            ->orderByRaw("FIELD(status_laporan, 'Menunggu', 'Diproses', 'Selesai')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('management.units.index', compact('units', 'laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mobil' => 'required|string|max:255',
            'nopol' => 'required|string|max:50',
            'transmisi' => 'required|in:Manual,Matic,Manual & Matic',
            'foto_unit' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $namaFile = null;
        if ($request->hasFile('foto_unit')) {
            $file = $request->file('foto_unit');
            $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // 🔥 PERBAIKAN: Menambahkan parameter 'public' agar tersimpan di storage/app/public
            $file->storeAs('uploads/units', $namaFile, 'public');
        }

        Unit::create([
            'nama_mobil' => $request->nama_mobil,
            'nopol' => $request->nopol,
            'transmisi' => $request->transmisi,
            'foto_unit' => $namaFile,
            'status_operasional' => 'Aktif',
            'status_kepemilikan' => 'Rolling'
        ]);

        return back()->with('success', 'Data armada baru berhasil didaftarkan ke sistem!');
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'nama_mobil' => 'required|string|max:255',
            'nopol' => 'required|string|max:50',
            'transmisi' => 'required|in:Manual,Matic,Manual & Matic',
            'status_operasional' => 'nullable|in:Aktif,Maintenance',
            'foto_unit' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'nama_mobil' => $request->nama_mobil,
            'nopol' => $request->nopol,
            'transmisi' => $request->transmisi,
        ];

        if ($request->filled('status_operasional')) {
            $data['status_operasional'] = $request->status_operasional;
        }

        if ($request->hasFile('foto_unit')) {
            // 🔥 PERBAIKAN LOGIC HAPUS: Menggunakan Storage agar selaras dengan lokasi file yang baru
            if ($unit->foto_unit && Storage::disk('public')->exists('uploads/units/' . $unit->foto_unit)) {
                Storage::disk('public')->delete('uploads/units/' . $unit->foto_unit);
            }

            $file = $request->file('foto_unit');
            $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());

            // 🔥 PERBAIKAN: Menambahkan parameter 'public'
            $file->storeAs('uploads/units', $namaFile, 'public');

            $data['foto_unit'] = $namaFile;
        }

        $unit->update($data);

        return back()->with('success', 'Data, Transmisi & Status operasional kendaraan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $unit = Unit::findOrFail($id);

        // 🔥 PERBAIKAN LOGIC HAPUS: Membersihkan file fisik secara permanen dari Storage
        if ($unit->foto_unit && Storage::disk('public')->exists('uploads/units/' . $unit->foto_unit)) {
            Storage::disk('public')->delete('uploads/units/' . $unit->foto_unit);
        }

        $unit->delete();
        return back()->with('success', 'Data armada berhasil dihapus dari pusat sistem!');
    }

    public function prosesLaporan(Request $request, $id)
    {
        $request->validate([
            'status_laporan' => 'required|in:Menunggu,Diproses,Selesai',
            'tindakan_unit' => 'nullable|in:tetap,maintenance,aktif'
        ]);

        $laporan = LaporanUnit::findOrFail($id);

        $laporan->update(['status_laporan' => $request->status_laporan]);

        if ($request->status_laporan == 'Selesai') {
            $laporan->unit->update(['status_operasional' => 'Aktif']);
        } else {
            if ($request->tindakan_unit == 'maintenance') {
                $laporan->unit->update(['status_operasional' => 'Maintenance']);
            } elseif ($request->tindakan_unit == 'aktif') {
                $laporan->unit->update(['status_operasional' => 'Aktif']);
            }
        }

        return back()->with('success', 'Tiket laporan diperbarui! Status unit kendaraan telah tersinkronisasi.');
    }

    public function mutasiUnit(Request $request, $id)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'status_kepemilikan' => 'required|in:Tetap,Rolling',
            'instruktur_id' => 'nullable|exists:users,id',
        ]);

        $unit = Unit::findOrFail($id);

        if ($request->status_kepemilikan == 'Tetap' && !$request->instruktur_id) {
            return back()->with('error', 'Gagal Mutasi! Unit dengan status Tetap wajib memiliki Instruktur Penanggung Jawab.');
        }

        $instrukturId = $request->status_kepemilikan == 'Tetap' ? $request->instruktur_id : null;

        $unit->update([
            'branch_id' => $request->branch_id,
            'status_kepemilikan' => $request->status_kepemilikan,
            'instruktur_id' => $instrukturId,
        ]);

        return back()->with('success', 'Mutasi dan mapping aset kendaraan ' . $unit->nama_mobil . ' berhasil diperbarui!');
    }

    public function updatePajak(Request $request, $id)
    {
        $request->validate([
            'tgl_terakhir_bayar_pajak' => 'required|date',
            'tgl_jatuh_tempo_pajak' => 'required|date',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'tgl_terakhir_bayar_pajak' => $request->tgl_terakhir_bayar_pajak,
            'tgl_jatuh_tempo_pajak' => $request->tgl_jatuh_tempo_pajak,
        ]);

        return back()->with('success', 'Data Pajak STNK Unit ' . $unit->nama_mobil . ' berhasil diperbarui!');
    }

    public function updateKir(Request $request, $id)
    {
        $request->validate([
            'tgl_terakhir_bayar_kir' => 'required|date',
            'tgl_jatuh_tempo_kir' => 'required|date',
        ]);

        $unit = Unit::findOrFail($id);
        $unit->update([
            'tgl_terakhir_bayar_kir' => $request->tgl_terakhir_bayar_kir,
            'tgl_jatuh_tempo_kir' => $request->tgl_jatuh_tempo_kir,
        ]);

        return back()->with('success', 'Data KIR Unit ' . $unit->nama_mobil . ' berhasil diperbarui!');
    }
}