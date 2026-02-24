<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreDiveSiteRequest;
use App\Http\Requests\Tenant\UpdateDiveSiteRequest;
use App\Models\DiveSite;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class DiveSiteController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $diveSites = DiveSite::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($diveSites);
    }

    public function store(StoreDiveSiteRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'dive_sites', $data['tenant_id']);
        }

        $diveSite = DiveSite::create($data);

        $this->handleMediaUpload($request, $diveSite);

        return response()->json($diveSite->load('media'), 201);
    }

    public function show(Request $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($diveSite->load('media'));
    }

    public function update(UpdateDiveSiteRequest $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'dive_sites', $request->user()->tenant_id, $diveSite->id);
        }

        $diveSite->update($data);

        $this->handleMediaUpload($request, $diveSite);

        return response()->json($diveSite->load('media'));
    }

    public function destroy(Request $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $diveSite->delete();

        return response()->json(null, 204);
    }
}
