<?php

namespace App\Services\Bonuses;

class AscendingOrderBonus extends Bonus
{
    public function check(array $arrows): bool
    {
        if (count($arrows) !== 4) {
            return false;
        }

        // Extract scores in order
        $scores = array_map(fn($arrow) => $arrow['score'] ?? 0, $arrows);

        // Check if each arrow scores higher than or equal to the previous
        for ($i = 1; $i < count($scores); $i++) {
            if ($scores[$i] < $scores[$i - 1]) {
                return false;
            }
        }

        // Make sure they're not all the same (that would be boring)
        if (count(array_unique($scores)) === 1) {
            return false;
        }

        return true;
    }

    public function getPoints(): int
    {
        return 3;
    }

    public function getName(): string
    {
        return 'Ascending Order';
    }

    public function getDescription(): string
    {
        return 'Each arrow scores equal or higher than the previous';
    }
}
