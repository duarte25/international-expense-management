<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'decimal:0,2', 'gt:0'],
            'currency' => ['required', 'string', 'size:3', 'regex:/^[A-Z]{3}$/'],
            'save_as_pending_on_failure' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $currency = strtoupper((string) $this->input('currency'));

        $this->merge([
            'currency' => $currency,
            'save_as_pending_on_failure' => (bool) $this->boolean('save_as_pending_on_failure'),
        ]);
    }
}
