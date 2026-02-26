<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($services);
    }

    public function show(Request $request, Service $service)
    {
        if ($service->tenant_id !== $request->tenant_id || !$service->is_active) {
            abort(404);
        }

        return response()->json($service->load('media'));
    }
}
