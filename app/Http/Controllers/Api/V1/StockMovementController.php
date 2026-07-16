<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::query()->with(['product', 'user']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        $movements = $query->latest()->paginate(25);

        return StockMovementResource::collection($movements);
    }

    public function store(StoreStockMovementRequest $request)
    {
        $validated = $request->validated();

        $movement = DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

            if ($validated['type'] === 'out' && $product->quantity < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => ["Not enough stock on hand ({$product->quantity} available)."],
                ]);
            }

            $product->quantity += $validated['type'] === 'in'
                ? $validated['quantity']
                : -$validated['quantity'];
            $product->save();

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'note' => $validated['note'] ?? null,
            ]);
        });

        return (new StockMovementResource($movement->load(['product', 'user'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(StockMovement $stockMovement)
    {
        return new StockMovementResource($stockMovement->load(['product', 'user']));
    }

    public function destroy(StockMovement $stockMovement)
    {
        $this->authorize('delete', $stockMovement);

        DB::transaction(function () use ($stockMovement) {
            $product = Product::lockForUpdate()->findOrFail($stockMovement->product_id);

            // Reverse the effect of this movement before removing it, so
            // deleting a mistaken entry doesn't leave stale stock counts.
            $product->quantity += $stockMovement->type === 'in'
                ? -$stockMovement->quantity
                : $stockMovement->quantity;
            $product->save();

            $stockMovement->delete();
        });

        return response()->json(null, 204);
    }
}
