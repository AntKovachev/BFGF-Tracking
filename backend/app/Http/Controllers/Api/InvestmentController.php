<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class InvestmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $investments = auth()->user()->investments()->latest('id')->get()
            ->map(fn ($investment) => [
                ...$investment->toArray(),
                'profit_loss' => round((float) $investment->current_value - (float) $investment->amount_invested, 2),
            ]);

        return response()->json($investments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvestmentRequest $request): JsonResponse
    {
        $investment = auth()->user()->investments()->create($request->validated());

        return response()->json([
            ...$investment->toArray(),
            'profit_loss' => round((float) $investment->current_value - (float) $investment->amount_invested, 2),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $investment = auth()->user()->investments()->findOrFail($id);

        return response()->json([
            ...$investment->toArray(),
            'profit_loss' => round((float) $investment->current_value - (float) $investment->amount_invested, 2),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInvestmentRequest $request, string $id): JsonResponse
    {
        $investment = auth()->user()->investments()->findOrFail($id);
        $investment->update($request->validated());

        return response()->json([
            ...$investment->toArray(),
            'profit_loss' => round((float) $investment->current_value - (float) $investment->amount_invested, 2),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): HttpResponse
    {
        $investment = auth()->user()->investments()->findOrFail($id);
        $investment->delete();

        return response()->noContent();
    }
}
