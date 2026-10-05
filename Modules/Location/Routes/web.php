<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Location\Http\Controllers\Admin\Level2Controller;
use Modules\Location\Http\Controllers\Admin\Level3Controller;
use Modules\Location\Http\Controllers\Admin\Level4Controller;
use Modules\Location\Http\Controllers\Admin\Level5Controller;
use Modules\Location\Http\Controllers\Admin\LocationController;

Route::group(['middleware' => ['auth:user'], 'prefix' => 'admin/location'], function () {
    Route::get('/', [LocationController::class, 'index'])->name('admin.location.index');
    Route::get('create', [LocationController::class, 'create'])->name('admin.location.create');
    Route::post('store', [LocationController::class, 'store'])->name('admin.location.store');
    Route::get('edit/{id}', [LocationController::class, 'edit'])->name('admin.location.edit');
    Route::post('update/{id}', [LocationController::class, 'update'])->name('admin.location.update');
    Route::get('destroy/{id}', [LocationController::class, 'destroy'])->name('admin.location.destroy');
    Route::group(['prefix' => 'level_2'], function () {
        Route::get('/{id}', [Level2Controller::class, 'index'])->name('admin.location.level2.index');
        Route::get('create/{id}', [Level2Controller::class, 'create'])->name('admin.location.level2.create');
        Route::post('store/{id}', [Level2Controller::class, 'store'])->name('admin.location.level2.store');
        Route::get('edit/{id}', [Level2Controller::class, 'edit'])->name('admin.location.level2.edit');
        Route::post('update/{id}', [Level2Controller::class, 'update'])->name('admin.location.level2.update');
        Route::get('destroy/{id}', [Level2Controller::class, 'destroy'])->name('admin.location.level2.destroy');
        Route::group(['prefix' => 'level_3'], function () {
            Route::get('/{id}', [Level3Controller::class, 'index'])->name('admin.location.level3.index');
            Route::get('create/{id}', [Level3Controller::class, 'create'])->name('admin.location.level3.create');
            Route::post('store/{id}', [Level3Controller::class, 'store'])->name('admin.location.level3.store');
            Route::get('edit/{id}', [Level3Controller::class, 'edit'])->name('admin.location.level3.edit');
            Route::post('update/{id}', [Level3Controller::class, 'update'])->name('admin.location.level3.update');
            Route::get('destroy/{id}', [Level3Controller::class, 'destroy'])->name('admin.location.level3.destroy');
            Route::group(['prefix' => 'level_4'], function () {
                Route::get('/{id}', [Level4Controller::class, 'index'])->name('admin.location.level4.index');
                Route::get('create/{id}', [Level4Controller::class, 'create'])->name('admin.location.level4.create');
                Route::post('store/{id}', [Level4Controller::class, 'store'])->name('admin.location.level4.store');
                Route::get('edit/{id}', [Level4Controller::class, 'edit'])->name('admin.location.level4.edit');
                Route::post('update/{id}', [Level4Controller::class, 'update'])->name('admin.location.level4.update');
                Route::get('destroy/{id}', [Level4Controller::class, 'destroy'])->name('admin.location.level4.destroy');
                Route::group(['prefix' => 'level_5'], function () {
                    Route::get('/{id}', [Level5Controller::class, 'index'])->name('admin.location.level5.index');
                    Route::get('create/{id}', [Level5Controller::class, 'create'])->name('admin.location.level5.create');
                    Route::post('store/{id}', [Level5Controller::class, 'store'])->name('admin.location.level5.store');
                    Route::get('edit/{id}', [Level5Controller::class, 'edit'])->name('admin.location.level5.edit');
                    Route::post('update/{id}', [Level5Controller::class, 'update'])->name('admin.location.level5.update');
                    Route::get('destroy/{id}', [Level5Controller::class, 'destroy'])->name('admin.location.level5.destroy');
                });
            });
        });
    });
});

Route::get('admin/sub_location', [LocationController::class, 'getSubLocations']);