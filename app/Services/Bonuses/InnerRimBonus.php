<?php

namespace App\Services\Bonuses;

class InnerRimBonus extends Bonus
{
    public function check(array $arrows): bool
    {
        if (count($arrows) !== 4) {
            return false;
        }

        // Extract scores
        $scores = array_map(fn($arrow) => $arrow['score'] ?? 0, $arrows);

        // Check if all arrows scored 9 or 10
        foreach ($scores as $score) {
            if ($score !== 9 && $score !== 10) {
                return false;
            }
        }

        return true;
    }

    public function getPoints(): int
    {
        return 5;
    }

    public function getName(): string
    {
        return 'Inner Rim';
    }

    public function getDescription(): string
    {
        return 'All four arrows score 9 or 10 points';
    }
}
