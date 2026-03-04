<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cpf = $this->generateValidCpf();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'cpf' => $cpf,
            'cep' => '01001000',
            'street' => 'Praca da Se',
            'neighborhood' => 'Se',
            'city' => 'Sao Paulo',
            'state' => 'SP',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    private function generateValidCpf(): string
    {
        $base = str_pad((string) fake()->unique()->numberBetween(100000000, 999999999), 9, '0', STR_PAD_LEFT);
        $digits = array_map('intval', str_split($base));

        $firstVerifier = $this->calculateVerifier($digits, 10);
        $digits[] = $firstVerifier;
        $secondVerifier = $this->calculateVerifier($digits, 11);

        return $base.$firstVerifier.$secondVerifier;
    }

    /**
     * @param  list<int>  $digits
     */
    private function calculateVerifier(array $digits, int $weightStart): int
    {
        $sum = 0;
        $weight = $weightStart;

        foreach ($digits as $digit) {
            $sum += $digit * $weight;
            $weight--;
        }

        $remainder = ($sum * 10) % 11;

        return $remainder === 10 ? 0 : $remainder;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
