<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Setting, Package, Branch, Gallery};
use Illuminate\Support\Facades\File;

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

    // ================= TAMBAH DATA (DIROMBAK ANTI GAGAL) =================
    public function addPackage(Request $request)
    {
        $request->validate(['nama_package' => 'required', 'pertemuan' => 'required', 'harga' => 'required']);
        
        $package = new Package();
        $package->nama_package = $request->nama_package;
        $package->pertemuan = $request->pertemuan;
        $package->harga = $request->harga;
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

        if($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_cabang.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/branches'), $filename);
            $branch->foto = $filename; // Paksa masukin nama file
        }

        $branch->save(); // Simpan paksa ke database
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

        if($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_galeri.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $filename);
            $gallery->foto = $filename; 
        }

        $gallery->save();
        return back()->with('success', 'Foto berhasil diunggah ke galeri kegiatan.');
    }

    // ================= UPDATE DATA (DIROMBAK ANTI GAGAL) =================
    public function updatePackage(Request $request, $id)
    {
        $request->validate(['nama_package' => 'required', 'pertemuan' => 'required', 'harga' => 'required']);
        
        $package = Package::findOrFail($id);
        $package->nama_package = $request->nama_package;
        $package->pertemuan = $request->pertemuan;
        $package->harga = $request->harga;
        $package->save();

        return back()->with('success', 'Data paket kursus berhasil diperbarui.');
    }

    public function updateBranch(Request $request, $id)
    {
        $request->validate(['nama_cabang' => 'required', 'lokasi' => 'required', 'foto' => 'nullable|image|mimes:jpeg,png,jpg']);
        
        $branch = Branch::findOrFail($id);
        $branch->nama_cabang = $request->nama_cabang;
        $branch->lokasi = $request->lokasi;
        $branch->detail = $request->detail;

        if($request->hasFile('foto')) {
            // Hapus file lama jika ada
            if($branch->foto && File::exists(public_path('uploads/branches/'.$branch->foto))) {
                File::delete(public_path('uploads/branches/'.$branch->foto));
            }
            
            $file = $request->file('foto');
            $filename = time() . '_cabang.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/branches'), $filename);
            $branch->foto = $filename; // Paksa timpa nama file lama
        }

        $branch->save(); // Simpan paksa ke database
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
        
        if($request->hasFile('image')) {
            // Hapus file lama jika ada
            if($gallery->foto && File::exists(public_path('uploads/gallery/'.$gallery->foto))) {
                File::delete(public_path('uploads/gallery/'.$gallery->foto));
            }
            
            $file = $request->file('image');
            $filename = time() . '_galeri.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/gallery'), $filename);
            $gallery->foto = $filename; 
        }
        
        $gallery->save();
        return back()->with('success', 'Data foto galeri berhasil diperbarui.');
    }

    // ================= HAPUS DATA =================
    public function deleteItem($type, $id)
    {
        if($type == 'package') Package::findOrFail($id)->delete();
        
        if($type == 'branch') {
            $branch = Branch::findOrFail($id);
            if($branch->foto && File::exists(public_path('uploads/branches/'.$branch->foto))) {
                File::delete(public_path('uploads/branches/'.$branch->foto));
            }
            $branch->delete();
        }
        
        if($type == 'gallery') {
            $gallery = Gallery::findOrFail($id);
            if($gallery->foto && File::exists(public_path('uploads/gallery/'.$gallery->foto))) {
                File::delete(public_path('uploads/gallery/'.$gallery->foto));
            }
            $gallery->delete();
        }
        
        return back()->with('success', 'Data berhasil dihapus secara permanen.');
    }
}