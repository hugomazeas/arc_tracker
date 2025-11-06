<?php

namespace App\Services;

use App\Services\Bonuses\Bonus;
use App\Services\Bonuses\SameScoreFourTimesBonus;
use App\Services\Bonuses\LastArrowTenBonus;
use App\Services\Bonuses\AllArrowsSameQuadrantBonus;
use App\Services\Bonuses\ConsecutiveSuiteBonus;
use App\Services\Bonuses\TwoPairsBonus;
use App\Services\Bonuses\AscendingOrderBonus;
use App\Services\Bonuses\OuterRimBonus;
use App\Services\Bonuses\InnerRimBonus;

class BonusCalculationService
{
    private array $bonuses = [];

    public function __construct()
    {
        // Auto-register all bonus implementations
        $this->bonuses = [
            new SameScoreFourTimesBonus(),
            new LastArrowTenBonus(),
            new AllArrowsSameQuadrantBonus(),
            new ConsecutiveSuiteBonus(),
            new TwoPairsBonus(),
            new AscendingOrderBonus(),
            new OuterRimBonus(),
            new InnerRimBonus(),
        ];
    }

    /**
     * Calculate total bonus points for a set of arrows
     *
     * @param array $arrows Array of arrows with x, y, and score
     * @return array ['total' => int, 'applied' => array of bonus details]
     */
    public function calculateBonuses(array $arrows): array
    {
        $total = 0;
        $applied = [];

        foreach ($this->bonuses as $bonus) {
            if ($bonus->check($arrows)) {
                $points = $bonus->getPoints();
                $total += $points;
                $applied[] = [
                    'name' => $bonus->getName(),
                    'description' => $bonus->getDescription(),
                    'points' => $points,
                ];
            }
        }

        return [
            'total' => $total,
            'applied' => $applied,
        ];
    }

    /**
     * Get all available bonuses
     *
     * @return array
     */
    public function getAllBonuses(): array
    {
        return array_map(function (Bonus $bonus) {
            return [
                'name' => $bonus->getName(),
                'description' => $bonus->getDescription(),
                'points' => $bonus->getPoints(),
            ];
        }, $this->bonuses);
    }
}
