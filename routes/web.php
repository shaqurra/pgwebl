<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\StasiunController;

Route::get('/', [PublicController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/map', [PointsController::class, 'index'])->name('map');
    Route::get('/table', [TableController::class, 'index'])->name('table');
    Route::get('/profile/edit', function () {
    // Ganti dengan controller jika ada
    return view('profile.edit');
})->name('profile.edit');

    Route::get('/stasiun', [StasiunController::class, 'index'])->name('stasiun.index');
    Route::post('/stasiun', [StasiunController::class, 'store'])->name('stasiun.store');
    Route::get('/stasiun/{id}', [StasiunController::class, 'show'])->name('stasiun.show');
    Route::patch('/stasiun/{id}', [StasiunController::class, 'update'])->name('stasiun.update');
    Route::delete('/stasiun/{id}', [StasiunController::class, 'destroy'])->name('stasiun.destroy');
});

Route::resource('points', PointsController::class);
Route::resource('polyline', PolylineController::class);
Route::resource('polygon', PolygonController::class);

require __DIR__ . '/auth.php';
