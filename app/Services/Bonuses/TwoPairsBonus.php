<?php

namespace App\Services\Bonuses;

class TwoPairsBonus extends Bonus
{
    public function check(array $arrows): bool
    {
        if (count($arrows) !== 4) {
            return false;
        }

        // Extract scores
        $scores = array_map(fn($arrow) => $arrow['score'] ?? 0, $arrows);

        // Count occurrences of each score
        $scoreCounts = array_count_values($scores);

        // Filter to only pairs (scores that appear exactly twice)
        $pairs = array_filter($scoreCounts, fn($count) => $count === 2);

        // Must have exactly 2 different pairs (meaning 2 scores each appearing twice)
        return count($pairs) === 2;
    }

    public function getPoints(): int
    {
        return 3;
    }

    public function getName(): string
    {
        return 'Two Pairs';
    }

    public function getDescription(): string
    {
        return 'Two pairs of same scores (e.g., 8-8-9-9 or 6-6-7-7)';
    }
}
