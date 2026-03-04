<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Expense;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $expenses = $request->user()
            ->expenses()
            ->latest()
            ->paginate(20);

        return response()->json($expenses);
    }

    public function store(StoreExpenseRequest $request, ExchangeRateService $exchangeRateService): JsonResponse
    {
        $rate = $exchangeRateService->getRateToBrl($request->validated('currency'));

        $saveAsPending = $request->validated('save_as_pending_on_failure', false);

        if ($rate === null && ! $saveAsPending) {
            return response()->json([
                'message' => 'Nao foi possivel consultar a cotacao agora. Tente novamente ou salve como pendente.',
            ], 422);
        }

        $amount = (float) $request->validated('amount');
        $converted = $rate === null ? null : round($amount * $rate, 2);

        /** @var \App\Models\Expense $expense */
        $expense = $request->user()->expenses()->create([
            'amount_original' => $amount,
            'currency_code' => $request->validated('currency'),
            'exchange_rate' => $rate,
            'amount_brl' => $converted,
            'status' => $rate === null ? 'pending' : 'converted',
            'api_error' => $rate === null ? 'Falha ao consultar API de cambio.' : null,
            'converted_at' => $rate === null ? null : now(),
        ]);

        return response()->json([
            'message' => $rate === null ? 'Despesa salva como pendente.' : 'Despesa salva com conversao em BRL.',
            'expense' => $expense,
        ], 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $expense = $this->findOwnedExpenseOrFail($request, $id);

        return response()->json($expense);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $expense = $this->findOwnedExpenseOrFail($request, $id);
        $expense->delete();

        return response()->json([
            'message' => 'Despesa removida com sucesso.',
        ]);
    }

    private function findOwnedExpenseOrFail(Request $request, int $id): Expense
    {
        return $request->user()
            ->expenses()
            ->whereKey($id)
            ->firstOrFail();
    }
}
