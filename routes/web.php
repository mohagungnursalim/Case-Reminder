<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JaksaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProyekController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\SaksiController;
use App\Http\Controllers\UserController;
use App\Jobs\SendReminderMessage;
use App\Models\Reminder;
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

// testing twilio
Route::get('/test-whatsapp', function () {
    $reminders = App\Models\Reminder::where('is_sent', false)->get();
    foreach ($reminders as $reminder) {
        dispatch(new \App\Jobs\SendReminderMessage($reminder));
    }
});

Route::get('/dashboard/logs', [LogController::class, 'getAllStatus'])->name('get-all');
Route::delete('/dashboard/delete-all', [LogController::class, 'deleteAll'])->name('delete-all');

// route agenda json
Route::get('/agenda-terkirim-sesuai-jadwal', [DashboardController::class, 'agendaTerkirimSesuaiJadwal']);
Route::get('/agenda-belum-terkirim-sesuai-jadwal', [DashboardController::class, 'agendaBelumTerkirimSesuaiJadwal']);

// ---------Login-----------
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('auth');

// --------------------Route Modules---------------------------------------

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth']);

Route::resource('/dashboard/user', UserController::class)->middleware(['auth','is_admin']);
// Rute untuk memperbarui peran user
Route::put('/dashboard/user/peran/{id}', [UserController::class, 'peran'])->middleware(['auth', 'is_admin'])->name('user.peran');


Route::resource('/dashboard/agenda', ReminderController::class)->middleware(['auth','is_admin']);

Route::resource('/dashboard/jaksa', JaksaController::class)->middleware('auth');

Route::resource('/dashboard/saksi', SaksiController::class)->middleware('auth');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index']);
    Route::patch('/dashboard/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/dashboard/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------End of Route Modules---------------------------------------

require __DIR__.'/auth.php';
