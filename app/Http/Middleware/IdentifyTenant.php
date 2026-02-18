<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->tenant_id) {
            return response()->json([
                'message' => 'Tenant not found for this user.',
            ], 403);
        }

        $tenant = Tenant::find($user->tenant_id);

        if (!$tenant) {
            return response()->json([
                'message' => 'Tenant not found.',
            ], 403);
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
