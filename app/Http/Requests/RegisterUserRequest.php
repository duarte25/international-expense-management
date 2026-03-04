<?php

namespace App\Http\Requests;

use App\Rules\ValidCpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'cpf' => ['required', 'string', 'size:11', 'regex:/^\d+$/', new ValidCpf(), Rule::unique('users', 'cpf')],
            'cep' => ['required', 'string', 'size:8', 'regex:/^\d+$/'],
            'street' => ['required', 'string', 'max:255'],
            'house_number' => ['required', 'string', 'max:20'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'size:2'],
            'complement' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute e obrigatorio.',
            'email' => 'Informe um e-mail valido.',
            'max' => 'O campo :attribute nao pode ter mais de :max caracteres.',
            'min' => 'O campo :attribute deve ter pelo menos :min caracteres.',
            'confirmed' => 'A confirmacao de senha nao confere.',
            'size' => 'O campo :attribute deve ter :size caracteres.',
            'regex' => 'O campo :attribute possui formato invalido.',
            'unique' => 'Este :attribute ja esta cadastrado.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'e-mail',
            'password' => 'senha',
            'password_confirmation' => 'confirmacao de senha',
            'cpf' => 'cpf',
            'cep' => 'cep',
            'street' => 'rua',
            'house_number' => 'numero',
            'neighborhood' => 'bairro',
            'city' => 'cidade',
            'state' => 'estado',
            'complement' => 'complemento',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            // Allow common masks, but keep letters/symbols to fail validation.
            'cpf' => str_replace(['.', '-', ' '], '', (string) $this->input('cpf')),
            'cep' => str_replace(['-', ' '], '', (string) $this->input('cep')),
            'street' => trim((string) $this->input('street')),
            'house_number' => trim((string) $this->input('house_number')),
            'neighborhood' => trim((string) $this->input('neighborhood')),
            'city' => trim((string) $this->input('city')),
            'state' => strtoupper(trim((string) $this->input('state'))),
            'complement' => trim((string) $this->input('complement')),
        ]);
    }
}
