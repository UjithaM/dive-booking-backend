<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StorePromotionRequest;
use App\Http\Requests\Tenant\UpdatePromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $promotions = Promotion::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('valid_from', 'desc')
            ->paginate(10);

        return response()->json($promotions);
    }

    public function store(StorePromotionRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $promotion = Promotion::create($data);

        return response()->json($promotion, 201);
    }

    public function show(Request $request, Promotion $promotion)
    {
        if ($promotion->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($promotion);
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion)
    {
        if ($promotion->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $promotion->update($request->validated());

        return response()->json($promotion);
    }

    public function destroy(Request $request, Promotion $promotion)
    {
        if ($promotion->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $promotion->delete();

        return response()->json(null, 204);
    }
}
