<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreActivityRequest;
use App\Http\Requests\Tenant\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($activities);
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $activity = Activity::create($data);

        return response()->json($activity, 201);
    }

    public function show(Request $request, Activity $activity)
    {
        if ($activity->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($activity);
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        if ($activity->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $activity->update($request->validated());

        return response()->json($activity);
    }

    public function destroy(Request $request, Activity $activity)
    {
        if ($activity->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $activity->delete();

        return response()->json(null, 204);
    }
}
