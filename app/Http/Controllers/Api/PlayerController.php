<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PlayerController extends Controller
{
    /**
     * Display a listing of all players
     */
    public function index(): JsonResponse
    {
        $players = Player::orderBy('name')->get();

        return response()->json($players);
    }

    /**
     * Store a newly created player
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:players',
        ]);

        Player::create($validated);

        return redirect()->back();
    }

    /**
     * Display the specified player
     */
    public function show(Player $player): JsonResponse
    {
        return response()->json($player);
    }

    /**
     * Update the specified player
     */
    public function update(Request $request, Player $player): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:players,name,' . $player->id,
        ]);

        $player->update($validated);

        return response()->json($player);
    }

    /**
     * Remove the specified player
     */
    public function destroy(Player $player): RedirectResponse
    {
        $player->delete();

        return redirect()->back();
    }
}
