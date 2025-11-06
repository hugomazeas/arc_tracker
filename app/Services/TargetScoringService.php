<?php

namespace App\Services;

class TargetScoringService
{
    // Target ring radiuses (in pixels or units, to be consistent with frontend)
    // These assume center is at (0, 0)
    private const RING_10 = 50;
    private const RING_9 = 100;
    private const RING_8 = 150;
    private const RING_7 = 200;
    private const RING_6 = 250;

    /**
     * Calculate the score for an arrow based on its coordinates
     *
     * @param float $x X coordinate (center is 0)
     * @param float $y Y coordinate (center is 0)
     * @return int Score between 0-10
     */
    public function calculateScore(float $x, float $y): int
    {
        $distance = sqrt($x * $x + $y * $y);

        if ($distance <= self::RING_10) {
            return 10;
        } elseif ($distance <= self::RING_9) {
            return 9;
        } elseif ($distance <= self::RING_8) {
            return 8;
        } elseif ($distance <= self::RING_7) {
            return 7;
        } elseif ($distance <= self::RING_6) {
            return 6;
        }

        return 0; // Miss
    }

    /**
     * Process an array of arrow coordinates and add scores
     *
     * @param array $arrows Array of arrows with x, y coordinates
     * @return array Array with x, y, and calculated score for each arrow
     */
    public function scoreArrows(array $arrows): array
    {
        return array_map(function ($arrow) {
            return [
                'x' => $arrow['x'],
                'y' => $arrow['y'],
                'score' => $this->calculateScore($arrow['x'], $arrow['y']),
            ];
        }, $arrows);
    }
}
