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
use Modules\Country\Http\Controllers\CountryController;

Route::group([
    'prefix' => 'admin/country'
], function () {
    Route::get('/', [CountryController::class, 'index'])->name('admin.country.index');
    Route::post('update/{id}', [CountryController::class, 'update'])->name('admin.country.update');
    Route::post('updateBulk', [CountryController::class, 'updateBulk'])->name('admin.country.update.bulk');
});