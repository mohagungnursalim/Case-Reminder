<?php

use App\Http\Controllers\AtasanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JaksaController;
use App\Http\Controllers\KasusController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SaksiController;
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

// testing wablas
Route::get('/test-whatsapp', function () {
    $reminders = App\Models\Reminder::where('is_sent', false)->get();
    foreach ($reminders as $reminder) {
        dispatch(new \App\Jobs\SendReminderMessage($reminder));
    }
});

// route agenda json
Route::get('/agenda-terkirim-sesuai-jadwal', [DashboardController::class, 'agendaTerkirimSesuaiJadwal'])->middleware('auth');
Route::get('/agenda-belum-terkirim-sesuai-jadwal', [DashboardController::class, 'agendaBelumTerkirimSesuaiJadwal'])->middleware('auth');

// ---------Login-----------
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('auth');

// --------------------Route Modules---------------------------------------

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth']);

Route::resource('/dashboard/user', UserController::class)->middleware(['auth','is_admin']);
// Rute untuk memperbarui peran user
Route::put('/dashboard/user/peran/{id}', [UserController::class, 'peran'])->middleware(['auth', 'is_admin'])->name('user.peran');


Route::resource('/dashboard/agenda', ReminderController::class)->middleware('auth');

Route::resource('/dashboard/jaksa', JaksaController::class)->middleware('auth');

Route::resource('/dashboard/saksi', SaksiController::class)->middleware('auth');

Route::resource('/dashboard/kasus', KasusController::class)->middleware('auth');

Route::resource('/dashboard/atasan', AtasanController::class)->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index']);
    Route::patch('/dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/dashboard/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------End of Route Modules---------------------------------------

require __DIR__.'/auth.php';
