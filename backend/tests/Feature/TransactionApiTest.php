<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TransactionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_and_list_only_own_transactions(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        Transaction::factory()->for($otherUser)->create();

        $createResponse = $this->postJson('/api/transactions', [
            'amount' => 1200,
            'type' => 'income',
            'category' => 'Salary',
            'description' => 'Monthly salary',
            'date' => '2026-04-01',
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('type', 'income');

        $listResponse = $this->getJson('/api/transactions');

        $listResponse->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.description', 'Monthly salary');
    }

    public function test_user_cannot_delete_other_users_transaction(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        Sanctum::actingAs($user);

        $otherTransaction = Transaction::factory()->for($otherUser)->create();

        $response = $this->deleteJson('/api/transactions/'.$otherTransaction->id);

        $response->assertNotFound();
    }
}
