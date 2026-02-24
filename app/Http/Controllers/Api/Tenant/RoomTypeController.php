<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreRoomTypeRequest;
use App\Http\Requests\Tenant\UpdateRoomTypeRequest;
use App\Models\RoomType;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $roomTypes = RoomType::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($roomTypes);
    }

    public function store(StoreRoomTypeRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'room_types', $data['tenant_id']);
        }

        $roomType = RoomType::create($data);

        $this->handleMediaUpload($request, $roomType);

        return response()->json($roomType->load('media'), 201);
    }

    public function show(Request $request, RoomType $roomType)
    {
        if ($roomType->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($roomType->load('media'));
    }

    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        if ($roomType->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'room_types', $request->user()->tenant_id, $roomType->id);
        }

        $roomType->update($data);

        $this->handleMediaUpload($request, $roomType);

        return response()->json($roomType->load('media'));
    }

    public function destroy(Request $request, RoomType $roomType)
    {
        if ($roomType->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $roomType->delete();

        return response()->json(null, 204);
    }
}
