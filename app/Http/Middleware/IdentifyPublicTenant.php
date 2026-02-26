<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyPublicTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantSlug = $request->header('X-Tenant');

        if (!$tenantSlug) {
            return response()->json([
                'message' => 'X-Tenant header is missing.',
            ], 400);
        }

        $tenant = Tenant::where('slug', $tenantSlug)->first();

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found.',
            ], 404);
        }

        if (!$tenant->is_active) {
            return response()->json([
                'message' => 'Tenant is inactive.',
            ], 403);
        }

        $request->merge(['tenant_id' => $tenant->id]);
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
