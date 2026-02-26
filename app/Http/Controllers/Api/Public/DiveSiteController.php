<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\DiveSite;
use Illuminate\Http\Request;

class DiveSiteController extends Controller
{
    public function index(Request $request)
    {
        $diveSites = DiveSite::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with('media')
            ->paginate(10);

        return response()->json($diveSites);
    }

    public function show(Request $request, DiveSite $diveSite)
    {
        if ($diveSite->tenant_id !== $request->tenant_id || !$diveSite->is_active) {
            abort(404);
        }

        return response()->json($diveSite->load('media'));
    }
}
