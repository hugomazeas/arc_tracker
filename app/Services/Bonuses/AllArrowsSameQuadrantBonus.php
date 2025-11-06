<?php

namespace App\Services\Bonuses;

class AllArrowsSameQuadrantBonus extends Bonus
{
    public function check(array $arrows): bool
    {
        if (count($arrows) !== 4) {
            return false;
        }

        // Check that all arrows scored (no misses)
        foreach ($arrows as $arrow) {
            if (!isset($arrow['score']) || $arrow['score'] === 0) {
                return false;
            }
        }

        $quadrants = array_map(function ($arrow) {
            return $this->getQuadrant($arrow['x'], $arrow['y']);
        }, $arrows);

        // Check if all quadrants are the same
        return count(array_unique($quadrants)) === 1;
    }

    /**
     * Determine which quadrant the arrow is in
     * Assumes target center is at (0, 0)
     * Returns: NE, NW, SE, SW
     */
    private function getQuadrant(float $x, float $y): string
    {
        if ($x >= 0 && $y >= 0) {
            return 'NE';
        } elseif ($x < 0 && $y >= 0) {
            return 'NW';
        } elseif ($x < 0 && $y < 0) {
            return 'SW';
        } else {
            return 'SE';
        }
    }

    public function getPoints(): int
    {
        return 8;
    }

    public function getName(): string
    {
        return 'Quadrant Master';
    }

    public function getDescription(): string
    {
        return 'All 4 arrows in the same quadrant (no misses)';
    }
}
