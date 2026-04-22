<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $transactions = auth()->user()->transactions()->latest('date')->latest('id')->get();

        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = auth()->user()->transactions()->create($request->validated());

        return response()->json($transaction, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);

        return response()->json($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTransactionRequest $request, string $id): JsonResponse
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        $transaction->update($request->validated());

        return response()->json($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): HttpResponse
    {
        $transaction = auth()->user()->transactions()->findOrFail($id);
        $transaction->delete();

        return response()->noContent();
    }
}
