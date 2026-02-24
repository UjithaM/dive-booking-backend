<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreCentreSeasonRequest;
use App\Http\Requests\Tenant\UpdateCentreSeasonRequest;
use App\Models\Centre;
use App\Models\CentreSeason;
use Illuminate\Http\Request;

class CentreSeasonController extends Controller
{
    public function index(Request $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $seasons = $centre->seasons()->orderBy('start_month')->paginate(10);

        return response()->json($seasons);
    }

    public function store(StoreCentreSeasonRequest $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();
        $data['centre_id'] = $centre->id;

        $season = CentreSeason::create($data);

        return response()->json($season, 201);
    }

    public function show(Request $request, Centre $centre, CentreSeason $season)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($season);
    }

    public function update(UpdateCentreSeasonRequest $request, Centre $centre, CentreSeason $season)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $season->update($request->validated());

        return response()->json($season);
    }

    public function destroy(Request $request, Centre $centre, CentreSeason $season)
    {
        if ($centre->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $season->delete();

        return response()->json(null, 204);
    }
}
