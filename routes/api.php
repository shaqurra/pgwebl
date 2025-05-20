<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/points', [ApiController::class, 'points'])->name('api.points');
Route::get('/point/{id}', [ApiController::class, 'point'])->name('api.point');
Route::get('/polyline', [ApiController::class, 'polyline'])->name('api.polyline');
Route::get('/polyline/{id}', [ApiController::class, 'polylines'])->name('api.polylines');
Route::get('/polygon', [ApiController::class, 'polygon'])->name('api.polygon');
Route::get('/polygon/{id}', [ApiController::class, 'polygons'])->name('api.polygons'); //
