<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Http;

class CepService
{
    /**
     * @return array{street:string, neighborhood:string, city:string, state:string}|null
     */
    public function lookup(string $cep): ?array
    {
        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->get("https://viacep.com.br/ws/{$cep}/json/");
        } catch (Throwable) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (! is_array($data) || ($data['erro'] ?? false) === true) {
            return null;
        }

        return [
            'street' => (string) ($data['logradouro'] ?? ''),
            'neighborhood' => (string) ($data['bairro'] ?? ''),
            'city' => (string) ($data['localidade'] ?? ''),
            'state' => (string) ($data['uf'] ?? ''),
        ];
    }
}
