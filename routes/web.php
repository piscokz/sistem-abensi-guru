<?php

// dalam routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\GuruMapelController;
use App\Http\Controllers\JadwalDetailController;
use App\Http\Controllers\JamMapelController;use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\KelasSiswaController;
use App\Http\Controllers\TanggalMerahController;

// fungsi root, arahkan ke halaman login jika belum login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest');
Route::get('/', [AuthenticatedSessionController::class, 'direct'])->middleware('auth')->name('basecamp');

// Group untuk semua route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Group untuk Kurikulum dan Guru Piket
    Route::middleware('role:kurikulum,guru_piket')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'statistikAbsensi'])
            ->middleware(['auth', 'verified'])
            ->name('dashboard');
        Route::get('/dashboard/statistik-absensi/pdf', [DashboardController::class, 'exportPdf'])
            ->name('dashboard.statistik.pdf');
        // routes/web.php
        Route::get('/dashboard/statistik-absensi/excel', [DashboardController::class, 'exportExcel'])
            ->name('dashboard.statistik.excel');
    });

    // Group untuk Kurikulum
    Route::middleware('role:kurikulum')->group(function () {
        Route::name('kurikulum.')->group(function () {
            Route::get('/hal-kurikulum', function () {
                return view('level.kurikulum.dashboard');
            });
        });
    });

    // Group untuk Guru Piket
    Route::middleware('role:guru_piket')->group(function () {
        Route::name('guru-piket.')->group(function () {
            Route::resource('libur', TanggalMerahController::class)->only(['index', 'store', 'destroy']);
            
            Route::resource('kelas', KelasController::class);
            Route::resource('mapel', MapelController::class);
            Route::resource('guru', GuruController::class);
            Route::resource('shift', ShiftController::class);
            Route::resource('shift.jam-mapel', JamMapelController::class)->shallow();

            // Resource jadwal induk
            // Route::resource('jadwal', JadwalController::class);

            Route::resource('kelas.jadwal', JadwalController::class)
                ->parameters(['kelas' => 'kelas', 'jadwal' => 'jadwal']);

            // Nested resource untuk detail jadwal
            // Route::get('jadwal/{jadwal}/cetak', [JadwalController::class, 'cetak'])->name('jadwal.cetak');
            Route::resource('jadwal.details', JadwalDetailController::class);
            Route::get('jadwal/{jadwal}/details/create/{jam_mapel}/{hari}', [JadwalDetailController::class, 'createWithJam'])
                ->name('jadwal.details.createWithJam');
            Route::post('jadwal/{jadwal}/details/create/{jam_mapel}/{hari}', [JadwalDetailController::class, 'store'])
                ->name('jadwal.details.storeWithJam');

            Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi');
            Route::get('absensi/export/pdf', [AbsensiController::class, 'exportPdf'])
                ->name('absensi.export.pdf');


            Route::get('/hadirkan-guru', [App\Http\Controllers\HadirkanGuruController::class, 'index'])
                ->name('hadirkan.index');
            Route::post('/hadirkan-guru/{guru}', [App\Http\Controllers\HadirkanGuruController::class, 'hadirkan'])
                ->name('hadirkan.store');
        });
    });

    // Route khusus untuk Guru Mapel
    Route::middleware('role:guru_mapel')->group(function () {
        Route::name('guru-mapel.')->group(function () {
            Route::get('/hal-guru-mapel', function () {
                return "Selamat datang di halaman Guru Mapel!";
            });

            Route::get('/jadwal_mengajar', [GuruMapelController::class, 'index'])->name('jadwal.index');
            // halaman tampilan scanner
            Route::get('/scan', [GuruMapelController::class, 'scanPage'])->name('scan-qr.index');
            // endpoint proses absensi
            Route::get('/absen', [GuruMapelController::class, 'absen'])->name('absen');
            Route::get('/codes/{token}', [GuruMapelController::class, 'validateToken'])->name('validate-token');
            Route::get('/rekap_absensi', [App\Http\Controllers\GuruMapelController::class, 'rekapAbsensi'])
                ->name('rekap-absensi');
        });
    });

    // Route khusus untuk Kelas
    Route::middleware('role:kelas_siswa')->group(function () {

        Route::name('kelas-siswa.')->group(function () {
            Route::get('/hal-kelas', function () {
                return "Selamat datang di halaman Kelas!";
            });

            Route::get('/jadwal_kelas', [KelasSiswaController::class, 'jadwal'])->name('jadwal.index');
            Route::get('/jadwal_kelas/{jadwal}', [KelasSiswaController::class, 'detail'])->name('jadwal.detail');
            Route::get('/qr_generate', [QrCodeController::class, 'generate'])->name('qr-generate.index');
        });
    });
});

require __DIR__ . '/auth.php';
