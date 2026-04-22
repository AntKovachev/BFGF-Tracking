<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    private const GOAL_AMOUNT = 250000;

    public function summary(): JsonResponse
    {
        $transactions = auth()->user()->transactions();
        $totalIncome = (float) $transactions->where('type', 'income')->sum('amount');
        $totalExpenses = (float) auth()->user()->transactions()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpenses;
        $progressPercentage = self::GOAL_AMOUNT > 0
            ? min(100, round(($balance / self::GOAL_AMOUNT) * 100, 2))
            : 0;

        $recentTransactions = auth()->user()->transactions()
            ->latest('date')
            ->latest('id')
            ->take(5)
            ->get();

        return response()->json([
            'summary' => [
                'total_income' => round($totalIncome, 2),
                'total_expenses' => round($totalExpenses, 2),
                'balance' => round($balance, 2),
            ],
            'goal' => [
                'goal_amount' => self::GOAL_AMOUNT,
                'progress_percentage' => $progressPercentage,
                'remaining_amount' => max(0, round(self::GOAL_AMOUNT - $balance, 2)),
            ],
            'recent_transactions' => $recentTransactions,
        ]);
    }
}
