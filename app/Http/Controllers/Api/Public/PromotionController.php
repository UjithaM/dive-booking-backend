<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with(['course', 'roomType']) // Assuming relationships exist
            ->get();

        return response()->json($promotions);
    }

    public function show(Request $request, Promotion $promotion)
    {
        if ($promotion->tenant_id !== $request->tenant_id || !$promotion->is_active) {
            abort(404);
        }

        return response()->json($promotion->load(['course', 'roomType']));
    }
}
