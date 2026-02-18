<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreDiveSiteRequest;
use App\Http\Requests\Tenant\UpdateDiveSiteRequest;
use App\Models\DiveSite;
use Illuminate\Http\Request;

class DiveSiteController extends Controller
{
    public function index(Request $request)
    {
        $diveSites = DiveSite::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($diveSites);
    }

    public function store(StoreDiveSiteRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $diveSite = DiveSite::create($data);

        return response()->json($diveSite, 201);
    }

    public function show(Request $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($diveSite);
    }

    public function update(UpdateDiveSiteRequest $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $diveSite->update($request->validated());

        return response()->json($diveSite);
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
