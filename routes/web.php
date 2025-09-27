<?php

// dalam routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasSiswaController;
use App\Http\Controllers\JadwalDetailController;
use App\Http\Controllers\JamMapelController;use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


// fungsi root, arahkan ke halaman login jika belum login
Route::get('/', [AuthenticatedSessionController::class, 'create'])->middleware('guest');


// Group untuk semua route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Group untuk Kurikulum dan Guru Piket
    Route::middleware('role:kurikulum,guru_piket')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard');})->name('dashboard');
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
        // Route::get('/dashboard', function () { return view('dashboard');})->name('dashboard');
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
        });
    });

    // Route khusus untuk Guru Mapel
    Route::middleware('role:guru_mapel')->group(function () {
        // Route::name('guru-mapel.')->group(function () {
        //     Route::get('/hal-guru-mapel', function () {
        //         return "Selamat datang di halaman Guru Mapel!";
        //     });

        //     // default halaman setelah login guru_mapel
        //     Route::get('/jadwal', function () {
        //         return view('guru_mapel.jadwal'); // bikin view guru_mapel/jadwal.blade.php
        //     })->name('jadwal.index');
        // });
    });

    // Route khusus untuk Kelas
    Route::middleware('role:kelas_siswa')->group(function () {

        Route::name('kelas_siswa.')->group(function () {
            Route::get('/hal-kelas', function () {
                return "Selamat datang di halaman Kelas!";
            });

            // Route::get('/jadwal', function () {return 'ok';})->name('jadwal.index');
            Route::get('/jadwal_kelas', [KelasSiswaController::class, 'jadwal'])->name('jadwal.index');
            Route::get('/qr_generate', [KelasSiswaController::class, 'qr'])->name('jadwal.qr_generate');
        });
    });
});

require __DIR__ . '/auth.php';
