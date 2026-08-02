<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Setting, Package, Branch, Gallery};
use Illuminate\Support\Facades\Storage; // 🔥 Mengganti File menjadi Storage

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'packages' => Package::all(),
            'branches' => Branch::all(),
            'galleries' => Gallery::all(),
            'setting' => Setting::first()
        ]);
    }

    // ================= TAMBAH DATA =================
    public function addPackage(Request $request)
    {
        // 🔥 Tambahan validasi transmisi, kategori, dan detail
        $request->validate([
            'nama_package' => 'required',
            'pertemuan' => 'required|numeric',
            'harga' => 'required|numeric',
            'transmisi' => 'required',
            'kategori' => 'required|in:Reguler,Non-Reguler',
            'detail' => 'nullable'
        ]);

        $package = new Package();
        $package->nama_package = $request->nama_package;
        $package->pertemuan = $request->pertemuan;
        $package->harga = $request->harga;
        $package->transmisi = $request->transmisi; // 🔥 Panggil field transmisi
        $package->kategori = $request->kategori;   // 🔥 Panggil field kategori
        $package->detail = $request->detail;       // 🔥 Panggil field detail
        $package->save();

        return back()->with('success', 'Paket kursus baru berhasil ditambahkan.');
    }

    public function addBranch(Request $request)
    {
        $request->validate(['nama_cabang' => 'required', 'lokasi' => 'required', 'foto' => 'required|image|mimes:jpeg,png,jpg']);

        $branch = new Branch();
        $branch->nama_cabang = $request->nama_cabang;
        $branch->lokasi = $request->lokasi;
        $branch->detail = $request->detail;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_cabang.' . $file->getClientOriginalExtension();

            // 🔥 PERBAIKAN: Gunakan storeAs dengan disk 'public'
            $file->storeAs('uploads/branches', $filename, 'public');

            // 🔥 SOLUSI TUNTAS: Isi kedua field sekaligus biar Database & Blade sama-sama jalan
            $branch->foto = $filename;
            $branch->foto_cabang = $filename;
        }

        $branch->save();
        return back()->with('success', 'Lokasi cabang baru berhasil didaftarkan.');
    }

    public function addGallery(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $gallery = new Gallery();
        $gallery->judul = $request->judul;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_galeri.' . $file->getClientOriginalExtension();

            // 🔥 PERBAIKAN: Gunakan storeAs dengan disk 'public'
            $file->storeAs('uploads/gallery', $filename, 'public');

            $gallery->foto = $filename;
        }

        $gallery->save();
        return back()->with('success', 'Foto berhasil diunggah ke galeri kegiatan.');
    }

    // ================= UPDATE DATA =================
    public function updatePackage(Request $request, $id)
    {
        // 🔥 Tambahan validasi transmisi, kategori, dan detail
        $request->validate([
            'nama_package' => 'required',
            'pertemuan' => 'required|numeric',
            'harga' => 'required|numeric',
            'transmisi' => 'required',
            'kategori' => 'required|in:Reguler,Non-Reguler',
            'detail' => 'nullable'
        ]);

        $package = Package::findOrFail($id);
        $package->nama_package = $request->nama_package;
        $package->pertemuan = $request->pertemuan;
        $package->harga = $request->harga;
        $package->transmisi = $request->transmisi;
        $package->kategori = $request->kategori;
        $package->detail = $request->detail;
        $package->save();

        return back()->with('success', 'Data paket kursus berhasil diperbarui.');
    }

    public function storeBranch(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg',
            'link_gmaps' => 'nullable|url',
            'no_telp_admin' => 'nullable|string|max:20'
        ]);

        $branch = new Branch();
        $branch->nama_cabang = $request->nama_cabang;
        $branch->lokasi = $request->lokasi;
        $branch->detail = $request->detail;

        // 🔥 FIX: Injeksi data baru saat Tambah Cabang
        $branch->link_gmaps = $request->link_gmaps;
        $branch->no_telp_admin = $request->no_telp_admin;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_cabang.' . $file->getClientOriginalExtension();

            // 🔥 PERBAIKAN: Gunakan storeAs dengan disk 'public'
            $file->storeAs('uploads/branches', $filename, 'public');

            $branch->foto = $filename;
            $branch->foto_cabang = $filename;
        }

        $branch->save();
        return back()->with('success', 'Cabang baru berhasil ditambahkan.');
    }

    // ================= EDIT DATA (UPDATE) =================
    public function updateBranch(Request $request, $id)
    {
        $request->validate([
            'nama_cabang' => 'required',
            'lokasi' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg',
            'link_gmaps' => 'nullable|url',
            'no_telp_admin' => 'nullable|string|max:20'
        ]);

        $branch = Branch::findOrFail($id);
        $branch->nama_cabang = $request->nama_cabang;
        $branch->lokasi = $request->lokasi;
        $branch->detail = $request->detail;

        // 🔥 FIX: Injeksi data baru saat Edit Cabang agar tersimpan di Database
        $branch->link_gmaps = $request->link_gmaps;
        $branch->no_telp_admin = $request->no_telp_admin;

        if ($request->hasFile('foto')) {
            // 🔥 PERBAIKAN LOGIC HAPUS: Menggunakan Storage agar selaras dengan lokasi file
            if ($branch->foto && Storage::disk('public')->exists('uploads/branches/' . $branch->foto)) {
                Storage::disk('public')->delete('uploads/branches/' . $branch->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_cabang.' . $file->getClientOriginalExtension();

            // 🔥 PERBAIKAN: Gunakan storeAs dengan disk 'public'
            $file->storeAs('uploads/branches', $filename, 'public');

            $branch->foto = $filename;
            $branch->foto_cabang = $filename;
        }

        $branch->save();
        return back()->with('success', 'Data & foto cabang berhasil diperbarui.');
    }

    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $gallery = Gallery::findOrFail($id);
        $gallery->judul = $request->judul;

        if ($request->hasFile('image')) {
            // 🔥 PERBAIKAN LOGIC HAPUS: Menggunakan Storage agar selaras dengan lokasi file
            if ($gallery->foto && Storage::disk('public')->exists('uploads/gallery/' . $gallery->foto)) {
                Storage::disk('public')->delete('uploads/gallery/' . $gallery->foto);
            }

            $file = $request->file('image');
            $filename = time() . '_galeri.' . $file->getClientOriginalExtension();

            // 🔥 PERBAIKAN: Gunakan storeAs dengan disk 'public'
            $file->storeAs('uploads/gallery', $filename, 'public');

            $gallery->foto = $filename;
        }

        $gallery->save();
        return back()->with('success', 'Data foto galeri berhasil diperbarui.');
    }

    // ================= HAPUS DATA =================
    public function deleteItem($type, $id)
    {
        if ($type == 'package') {
            Package::findOrFail($id)->delete();
        }

        if ($type == 'branch') {
            $branch = Branch::findOrFail($id);
            // 🔥 PERBAIKAN LOGIC HAPUS: Membersihkan file fisik secara permanen dari Storage
            if ($branch->foto && Storage::disk('public')->exists('uploads/branches/' . $branch->foto)) {
                Storage::disk('public')->delete('uploads/branches/' . $branch->foto);
            }
            $branch->delete();
        }

        if ($type == 'gallery') {
            $gallery = Gallery::findOrFail($id);
            // 🔥 PERBAIKAN LOGIC HAPUS: Membersihkan file fisik secara permanen dari Storage
            if ($gallery->foto && Storage::disk('public')->exists('uploads/gallery/' . $gallery->foto)) {
                Storage::disk('public')->delete('uploads/gallery/' . $gallery->foto);
            }
            $gallery->delete();
        }

        return back()->with('success', 'Data berhasil dihapus secara permanen.');
    }
}