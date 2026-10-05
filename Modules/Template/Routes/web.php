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
use Modules\Template\Http\Controllers\TemplateController;
use Modules\Template\Http\Controllers\TemplateDataController;

Route::group([
    'prefix' => 'admin/template'
], function () {
    Route::get('/', [TemplateController::class, 'index'])->name('admin.template.index');
    Route::get('show/{id}', [TemplateController::class, 'show'])->name('admin.template.show');
    Route::group([
        'prefix' => 'data'
    ], function () {
        Route::get('{id}', [TemplateDataController::class, 'index'])->name('admin.template.data.index');
        Route::post('{id}', [TemplateDataController::class, 'update'])->name('admin.template.data.update');
    });
});
