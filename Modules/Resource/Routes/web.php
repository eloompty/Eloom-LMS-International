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
use Modules\Resource\Http\Controllers\ResourceCategoryController;
use Modules\Resource\Http\Controllers\ResourceController;

Route::group([
    'prefix' => 'admin/resource'
], function () {
    Route::get('/', [ResourceController::class, 'index'])->name('admin.resource.index');
    Route::get('create', [ResourceController::class, 'create'])->name('admin.resource.create');
    Route::post('store', [ResourceController::class, 'store'])->name('admin.resource.store');
    Route::get('edit/{id}', [ResourceController::class, 'edit'])->name('admin.resource.edit');
    Route::post('update/{id}', [ResourceController::class, 'update'])->name('admin.resource.update');
    Route::get('delete/{id}', [ResourceController::class, 'destroy'])->name('admin.resource.delete');
    Route::get('menu', [ResourceController::class, 'menu'])->name('admin.resource.menu');
    Route::group([
        'prefix' => 'category'
    ], function () {
        Route::get('/', [ResourceCategoryController::class, 'index'])->name('admin.resource.category.index');
        Route::get('create', [ResourceCategoryController::class, 'create'])->name('admin.resource.category.create');
        Route::post('store', [ResourceCategoryController::class, 'store'])->name('admin.resource.category.store');
        Route::get('edit/{id}', [ResourceCategoryController::class, 'edit'])->name('admin.resource.category.edit');
        Route::post('update/{id}', [ResourceCategoryController::class, 'update'])->name('admin.resource.category.update');
        Route::get('delete/{id}', [ResourceCategoryController::class, 'destroy'])->name('admin.resource.category.delete');
    });
});