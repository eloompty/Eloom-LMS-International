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
use Modules\Ticket\Http\Controllers\TicketController;

Route::group([
    'prefix' => 'admin/ticket'
], function () {
    Route::get('/', [TicketController::class, 'index'])->name('admin.ticket.index');
    Route::get('show/{id}', [TicketController::class, 'show'])->name('admin.ticket.show');
    Route::post('reply/{id}', [TicketController::class, 'reply'])->name('admin.ticket.reply');
    Route::get('close/{id}', [TicketController::class, 'close'])->name('admin.ticket.close');
    Route::get('reopen/{id}', [TicketController::class, 'reopen'])->name('admin.ticket.reopen');
});
