<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreCentreRequest;
use App\Http\Requests\Tenant\UpdateCentreRequest;
use App\Models\Centre;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class CentreController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $centres = Centre::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('name')
            ->paginate(10);

        return response()->json($centres);
    }

    public function store(StoreCentreRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'centres', $data['tenant_id']);
        }

        $centre = Centre::create($data);

        $this->handleMediaUpload($request, $centre);

        return response()->json($centre->load('media'), 201);
    }

    public function show(Request $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($centre->load('media'));
    }

    public function update(UpdateCentreRequest $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'centres', $request->user()->tenant_id, $centre->id);
        }

        $centre->update($data);

        $this->handleMediaUpload($request, $centre);

        return response()->json($centre->load('media'));
    }

    public function destroy(Request $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $centre->delete();

        return response()->json(null, 204);
    }
}
