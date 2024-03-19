<?php


use App\Http\Controllers\Admin\AutosController;
use App\Http\Controllers\Admin\ConsentionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/consentions', [ConsentionController::class, 'index'])->name('consentions');

Route::get('/users', [UsersController::class, 'index'])->name('users');

Route::get('/autos', [AutosController::class, 'index'])->name('autos');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
