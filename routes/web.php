<?php

use Illuminate\Support\Facades\Route;
use App\Models\{Setting, Unit, Branch, Gallery, Package};

// Controller Admin
use App\Http\Controllers\Admin\{
    SettingController, StudentController, InstructorController, 
    KeuanganController, JadwalController, CutiController as AdminCuti, 
    ReportController as AdminReport
};

// Controller Dashboards
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Instruktur\DashboardController as InstrukturDashboard;
use App\Http\Controllers\Management\DashboardController as ManagementDashboard;

// Controller Instruktur (Cuti & Riwayat)
use App\Http\Controllers\Instruktur\CutiController as InstrukturCuti;
use App\Http\Controllers\Instruktur\RiwayatController as InstrukturRiwayat;

// Controller Management
use App\Http\Controllers\Management\{
    ReportController as ManagementReport, 
    CutiController as ManagementCuti, 
    KaryawanController as ManagementKaryawan,
    UnitControlController
};

// Auth & Middleware
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('landing', ['setting' => Setting::first() ?? new Setting(), 'units' => Unit::all(), 'branches' => Branch::all(), 'galleries'=> Gallery::all(), 'packages' => Package::all()]);
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// ================= AREA SISWA =================
Route::middleware(['auth', CheckRole::class . ':siswa'])->prefix('siswa')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('siswa.dashboard');
    Route::post('/bayar/{id}', [SiswaDashboard::class, 'uploadBukti']);
    Route::post('/simpan-jadwal', [SiswaDashboard::class, 'simpanJadwal']);
    Route::post('/feedback/{id}', [SiswaDashboard::class, 'simpanFeedback']);
});

// ================= AREA INSTRUKTUR =================
Route::middleware(['auth', CheckRole::class . ':instruktur'])->prefix('instruktur')->group(function () {
    Route::get('/dashboard', [InstrukturDashboard::class, 'index'])->name('instruktur.dashboard');
    Route::put('/jadwal/evaluasi/{id}', [InstrukturDashboard::class, 'simpanEvaluasi']);
    Route::get('/riwayat', [InstrukturRiwayat::class, 'index']);
    Route::get('/cuti', [InstrukturCuti::class, 'index']);
    Route::post('/cuti', [InstrukturCuti::class, 'store']);
    Route::post('/laporan-unit', [InstrukturDashboard::class, 'storeLaporanUnit']);
});

// ================= AREA ADMIN =================
Route::middleware(['auth', CheckRole::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    Route::put('/jadwal/update-full/{id}', [JadwalController::class, 'updateFull']);
    Route::put('/jadwal/reschedule/{id}', [JadwalController::class, 'updateJadwal']);
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy']);
    Route::put('/jadwal/konfirmasi-lembur/{id}', [JadwalController::class, 'lunasinLembur']);
    Route::post('/jadwal', [JadwalController::class, 'store']);
    Route::get('/keuangan', [KeuanganController::class, 'index']);
    Route::put('/keuangan/{id}', [KeuanganController::class, 'updateStatus']);
    // 🔥 ROUTE BARU: Upload Bukti Bayar Offline via Admin
    Route::put('/keuangan/upload-bukti/{id}', [KeuanganController::class, 'uploadBuktiAdmin']); 
    Route::post('/keuangan/tambahan', [KeuanganController::class, 'storeTambahan']);
    Route::post('/keuangan/laporan-cetak', [KeuanganController::class, 'generateReport'])->name('admin.keuangan.cetak'); 
    
    Route::get('/siswa', [StudentController::class, 'index']);
    // 🔥 ROUTE BARU: Tambah Siswa Baru (Bypass Admin)
    Route::post('/siswa', [StudentController::class, 'store']);
    
    // 🔥 PERBAIKAN: Hapus prefix '/admin' di sini karena group ini sudah pakai prefix('admin')
    Route::put('/siswa/{id}', [StudentController::class, 'update']); 
    
    Route::delete('/siswa/{id}', [StudentController::class, 'destroy']);
    
    Route::get('/instruktur', [InstructorController::class, 'index']);
    Route::post('/instruktur', [InstructorController::class, 'store']);
    Route::put('/instruktur/{id}', [InstructorController::class, 'update']);
    Route::delete('/instruktur/{id}', [InstructorController::class, 'destroy']);
    Route::get('/cuti', [AdminCuti::class, 'index']);
    Route::post('/cuti', [AdminCuti::class, 'store']); 
    
    Route::get('/settings', [SettingController::class, 'edit']);
    Route::post('/settings/update-general', [SettingController::class, 'updateGeneral']);
    Route::post('/settings/add-package', [SettingController::class, 'addPackage']);
    Route::put('/settings/update/package/{id}', [SettingController::class, 'updatePackage']);
    Route::post('/settings/add-branch', [SettingController::class, 'addBranch']);
    Route::put('/settings/update/branch/{id}', [SettingController::class, 'updateBranch']);
    Route::post('/settings/add-gallery', [SettingController::class, 'addGallery']);
    Route::put('/settings/update/gallery/{id}', [SettingController::class, 'updateGallery']);
    Route::delete('/settings/delete/{type}/{id}', [SettingController::class, 'deleteItem']);
    
    Route::get('/laporan', [AdminReport::class, 'index'])->name('admin.laporan.index');
    Route::post('/laporan/cetak', [AdminReport::class, 'cetak'])->name('admin.laporan.cetak');
});

// ================= AREA MANAGEMENT =================
Route::middleware(['auth', CheckRole::class . ':management'])->prefix('management')->group(function () {
    Route::get('/dashboard', [ManagementDashboard::class, 'index'])->name('management.dashboard');
    
    // ROUTING MANAJEMEN UNIT
    Route::get('/units', [UnitControlController::class, 'index'])->name('management.units.index');
    Route::post('/units', [UnitControlController::class, 'store']); 
    Route::put('/units/{id}', [UnitControlController::class, 'update']); 
    Route::delete('/units/{id}', [UnitControlController::class, 'destroy']); 
    
    // Legalitas & Operasional
    Route::put('/units/pajak/{id}', [UnitControlController::class, 'updatePajak']); 
    Route::put('/units/kir/{id}', [UnitControlController::class, 'updateKir']); 
    Route::put('/units/mutasi/{id}', [UnitControlController::class, 'mutasiUnit']); 
    Route::put('/laporan-unit/{id}', [UnitControlController::class, 'prosesLaporan']); 
    
    // Karyawan & Cuti
    Route::get('/karyawan', [ManagementKaryawan::class, 'index'])->name('management.karyawan.index');
    Route::post('/karyawan', [ManagementKaryawan::class, 'store']);
    Route::put('/karyawan/{id}', [ManagementKaryawan::class, 'update']);
    Route::delete('/karyawan/{id}', [ManagementKaryawan::class, 'destroy']);
    
    Route::get('/cuti', [ManagementCuti::class, 'index'])->name('management.cuti.index');
    Route::put('/cuti/{id}', [ManagementCuti::class, 'updateStatus']);
    
    Route::get('/laporan', [ManagementReport::class, 'index'])->name('management.laporan.index');
    Route::post('/laporan/cetak', [ManagementReport::class, 'cetak'])->name('management.laporan.cetak');

    // 🔥 ROUTE BARU: Ubah Password Management
    Route::get('/ubah-password', [ManagementDashboard::class, 'showPasswordForm'])->name('management.password.form');
    Route::put('/ubah-password', [ManagementDashboard::class, 'updatePassword'])->name('management.password.update');
});