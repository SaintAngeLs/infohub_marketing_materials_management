<?php

use App\Http\Controllers\Admin\Applications\ApplicationManagementController;
use App\Http\Controllers\Admin\Applications\ApplicationViewController;
use App\Http\Controllers\Admin\Autos\AutosManagementController;
use App\Http\Controllers\Admin\Autos\AutosViewController;
use App\Http\Controllers\Admin\AutosController;
use App\Http\Controllers\Admin\Concessions\ConcessionsManagementController;
use App\Http\Controllers\Admin\Concessions\ConcessionsViewController;
use App\Http\Controllers\Admin\Files\FileController;
use App\Http\Controllers\Admin\Menu\MenuController;
use App\Http\Controllers\Admin\MenuItem\MenuItemController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Permissions\PermissionManagementController;
use App\Http\Controllers\Admin\Permissions\PermissionViewController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\Statistics\StatisticsManagementController;
use App\Http\Controllers\Admin\Statistics\StatisticsViewController;
use App\Http\Controllers\Admin\UserGroups\UserGroupsController;
use App\Http\Controllers\Admin\Users\PasswordSetupController;
use App\Http\Controllers\Admin\Users\UserManagementController;
use App\Http\Controllers\Admin\Users\UserViewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Search\SearchController;
use App\Http\Controllers\User\Account\UserAccountManagementController;
use App\Http\Controllers\User\Account\UserAccountViewController;
use App\Http\Controllers\User\MenuItemNotifications\MenuItemNotificationsController;
use App\Http\Controllers\User\MenuItemNotifications\MenuItemNotificationsViewController;
use App\Http\Controllers\User\UserMenu\UserMenuViewController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Admin\StatisticsController;


Route::middleware('guest')->group(function () {
    Route::get('/', function () { return view('auth.login'); });
    Route::get('/user/set-password/{user}', [PasswordSetupController::class, 'showSetPasswordForm'])->name('user.set-password')->middleware('signed');
    Route::post('/user/update-password/{user}', [PasswordSetupController::class, 'updatePassword'])->name('user.update-password');
    Route::post('/access-request', [ApplicationManagementController::class, 'storeAccessRequest'])->name('access-request.store');
    Route::get('/access-request-thank-you', function () { return view('thank-you'); })->name('thank-you');

});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/my-account', [UserAccountViewController::class, 'index'])->name('user.my-account');
    Route::get('/user/notifications', [MenuItemNotificationsViewController::class, 'index'])->name('user.notifications');
    Route::get('/user/change-password', [UserAccountViewController::class, 'showChangePasswordForm'])->name('user.change-password');
    Route::post('/user/change-password', [UserAccountManagementController::class, 'changePassword'])->name('user.change-password.post');
    Route::get('/user/get-menu-items-user-notifications', [MenuItemNotificationsController::class, 'getMenuItemsNotifications'])->name('user.get-menu-items-user-notifications');

    Route::get('/search', [SearchController::class, 'search'])->name('search');

});

