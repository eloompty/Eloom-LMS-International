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
use Modules\Chat\Http\Controllers\ChatController;

Route::group([
    'prefix' => 'admin/chat'
], function () {
    Route::get('/', [ChatController::class, 'index'])->name('admin.chat.index');
    Route::get('create', [ChatController::class, 'create'])->name('admin.chat.create');
    Route::post('store', [ChatController::class, 'store'])->name('admin.chat.store');
    Route::get('show/{id}', [ChatController::class, 'show'])->name('admin.chat.show');
    Route::get('edit/{id}', [ChatController::class, 'edit'])->name('admin.chat.edit');
    Route::post('update/{id}', [ChatController::class, 'update'])->name('admin.chat.update');
    Route::get('delete/{id}', [ChatController::class, 'destroy'])->name('admin.chat.delete');
    Route::get('getIntakeCourse', [ChatController::class, 'getIntakeCourse']);
    Route::get('getIntakeSemester', [ChatController::class, 'getIntakeSemester']);
    Route::get('getIntakeSubject', [ChatController::class, 'getIntakeSubject']);
    Route::get('getIntakeUnit', [ChatController::class, 'getIntakeUnit']);
    Route::get('getStudentTeacherFromIntakeSubject', [ChatController::class, 'getStudentTeacherFromIntakeSubject']);
    Route::post('createMessage', [ChatController::class, 'createMessage']);
    Route::post('sendMessage/{id}', [ChatController::class, 'sendMessage'])->name('admin.chat.message.send');
    Route::get('loadMessage', [ChatController::class, 'loadMessages']);
});
