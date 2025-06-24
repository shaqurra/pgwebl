<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\StasiunController;
use App\Http\Controllers\JalurKeretaController;
use App\Http\Controllers\Api\ProvinsiJakartaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// FIXED Routes untuk Stasiun (proper order and methods)
Route::get('/stasiun', [StasiunController::class, 'index'])->name('api.stasiun');
Route::get('/stasiun/map', [StasiunController::class, 'map'])->name('stasiun.map'); // Move before {id} route
Route::get('/stasiun/{id}', [StasiunController::class, 'show'])->whereNumber('id');
Route::post('/stasiun', [StasiunController::class, 'store']);
Route::put('/stasiun/{id}', [StasiunController::class, 'update'])->whereNumber('id');
Route::patch('/stasiun/{id}', [StasiunController::class, 'update'])->whereNumber('id');
Route::delete('/stasiun/{id}', [StasiunController::class, 'destroy'])->whereNumber('id');

// Routes lainnya
Route::get('/jalur-kereta', [JalurKeretaController::class, 'index'])->name('api.jalurkereta');
Route::get('/polygon', [ApiController::class, 'polygon'])->name('api.polygon');
Route::get('/polygon/{id}', [ApiController::class, 'polygons'])->name('api.polygons');