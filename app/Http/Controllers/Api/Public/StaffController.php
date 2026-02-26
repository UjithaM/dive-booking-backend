<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $staffMembers = Staff::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with('media')
            ->paginate(10);

        return response()->json($staffMembers);
    }

    public function show(Request $request, Staff $staff)
    {
        if ($staff->tenant_id !== $request->tenant_id || !$staff->is_active) {
            abort(404);
        }

        return response()->json($staff->load('media'));
    }
}
