<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InvestmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_investment_responses_include_profit_loss(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $createResponse = $this->postJson('/api/investments', [
            'name' => 'ETF Portfolio',
            'amount_invested' => 5000,
            'current_value' => 5300,
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('profit_loss', 300);

        $otherUser = User::factory()->create();
        Investment::factory()->for($otherUser)->create();

        $listResponse = $this->getJson('/api/investments');

        $listResponse->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'ETF Portfolio')
            ->assertJsonPath('0.profit_loss', 300);
    }
}
