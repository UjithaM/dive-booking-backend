<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreTenantRequest;
use App\Http\Requests\SuperAdmin\UpdateTenantRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Tenant::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('slug', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($request->query('include_trashed') === 'true') {
            $query->withTrashed();
        }

        $tenants = $query->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 15));

        return response()->json($tenants);
    }

    public function store(StoreTenantRequest $request): JsonResponse
    {
        $data = array_filter($request->validated(), fn($value) => !is_null($value));

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);

            $originalSlug = $data['slug'];
            $counter = 1;
            while (Tenant::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter++;
            }
        }

        $tenant = Tenant::create($data);

        return response()->json([
            'message' => 'Tenant created successfully.',
            'data' => $tenant,
        ], 201);
    }

    public function show(Tenant $tenant): JsonResponse
    {
        return response()->json([
            'data' => $tenant,
        ]);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $data = array_filter($request->validated(), fn($value) => !is_null($value));
        $tenant->update($data);

        return response()->json([
            'message' => 'Tenant updated successfully.',
            'data' => $tenant->fresh(),
        ]);
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        $tenant->delete();

        return response()->json([
            'message' => 'Tenant deleted successfully.',
        ]);
    }

    public function restore(string $id): JsonResponse
    {
        $tenant = Tenant::withTrashed()->findOrFail($id);
        $tenant->restore();

        return response()->json([
            'message' => 'Tenant restored successfully.',
            'data' => $tenant,
        ]);
    }

    public function toggleStatus(Tenant $tenant): JsonResponse
    {
        $tenant->update(['is_active' => !$tenant->is_active]);

        return response()->json([
            'message' => $tenant->is_active ? 'Tenant activated.' : 'Tenant deactivated.',
            'data' => $tenant,
        ]);
    }
}
