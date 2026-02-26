<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use Illuminate\Http\Request;

class CentreController extends Controller
{
    public function index(Request $request)
    {
        $centres = Centre::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with(['seasons'])
            ->paginate(10);

        return response()->json($centres);
    }

    public function show(Request $request, Centre $centre)
    {
        if ($centre->tenant_id !== $request->tenant_id || !$centre->is_active) {
            abort(404);
        }

        return response()->json($centre->load('seasons'));
    }
}
