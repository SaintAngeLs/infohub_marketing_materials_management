<?php

use App\Http\Controllers\Admin\AutosController;
use App\Http\Controllers\Admin\ConsentionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::middleware('admin')->group(function () {
    Route::prefix('menu')->name('menu.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('/concessions', [ConsentionController::class, 'index'])->name('concessions');
        Route::get('/users', [UsersController::class, 'index'])->name('users');
        Route::get('/structure', [MenuController::class, 'index'])->name('structure');
        Route::get('/autos', [AutosController::class, 'index'])->name('autos');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports');

        Route::get('/files', function () {
            return view('admin.files.index');
        })->name('files');

        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::get('/edit/{menuItem}', [MenuController::class, 'edit'])->name('edit');
        Route::post('/toggle-status/{menuItem}', [MenuController::class, 'toggleStatus'])->name('toggleStatus');

        Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
        Route::get('/get-menu-items', [MenuController::class, 'getMenuItems']);

        Route::patch('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
        Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
