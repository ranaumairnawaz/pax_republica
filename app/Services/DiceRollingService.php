<?php

namespace App\Services;

use App\Models\Character;
use Exception;

class DiceRollingService
{
    /**
     * Performs a standard dice roll.
     *
     * @param Character $character
     * @param string $rollableType
     * @param int $rollableId
     * @param int $modifier
     * @return array
     * @throws Exception
     */
    public function roll(Character $character, string $rollableType, int $rollableId, int $modifier = 0): array
    {
        $rollable = $character->{$rollableType}()->find($rollableId);

        if (!$rollable) {
            throw new Exception("{$rollableType} not found on character.");
        }

        $roll = rand(1, 20);
        $total = $roll + $rollable->value + $modifier;

        return [
            'roll' => $roll,
            'modifier' => $rollable->value + $modifier,
            'total' => $total,
            'rollable' => $rollable,
        ];
    }

    /**
     * Performs a challenge dice roll.
     *
     * @param Character $character1
     * @param string $rollableType1
     * @param int $rollableId1
     * @param Character $character2
     * @param string $rollableType2
     * @param int $rollableId2
     * @param int $modifier1
     * @param int $modifier2
     * @return array
     * @throws Exception
     */
    public function challengeRoll(
        Character $character1,
        string $rollableType1,
        int $rollableId1,
        Character $character2,
        string $rollableType2,
        int $rollableId2,
        int $modifier1 = 0,
        int $modifier2 = 0
    ): array {
        $roll1Result = $this->roll($character1, $rollableType1, $rollableId1, $modifier1);
        $roll2Result = $this->roll($character2, $rollableType2, $rollableId2, $modifier2);

        $winner = $roll1Result['total'] > $roll2Result['total'] ? $character1 : $character2;
        $difference = abs($roll1Result['total'] - $roll2Result['total']);

        return [
            'roll1' => $roll1Result,
            'roll2' => $roll2Result,
            'winner' => $winner,
            'difference' => $difference,
        ];
    }

    public function rollDice(): int
    {
        return rand(1, 100);
    }
}
