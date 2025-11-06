<?php

namespace App\Services\Bonuses;

abstract class Bonus
{
    /**
     * Check if this bonus applies to the given arrows
     *
     * @param array $arrows Array of arrows with x, y, and score keys
     * @return bool
     */
    abstract public function check(array $arrows): bool;

    /**
     * Get the bonus points value
     *
     * @return int
     */
    abstract public function getPoints(): int;

    /**
     * Get the bonus name
     *
     * @return string
     */
    abstract public function getName(): string;

    /**
     * Get the bonus description
     *
     * @return string
     */
    abstract public function getDescription(): string;
}
