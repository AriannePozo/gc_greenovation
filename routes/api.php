<?php

use App\Http\Controllers\Api\ReadingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::controller(ReadingController::class)->group(function () {
    Route::post('store', 'store');
    Route::post('index', 'index');
});
