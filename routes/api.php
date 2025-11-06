<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Services\BonusCalculationService;

// Players
Route::apiResource('players', PlayerController::class);

// Games
Route::apiResource('games', GameController::class)->only(['index', 'store', 'show', 'destroy']);

// Leaderboard
Route::get('leaderboard', [LeaderboardController::class, 'index']);
Route::get('leaderboard/weekly', [LeaderboardController::class, 'weekly']);

// Bonuses info endpoint
Route::get('bonuses', function (BonusCalculationService $bonusService) {
    return response()->json($bonusService->getAllBonuses());
});
