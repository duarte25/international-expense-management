<?php

namespace App\Services;

use Throwable;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    public function getRateToBrl(string $currencyCode): ?float
    {
        if ($currencyCode === 'BRL') {
            return 1.0;
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->get("https://open.er-api.com/v6/latest/{$currencyCode}");
        } catch (Throwable) {
            return null;
        }

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();

        if (! is_array($data) || ($data['result'] ?? '') !== 'success') {
            return null;
        }

        $rate = $data['rates']['BRL'] ?? null;

        if (! is_numeric($rate)) {
            return null;
        }

        return (float) $rate;
    }
}