Route::middleware('auth')->group(function () {
    Route::get('/user/my-account', [UserAccountViewController::class, 'index'])->middleware(['verified'])->name('user.my-account');
    Route::get('/dashboard', function () { return view('dashboard'); })->middleware(['verified'])->name('dashboard');
    Route::get('/user-menu', [UserMenuViewController::class, 'index'])->name('user.menu');
    Route::get('/user-menu/{menuItemId}/files', [UserMenuViewController::class, 'showFilesForMenuItem'])->name('user-menu.files');
    Route::get('/files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::post('/user/update-menu-item-notification',  [MenuItemNotificationsController::class, 'updateNotificationPreference'])->name('user.notification-preference.update');
    Route::get('/user/get-menu-items-user-notifications', [MenuItemNotificationsController::class, 'getMenuItemsNotifications'])->name('user.get-menu-items-user-notifications');
    Route::post('/files/downloadMultiple', [FileController::class, 'downloadMultiple'])->name('files.downloadMultiple');
});

Route::middleware('admin',  'verified')->group(function () {

    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::prefix('menu')->name('menu.')->middleware(['auth', 'verified'])->group(function () {

        Route::get('/users', [UserViewController::class, 'index'])->name('users');
        Route::get('/structure', [MenuController::class, 'index'])->name('structure');
        Route::get('/autos', [AutosController::class, 'index'])->name('autos');
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
        Route::get('/files', [MenuController::class, 'indexFiles'])->name('files');
        Route::get('/statistics', [StatisticsViewController::class, 'index'])->name('statistics');
        Route::get('/statistics/entries', [StatisticsManagementController::class, 'showMenuEntries'])->name('statistics.entries');
        Route::get('/statistics/downloads', [StatisticsManagementController::class, 'showDownloads'])->name('statistics.downloads');
        Route::get('/statistics/logins', [StatisticsManagementController::class, 'showLogins'])->name('statistics.logins');
        Route::get('/statistics/download-excel', [StatisticsManagementController::class, 'downloadExcel'])->name('statistics.download-excel');
        Route::get('/statistics/file-details/{fileId}', [StatisticsManagementController::class, 'fileDetails'])->name('statistics.file-downloads-details');
        Route::get('/statistics/user/{userId}/logins/details', [StatisticsManagementController::class, 'userLoginsDetails'])->name('statistics.user.logins.details');
        Route::get('/statistics/menu-item-details/{menuItemId}', [StatisticsManagementController::class, 'menuItemDetails'])->name('statistics.menuItemDetails');


        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::get('/edit/{menuItem}', [MenuController::class, 'edit'])->name('edit');
        Route::post('/toggle-status/{menuItem}', [MenuController::class, 'toggleStatus'])->name('toggleStatus');

//        Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
        Route::get('/get-menu-items', [MenuController::class, 'getMenuItems']);
        Route::get('/get-menu-items-with-files', [MenuController::class, 'getMenuItemsWithFiles']);

        Route::post('/menu-items', [MenuController::class, 'store'])->name('menu-items.store');
        Route::patch('/menu-items/{menuItem}', [MenuController::class, 'update'])->name('menu-items.update');
        Route::post('/menu-items/update-order', [MenuItemController::class, 'updateOrder'])->name('menu-items.update-order');
        Route::post('/menu-items/update-type', [MenuItemController::class, 'updateType'])->name('menu-items.update-type');
        Route::post('/update-tree-structure', [MenuItemController::class, 'updateTreeStructure'])->name('menu.update-tree-structure');
        Route::get('/menu-items/{id}/has-sub-items', [MenuItemController::class, 'hasSubItems'])->name('menu-items.has-sub-items');
        // Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');
        Route::delete('/menu-items/{menuItem}', [MenuController::class, 'destroy'])->name('menu-items.destroy');



        Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
        Route::post('/files/store', [FileController::class, 'store'])->name('files.store');
        Route::get('/file/edit/{file}', [FileController::class, 'edit'])->name('file.edit');
        Route::patch('/files/{file}', [FileController:: class, 'update'])->name('file.update');
        Route::delete('/files/delete/{id}', [FileController::class, 'delete'])->name('files.delete');
        Route::get('/files/download/{file}', [FileController::class, 'download'])->name('files.download');
        Route::get('/files/directory-structure', [FileController::class, 'getDirectoryStructure']);
        Route::post('/file/toggle-status/{id}', [FileController::class, 'toggleStatus'])->name('file.toggleStatus');

        Route::prefix('concessions')->name('concessions.')->group(function () {
            Route::get('/', [ConcessionsViewController::class, 'index'])->name('index');
            Route::get('/create', [ConcessionsViewController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [ConcessionsViewController::class, 'edit'])->name('edit');
            Route::post('/store', [ConcessionsManagementController::class, 'store'])->name('store');
            Route::patch('/update/{id}', [ConcessionsManagementController::class, 'update'])->name('update');
        });

        Route::prefix('users')->name('users.')->middleware(['auth', 'verified'])->group(function () {
            Route::get('/usergroups', [UserGroupsController::class, 'index'])->name('groups');
            Route::get('/usergroups/create', [UserGroupsController::class, 'create'])->name('group.create');
            Route::patch('/usergroups/update', [UserGroupsController::class, 'update'])->name('group.update');
            Route::post('/usergroups/store', [UserGroupsController::class, 'store'])->name('group.store');
            Route::get('/usergroups/edit/{id}', [UserGroupsController::class, 'edit'])->name('group.edit');
            Route::get('/usergroups/{groupId}/permissions/edit', [PermissionViewController::class, 'editGroupPermissions'])->name('group.permissions.edit');
            Route::post('/permissions/save', [MenuController::class, 'savePermissions'])->name('group.permissions.save');

            Route::get('applications/view', [ApplicationViewController::class, 'index'])->name('applications.view');
            Route::patch('/applications/update-status/{id}', [ApplicationManagementController::class, 'updateStatus'])->name('applications.updateStatus');
            Route::get('/applications/details/{id}', [ApplicationViewController::class, 'showDetails'])->name('applications.details');
            Route::post('/applications/create', [ApplicationManagementController::class, 'create'])->name('applications.create');
            Route::get('/applications/accept/{id}', [ApplicationManagementController::class, 'acceptApplication'])->name('applications.accept');
            Route::get('/applications/reject/{id}', [ApplicationManagementController::class, 'rejectApplication'])->name('applications.reject');


            Route::get('/users', [UserViewController::class, 'index'])->name('index');
            Route::get('/create', [UserViewController::class, 'create'])->name('create');
            Route::get('/edit/{user}', [UserViewController::class, 'edit'])->name('edit');
            Route::patch('/users/edit/{user}', [UserManagementController::class, 'update'])->name('update');
            Route::get('/{user}/permissions/edit', [PermissionViewController::class, 'editUserPermissions'])->name('permissions.edit');
            Route::post('/store', [UserManagementController::class, 'store'])->name('store');

            Route::get('/get-menu-items-group-permissions', [MenuController::class, 'getMenuItemWithGroupPermissions']);
            Route::get('/get-menu-items-user-permissions', [MenuController::class, 'getMenuItemWithUserPermissions']);
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/update-or-create-user-permission', [PermissionController::class, 'assignOrUpdateUserPermission'])->name('permission.user.assign');
            Route::get('/update-or-create-group-permission', [PermissionController::class, 'assignOrUpdateGroupPermissions'])->name('permission.group.assign');
            // Route::post('/update-group-permission', [PermissionController::class, 'updateGroupPermission'])->name('permission.updateGroup');
            // Route::post('/update-user-permission', [PermissionController::class, 'updateUserPermission'])->name('permission.updateUser');
            Route::post('/update-group-permission', [PermissionManagementController::class, 'updateGroupPermission'])->name('permissions.updateGroup');
            Route::post('/update-user-permission', [PermissionManagementController::class, 'updateUserPermission'])->name('permissions.updateUser');

            Route::get('/copy/from-user/{targetUserId}', [PermissionViewController::class, 'copyFromUser'])->name('copy.from.user');
            Route::post('/copy/copy-user-permissions', [PermissionManagementController::class, 'copyUserPermissions'])->name('copy.copyUserPermissions');

            Route::get('/copy/from-group', [PermissionViewController::class, 'copyFromGroup'])->name('copy.from.group');
        });

        Route::prefix('autos')->name('autos.')->group(function () {
            Route::get('/', [AutosViewController::class, 'index'])->name('index');
            Route::get('/create', [AutosViewController::class, 'create'])->name('create');
            Route::post('/store', [AutosManagementController::class, 'store'])->name('store');
            Route::get('/{auto}/edit', [AutosViewController::class, 'edit'])->name('edit');
            Route::patch('/{auto}', [AutosManagementController::class, 'update'])->name('update');
            Route::delete('/{auto}', [AutosManagementController::class, 'destroy'])->name('destroy');
        });
    });

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
