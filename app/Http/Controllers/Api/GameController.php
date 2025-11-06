<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\BonusCalculationService;
use App\Services\TargetScoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function __construct(
        private TargetScoringService $scoringService,
        private BonusCalculationService $bonusService
    ) {}

    /**
     * Display a listing of games
     */
    public function index(Request $request): JsonResponse
    {
        $query = Game::with('player');

        // Filter by player if provided
        if ($request->has('player_id')) {
            $query->where('player_id', $request->player_id);
        }

        // Filter by date range if provided
        if ($request->has('start_date')) {
            $query->where('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->where('created_at', '<=', $request->end_date);
        }

        $games = $query->orderBy('created_at', 'desc')->get();

        return response()->json($games);
    }

    /**
     * Store a newly created game
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'player_id' => 'required|exists:players,id',
            'arrows' => 'required|array|size:4',
            'arrows.*.x' => 'required|numeric',
            'arrows.*.y' => 'required|numeric',
        ]);

        // Score the arrows based on coordinates
        $scoredArrows = $this->scoringService->scoreArrows($validated['arrows']);

        // Calculate base score
        $baseScore = array_sum(array_column($scoredArrows, 'score'));

        // Calculate bonuses
        $bonusResult = $this->bonusService->calculateBonuses($scoredArrows);
        $bonusScore = $bonusResult['total'];

        // Create the game
        $game = Game::create([
            'player_id' => $validated['player_id'],
            'arrow_data' => $scoredArrows,
            'base_score' => $baseScore,
            'bonus_score' => $bonusScore,
            'total_score' => $baseScore + $bonusScore,
        ]);

        // Load player relationship
        $game->load('player');

        // Add bonus details to response
        $gameArray = $game->toArray();
        $gameArray['bonuses_applied'] = $bonusResult['applied'];

        return response()->json($gameArray, 201);
    }

    /**
     * Display the specified game
     */
    public function show(Game $game): JsonResponse
    {
        $game->load('player');

        // Recalculate bonuses for display
        $bonusResult = $this->bonusService->calculateBonuses($game->arrow_data);
        $gameArray = $game->toArray();
        $gameArray['bonuses_applied'] = $bonusResult['applied'];

        return response()->json($gameArray);
    }

    /**
     * Remove the specified game
     */
    public function destroy(Game $game): JsonResponse
    {
        $game->delete();

        return response()->json(null, 204);
    }
}
