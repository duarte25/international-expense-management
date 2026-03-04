<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    /**
     * @param  Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D+/', '', (string) $value) ?? '';

        if (strlen($cpf) !== 11) {
            $fail('CPF invalido.');

            return;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf) === 1) {
            $fail('CPF invalido.');

            return;
        }

        $digits = array_map('intval', str_split($cpf));

        $firstVerifier = $this->calculateVerifier(array_slice($digits, 0, 9), 10);
        $secondVerifier = $this->calculateVerifier(array_slice($digits, 0, 10), 11);

        if ($firstVerifier !== $digits[9] || $secondVerifier !== $digits[10]) {
            $fail('CPF invalido.');
        }
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
}
