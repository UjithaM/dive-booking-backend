<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::where('tenant_id', $request->user()?->tenant_id ?? $request->tenant_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($activities);
    }

    public function show(Request $request, Activity $activity)
    {
        if ($activity->tenant_id !== ($request->user()?->tenant_id ?? $request->tenant_id) || !$activity->is_active) {
            abort(404);
        }

        return response()->json($activity);
    }
}
