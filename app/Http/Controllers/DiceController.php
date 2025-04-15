<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DiceController extends Controller
{
    /**
     * Handle a dice roll request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function roll(Request $request)
    {
        // Basic validation - can be expanded
        $validated = $request->validate([
            'dice_count' => ['required', 'integer', 'min:1', 'max:20'], // Limit max dice
            'dice_sides' => ['required', 'integer', 'min:2', 'max:100'], // Limit max sides
            'modifier' => ['sometimes', 'integer', 'min:-100', 'max:100'], // Limit modifier
            'reason' => ['nullable', 'string', 'max:100'], // Optional reason for the roll
            // Potentially add 'character_id', 'scene_id', 'skill_id' etc. later for context/logging
        ]);

        $diceCount = $validated['dice_count'];
        $diceSides = $validated['dice_sides'];
        $modifier = $validated['modifier'] ?? 0;
        $reason = $validated['reason'] ?? null;

        $rolls = [];
        $total = 0;

        for ($i = 0; $i < $diceCount; $i++) {
            $roll = random_int(1, $diceSides);
            $rolls[] = $roll;
            $total += $roll;
        }

        $finalTotal = $total + $modifier;

        // Prepare the result string
        $rollString = implode(', ', $rolls);
        $modifierString = $modifier > 0 ? " + {$modifier}" : ($modifier < 0 ? " - " . abs($modifier) : "");
        $resultDescription = "Rolling {$diceCount}d{$diceSides}{$modifierString}";
        if ($reason) {
            $resultDescription .= " for {$reason}";
        }
        $resultText = "Result: [{$rollString}]{$modifierString} = {$finalTotal}";

        // TODO: Log the dice roll (e.g., create a system post in the scene)

        return response()->json([
            'success' => true,
            'rolls' => $rolls,
            'total' => $total,
            'modifier' => $modifier,
            'final_total' => $finalTotal,
            'description' => $resultDescription,
            'result_text' => $resultText,
            // Include user/character info for display
            'roller_name' => Auth::user()->account_name ?? 'User',
        ]);
    }
}
