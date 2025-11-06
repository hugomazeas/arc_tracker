<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    /**
     * Get leaderboard for a specific time period
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $query = Game::query()
            ->select(
                'player_id',
                DB::raw('COUNT(*) as games_played'),
                DB::raw('SUM(base_score) as total_base_score'),
                DB::raw('SUM(bonus_score) as total_bonus_score'),
                DB::raw('SUM(total_score) as total_score'),
                DB::raw('AVG(total_score) as avg_score'),
                DB::raw('MAX(total_score) as best_game')
            )
            ->groupBy('player_id');

        // Apply date filters if provided
        if (isset($validated['start_date'])) {
            $query->where('created_at', '>=', $validated['start_date']);
        }
        if (isset($validated['end_date'])) {
            $query->where('created_at', '<=', $validated['end_date']);
        }

        $leaderboard = $query->get()
            ->map(function ($item) {
                $player = Player::find($item->player_id);
                return [
                    'player_id' => $item->player_id,
                    'player_name' => $player->name,
                    'games_played' => $item->games_played,
                    'total_base_score' => $item->total_base_score,
                    'total_bonus_score' => $item->total_bonus_score,
                    'total_score' => $item->total_score,
                    'avg_score' => round($item->avg_score, 2),
                    'best_game' => $item->best_game,
                ];
            })
            ->sortByDesc('total_score')
            ->values();

        return response()->json($leaderboard);
    }

    /**
     * Get weekly leaderboard
     */
    public function weekly(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'year' => 'nullable|integer|min:2020|max:2100',
            'week' => 'nullable|integer|min:1|max:53',
        ]);

        // Default to current week if not provided
        $year = $validated['year'] ?? now()->year;
        $week = $validated['week'] ?? now()->weekOfYear;

        // Calculate start and end dates for the week
        $startDate = now()->setISODate($year, $week)->startOfWeek();
        $endDate = now()->setISODate($year, $week)->endOfWeek();

        $request->merge([
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);

        $leaderboard = $this->index($request)->getData();

        return response()->json([
            'year' => $year,
            'week' => $week,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'leaderboard' => $leaderboard,
        ]);
    }
}
