<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreStaffRequest;
use App\Http\Requests\Tenant\UpdateStaffRequest;
use App\Models\Staff;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    use HandlesMediaUpload;

    public function index(Request $request)
    {
        $staff = Staff::with('user', 'media')
            ->where('tenant_id', $request->user()->tenant_id)
            ->paginate(10);

        return response()->json($staff);
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $staff = Staff::create($data);

        $this->handleMediaUpload($request, $staff);

        return response()->json($staff->load('user', 'media'), 201);
    }

    public function show(Request $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($staff->load('user', 'media'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $staff->update($request->validated());

        $this->handleMediaUpload($request, $staff);

        return response()->json($staff->load('user', 'media'));
    }

    public function destroy(Request $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $staff->delete();

        return response()->json(null, 204);
    }
}
