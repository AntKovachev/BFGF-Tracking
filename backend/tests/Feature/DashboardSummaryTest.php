<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_summary_returns_totals_balance_and_goal_progress(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Transaction::factory()->for($user)->create([
            'amount' => 3000,
            'type' => 'income',
            'date' => '2026-04-10',
        ]);

        Transaction::factory()->for($user)->create([
            'amount' => 1000,
            'type' => 'expense',
            'date' => '2026-04-11',
        ]);

        $response = $this->getJson('/api/dashboard/summary');

        $response->assertOk()
            ->assertJsonPath('summary.total_income', 3000)
            ->assertJsonPath('summary.total_expenses', 1000)
            ->assertJsonPath('summary.balance', 2000)
            ->assertJsonPath('goal.goal_amount', 250000)
            ->assertJsonPath('goal.remaining_amount', 248000)
            ->assertJsonPath('goal.progress_percentage', 0.8);
    }
}
