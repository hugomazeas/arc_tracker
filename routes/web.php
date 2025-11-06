<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PlayerController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('manage')->group(function () {
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
});
