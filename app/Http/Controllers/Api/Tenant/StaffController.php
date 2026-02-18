<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreStaffRequest;
use App\Http\Requests\Tenant\UpdateStaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staff = Staff::with('user')
            ->where('tenant_id', $request->user()->tenant_id)
            ->paginate(10);

        return response()->json($staff);
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $staff = Staff::create($data);

        return response()->json($staff, 201);
    }

    public function show(Request $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($staff->load('user'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $staff->update($request->validated());

        return response()->json($staff);
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
