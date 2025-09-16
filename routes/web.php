<?php

// dalam routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JamMapelController;
use App\Http\Controllers\JadwalDetailController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth',);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group untuk semua route yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Group untuk Kurikulum
    Route::middleware('role:kurikulum')->group(function () {
        Route::name('kurikulum.')->group(function () {
            Route::get('/hal-kurikulum', function () {
                return "Selamat datang di halaman Kurikulum!";
            });
        });
    });

    // Group untuk Guru Piket
    Route::middleware('role:guru_piket')->group(function () {
        Route::name('guru-piket.')->group(function () {
            Route::get('/hal-guru-piket', function () {
                return "Selamat datang di halaman Guru Piket!";
            });
            Route::resource('kelas', KelasController::class);
            Route::resource('mapel', MapelController::class);
            Route::resource('guru', GuruController::class);
            Route::resource('shift', ShiftController::class);

            // nested: shift -> jam-mapel
            Route::resource('shift.jam-mapel', JamMapelController::class)->shallow();

            // Resource jadwal induk
            // Route::resource('jadwal', JadwalController::class);

            Route::resource('kelas.jadwal', JadwalController::class)
                ->parameters(['kelas' => 'kelas', 'jadwal' => 'jadwal']);

            // Nested resource untuk detail jadwal
            Route::resource('jadwal.details', JadwalDetailController::class);
        });
    });

    // Route khusus untuk Guru Mapel
    Route::middleware('role:guru_mapel')->group(function () {
        Route::name('guru-mapel.')->group(function () {
            Route::get('/hal-guru-mapel', function () {
                return "Selamat datang di halaman Guru Mapel!";
            });
            // Route::get('/hal-guru-mapel', function() { return "Selamat datang di halaman Guru Mapel!";})->name('guru.index');
            // Route::get('/absen/scan', function() { /* Logika Controller */ })->name('absen.scan');
        });
    });

    // Route khusus untuk Siswa
    Route::middleware('role:siswa')->group(function () {
        Route::name('siswa.')->group(function () {
            Route::get('/hal-siswa', function () {
                return "Selamat datang di halaman Siswa!";
            });
            // Route::get('/qr/generate', function() { /* Logika Controller */ })->name('qr.generate');
        });
    });
});

require __DIR__ . '/auth.php';
