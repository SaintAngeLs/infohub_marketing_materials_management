<?php

use App\Http\Controllers\Admin\AutosController;
use App\Http\Controllers\Admin\ConcessionsController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\UserGroupsController;
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
        Route::get('/get-menu-items-with-files', [MenuController::class, 'getMenuItemsWithFiles']);

        Route::patch('/menu-items/{menuItem}', [MenuController::class, 'update'])->name('menu-items.update');
        Route::post('/menu-items/update-order', [MenuItemController::class, 'updateOrder'])->name('menu-items.update-order');
        Route::post('/menu-items/update-type', [MenuItemController::class, 'updateType'])->name('menu-items.update-type');
        Route::post('/update-tree-structure', [MenuItemController::class, 'updateTreeStructure'])->name('menu.update-tree-structure');
        Route::get('/menu-items/{id}/has-sub-items', [MenuItemController::class, 'hasSubItems'])->name('menu-items.has-sub-items');
        Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');


        Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
        Route::post('/files/store', [FileController::class, 'store'])->name('files.store');
        Route::get('/file/edit/{file}', [FileController::class, 'edit'])->name('file.edit');
        Route::patch('/files/{file}', [FileController:: class, 'update'])->name('file.update');
        Route::get('/files/delete/{id}', [FileController::class, 'delete'])->name('files.delete');
        Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');
        Route::get('/files/directory-structure', [FileController::class, 'getDirectoryStructure']);
        Route::post('/file/toggle-status/{id}', [FileController::class, 'toggleStatus'])->name('file.toggleStatus');

        Route::get('/concessions', [ConcessionsController::class, 'index'])->name('concessions');
        Route::get('/concessions/create', [ConcessionsController::class, 'create'])->name('concessions.create');
        Route::get('/concessions/edit/{id}', [ConcessionsController::class, 'edit'])->name('concessions.edit');
        Route::post('/concessions/store', [ConcessionsController::class, 'store'])->name('concessions.store');
        Route::patch('/concessions/update/{concession}', [ConcessionsController::class, 'update'])->name('concessions.update');


        Route::prefix('users')->name('users.')->middleware(['auth', 'verified'])->group(function () {
            Route::get('/usergroups', [UsersController::class, 'index'])->name('groups');
            Route::get('/applications', [UsersController::class, 'index'])->name('applications');
            Route::get('/create', [UsersController::class, 'create'])->name('create');
            Route::post('/store', [UsersController::class, 'store'])->name('store');
        });

    });




});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
