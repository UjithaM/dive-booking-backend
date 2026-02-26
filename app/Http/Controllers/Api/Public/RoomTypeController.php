<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index(Request $request)
    {
        $roomTypes = RoomType::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($roomTypes);
    }

    public function show(Request $request, RoomType $roomType)
    {
        if ($roomType->tenant_id !== $request->tenant_id || !$roomType->is_active) {
            abort(404);
        }

        return response()->json($roomType->load('media'));
    }
}
