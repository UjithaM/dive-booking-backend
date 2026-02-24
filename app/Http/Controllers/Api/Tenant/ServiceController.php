<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreServiceRequest;
use App\Http\Requests\Tenant\UpdateServiceRequest;
use App\Models\Service;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $services = Service::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($services);
    }

    public function store(StoreServiceRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'services', $data['tenant_id']);
        }

        $service = Service::create($data);

        $this->handleMediaUpload($request, $service);

        return response()->json($service->load('media'), 201);
    }

    public function show(Request $request, Service $service)
    {
        if ($service->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($service->load('media'));
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        if ($service->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'services', $request->user()->tenant_id, $service->id);
        }

        $service->update($data);

        $this->handleMediaUpload($request, $service);

        return response()->json($service->load('media'));
    }

    public function destroy(Request $request, Service $service)
    {
        if ($service->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $service->delete();

        return response()->json(null, 204);
    }
}
