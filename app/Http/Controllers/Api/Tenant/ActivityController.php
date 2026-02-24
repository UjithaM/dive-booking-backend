<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreActivityRequest;
use App\Http\Requests\Tenant\UpdateActivityRequest;
use App\Models\Activity;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $activities = Activity::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($activities);
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'activities', $data['tenant_id']);
        }

        $activity = Activity::create($data);

        $this->handleMediaUpload($request, $activity);

        return response()->json($activity->load('media'), 201);
    }

    public function show(Request $request, Activity $activity)
    {
        if ($activity->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($activity->load('media'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        if ($activity->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'activities', $request->user()->tenant_id, $activity->id);
        }

        $activity->update($data);

        $this->handleMediaUpload($request, $activity);

        return response()->json($activity->load('media'));
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
