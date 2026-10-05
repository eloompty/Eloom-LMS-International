<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

/* Firebase Cloud Messaging service worker, served from the site root so its
   scope covers the whole application. Rendered rather than served statically so
   the project configuration comes from config/firebase.php. */
Route::get('/firebase-messaging-sw.js', function () {
    abort_unless(config('firebase.web.projectId'), 404);

    return response()
        ->view('firebase-messaging-sw')
        ->header('Content-Type', 'application/javascript')
        ->header('Service-Worker-Allowed', '/');
})->name('firebase.service-worker');
