<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Products at or below their reorder level.
     */
    public function lowStock(Request $request)
    {
        $this->authorize('view-reports');

        $products = Product::with('category')
            ->lowStock()
            ->orderBy('quantity')
            ->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * Aggregate stock-in vs stock-out totals over a date range, optionally
     * scoped to a single product.
     */
    public function movementSummary(Request $request)
    {
        $this->authorize('view-reports');

        $query = StockMovement::query();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }

        $totals = (clone $query)
            ->selectRaw("type, COALESCE(SUM(quantity), 0) as total_quantity, COUNT(*) as movement_count")
            ->groupBy('type')
            ->get()
            ->keyBy('type');

        return response()->json([
            'stock_in' => [
                'quantity' => (int) ($totals['in']->total_quantity ?? 0),
                'movements' => (int) ($totals['in']->movement_count ?? 0),
            ],
            'stock_out' => [
                'quantity' => (int) ($totals['out']->total_quantity ?? 0),
                'movements' => (int) ($totals['out']->movement_count ?? 0),
            ],
        ]);
    }
}
