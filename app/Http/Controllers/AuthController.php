<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\CepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request, CepService $cepService): JsonResponse
    {
        $address = $cepService->lookup($request->validated('cep'));

        if (
            $address === null
            || $address['street'] === ''
            || $address['neighborhood'] === ''
            || $address['city'] === ''
            || $address['state'] === ''
        ) {
            throw ValidationException::withMessages([
                'cep' => 'CEP invalido ou inexistente.',
            ]);
        }

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'cpf' => $request->validated('cpf'),
            'cep' => $request->validated('cep'),
            'street' => $address['street'],
            'house_number' => $request->validated('house_number'),
            'neighborhood' => $address['neighborhood'],
            'complement' => trim((string) ($request->validated('complement') ?? '')) !== ''
                ? $request->validated('complement')
                : $address['complement'],
            'city' => $address['city'],
            'state' => $address['state'],
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario cadastrado com sucesso.',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais invalidas.',
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ]);
    }
}
