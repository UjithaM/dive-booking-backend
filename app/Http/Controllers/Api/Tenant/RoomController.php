<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreRoomRequest;
use App\Http\Requests\Tenant\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::where('tenant_id', $request->user()->tenant_id)
            ->with(['centre', 'roomType'])
            ->orderBy('room_number')
            ->paginate(10);

        return response()->json($rooms);
    }

    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $room = Room::create($data);

        return response()->json($room->load(['centre', 'roomType']), 201);
    }

    public function show(Request $request, Room $room)
    {
        if ($room->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($room->load(['centre', 'roomType']));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        if ($room->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $room->update($request->validated());

        return response()->json($room->load(['centre', 'roomType']));
    }

    public function destroy(Request $request, Room $room)
    {
        if ($room->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $room->delete();

        return response()->json(null, 204);
    }
}
