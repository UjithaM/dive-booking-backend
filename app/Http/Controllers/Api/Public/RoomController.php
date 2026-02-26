<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::whereHas('roomType', function ($query) use ($request) {
            $query->where('tenant_id', $request->tenant_id)
                ->where('is_active', true);
        })->where('is_active', true)->with('roomType')->paginate(10);

        return response()->json($rooms);
    }

    public function show(Request $request, Room $room)
    {
        $room->load('roomType');

        if ($room->roomType->tenant_id !== $request->tenant_id || !$room->is_active || !$room->roomType->is_active) {
            abort(404);
        }

        return response()->json($room);
    }
}
