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
use Modules\Social\Http\Controllers\SocialCategoryController;

Route::group([
    'prefix' => 'admin/social'
], function () {
    Route::group([
        'prefix' => 'category'
    ], function () {
        Route::get('/', [SocialCategoryController::class, 'index'])->name('admin.social.category.index');
        Route::get('create', [SocialCategoryController::class, 'create'])->name('admin.social.category.create');
        Route::post('store', [SocialCategoryController::class, 'store'])->name('admin.social.category.store');
        Route::get('edit/{id}', [SocialCategoryController::class, 'edit'])->name('admin.social.category.edit');
        Route::post('update/{id}', [SocialCategoryController::class, 'update'])->name('admin.social.category.update');
        Route::get('destroy/{id}', [SocialCategoryController::class, 'destroy'])->name('admin.social.category.delete');
    });
});