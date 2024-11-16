<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BackgroundJobController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/background-jobs', [BackgroundJobController::class, 'index'])->name('background-jobs.index');
    Route::get('/background-jobs/{id}/logs', [BackgroundJobController::class, 'showLogs'])->name('background-jobs.logs');
    Route::post('/background-jobs/{id}/cancel', [BackgroundJobController::class, 'cancelJob'])->name('background-jobs.cancel');
});

require __DIR__.'/auth.php';
