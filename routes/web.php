<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ---------Login-----------
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('auth');

// --------------------Route Modules---------------------------------------

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth']);

Route::resource('/dashboard/anggota', AnggotaController::class)->middleware(['auth']);
Route::get('/dashboard/anggota/{id}/cetak-kartu', [AnggotaController::class, 'cetakKartu'])->name('anggota.cetak-kartu');

Route::resource('/dashboard/kategori', KategoriController::class)->middleware(['auth']);

Route::resource('/dashboard/buku', BukuController::class)->middleware(['auth']);

Route::resource('/dashboard/proyek', ProyekController::class)->middleware(['auth']);

Route::resource('/dashboard/user', UserController::class)->middleware(['auth','is_admin']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index']);
    Route::patch('/dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/dashboard/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------End of Route Modules---------------------------------------

require __DIR__.'/auth.php';
