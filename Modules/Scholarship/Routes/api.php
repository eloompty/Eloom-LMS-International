<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/scholarship', function () {
    return response()->json(['name' => 'Scholarship']);
});
