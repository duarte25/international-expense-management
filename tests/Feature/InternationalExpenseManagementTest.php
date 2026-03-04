<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InternationalExpenseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_rejects_invalid_cpf(): void
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'logradouro' => 'Praca da Se',
                'bairro' => 'Se',
                'localidade' => 'Sao Paulo',
                'uf' => 'SP',
            ], 200),
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'cpf' => '11111111111',
            'cep' => '01001000',
            'street' => 'Praca da Se',
            'house_number' => '100',
            'neighborhood' => 'Se',
            'city' => 'Sao Paulo',
            'state' => 'SP',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['cpf']);
    }

    public function test_register_rejects_duplicate_cpf(): void
    {
        Http::fake([
            'viacep.com.br/*' => Http::response([
                'logradouro' => 'Praca da Se',
                'bairro' => 'Se',
                'localidade' => 'Sao Paulo',
                'uf' => 'SP',
            ], 200),
        ]);

        $payload = [
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'cpf' => '11144477735',
            'cep' => '01001000',
            'street' => 'Praca da Se',
            'house_number' => '100',
            'neighborhood' => 'Se',
            'city' => 'Sao Paulo',
            'state' => 'SP',
        ];

        $this->postJson('/api/register', $payload)->assertCreated();

        $this->postJson('/api/register', [
            ...$payload,
            'email' => 'maria2@example.com',
        ])->assertStatus(422)->assertJsonValidationErrors(['cpf']);
    }

    public function test_register_rejects_cep_with_letters(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Maria',
            'email' => 'maria.cep@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'cpf' => '11144477735',
            'cep' => '0100AB00',
            'street' => 'Rua qualquer',
            'house_number' => '10',
            'neighborhood' => 'Bairro',
            'city' => 'Cidade',
            'state' => 'AM',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['cep']);
    }

    public function test_user_can_only_access_own_expenses(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $expenseA = Expense::create([
            'user_id' => $userA->id,
            'amount_original' => 100.00,
            'currency_code' => 'USD',
            'exchange_rate' => 5.400000,
            'amount_brl' => 540.00,
            'status' => 'converted',
            'converted_at' => now(),
        ]);

        Sanctum::actingAs($userB);
        $this->getJson("/api/expenses/{$expenseA->id}")->assertNotFound();

        Sanctum::actingAs($userA);
        $this->getJson("/api/expenses/{$expenseA->id}")->assertOk();
    }

    public function test_expense_is_saved_as_pending_when_exchange_api_fails_and_flag_is_true(): void
    {
        $user = User::factory()->create();

        Http::fake([
            'open.er-api.com/*' => Http::response([], 500),
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/expenses', [
            'amount' => '120.50',
            'currency' => 'USD',
            'save_as_pending_on_failure' => true,
        ]);

        $response->assertCreated()
            ->assertJsonPath('expense.status', 'pending')
            ->assertJsonPath('expense.amount_brl', null);
    }
}
