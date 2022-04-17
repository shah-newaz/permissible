<?php

use Illuminate\Support\Facades\Route;
use Shahnewaz\Permissible\Http\Controllers\RolePermissionController;
use Shahnewaz\Permissible\Http\Controllers\UsersController;

Route::namespace('Shahnewaz\Permissible\Http\Controllers')->middleware('web')->group(function () {

    Route::middleware(['auth', 'role:su'])->prefix('permissible')->group(function () {
        // User Manager
        Route::get('/users', [UsersController::class, 'index'])->name('permissible.user.index');
        Route::get('/users/new', [UsersController::class, 'form'])->name('permissible.user.new');
        Route::get('/users/{user}', [UsersController::class, 'form'])->name('permissible.user.form');
        Route::post('/users/save', [UsersController::class, 'post'])->name('permissible.user.save');
        Route::post('/users/{user}/delete', [UsersController::class, 'delete'])->name('permissible.user.delete');
        Route::post('/users/{user}/restore', [UsersController::class, 'restore'])->name('permissible.user.restore');
        Route::post('/users/{user}/force-delete', [UsersController::class, 'forceDelete'])->name('permissible.user.force-delete');

        // Roles Management

        /*========================================================
            Role Permission
        =========================================================*/
        Route::get('role', 'RolePermissionController@getIndex')->name('permissible.role.index')->middleware('permission:admins.manage');
        Route::post('role', 'RolePermissionController@postRole')->name('permissible.role.post')->middleware('permission:admins.manage');

        Route::get('role/{role}/permission', 'RolePermissionController@setRolePermissions')
            ->middleware('permission:admins.manage')
            ->name('permissible.role.permission');

        Route::post('role/{role}/permission', [RolePermissionController::class, 'postRolePermissions'])
            ->middleware('permission:admins.manage')
            ->name('permissible.role.permission.post');
    });
});
