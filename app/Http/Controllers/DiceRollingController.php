<?php

namespace App\Http\Controllers;

use App\Services\DiceRollingService;
use Illuminate\Http\JsonResponse;

class DiceRollingController extends Controller
{
    protected $diceRollingService;

    public function __construct(DiceRollingService $diceRollingService)
    {
        $this->diceRollingService = $diceRollingService;
    }

    public function rollDice(): JsonResponse
    {
        $result = $this->diceRollingService->rollDice();

        return response()->json(['result' => $result]);
    }
}
