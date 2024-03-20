<?php

use App\Http\Controllers\Admin\AutosController;
use App\Http\Controllers\Admin\ConsentionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/create', [MenuController::class, 'create'])->name('menu.create');


Route::middleware('admin')->group(function () {
    Route::prefix('menu')->name('menu.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/concessions', [ConsentionController::class, 'index'])->name('concessions');
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::get('/structure', [MenuController::class, 'index'])->name('structure');
        Route::get('/autos', [AutosController::class, 'index'])->name('autos');
        Route::get('/files', function () {
            return view('admin.files');
        })->name('files');
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
    });
});

Route::get('/get-menu-items', [MenuController::class, 'getMenuItems']);

Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
Route::patch('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');


Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
