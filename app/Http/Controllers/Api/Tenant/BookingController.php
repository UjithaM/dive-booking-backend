<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreBookingRequest;
use App\Http\Requests\Tenant\UpdateBookingStatusRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::where('tenant_id', $request->user()->tenant_id)
            ->with(['customer', 'items.bookable'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($bookings);
    }

    public function store(StoreBookingRequest $request)
    {
        $data = $request->validated();
        $tenantId = $request->user()->tenant_id;

        $totalAmount = 0;

        if (isset($data['items'])) {
            foreach ($data['items'] as &$item) {
                // assume unit_price * quantity for total_price
                $totalPrice = $item['unit_price'] * $item['quantity'];
                $item['total_price'] = $totalPrice;
                $totalAmount += $totalPrice;
            }
        }

        if (isset($data['room_bookings'])) {
            foreach ($data['room_bookings'] as &$roomBooking) {
                $checkIn = \Carbon\Carbon::parse($roomBooking['check_in']);
                $checkOut = \Carbon\Carbon::parse($roomBooking['check_out']);
                $nights = $checkIn->diffInDays($checkOut) ?: 1;

                $totalPrice = $roomBooking['price_per_night'] * $nights;
                $roomBooking['total_price'] = $totalPrice;
                $totalAmount += $totalPrice;
            }
        }

        $customerData = $data['customer'];
        $customer = \App\Models\Customer::firstOrCreate(
            ['email' => $customerData['email'], 'tenant_id' => $tenantId],
            [
                'first_name' => $customerData['first_name'],
                'last_name' => $customerData['last_name'],
                'phone' => $customerData['phone'] ?? null,
                'date_of_birth' => $customerData['date_of_birth'] ?? null,
            ]
        );

        $booking = Booking::create([
            'tenant_id' => $tenantId,
            'booking_reference' => 'BKG-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'centre_id' => $data['centre_id'],
            'promotion_id' => $data['promotion_id'] ?? null,
            'status' => 'pending',
            'booking_date' => $data['booking_date'],
            'booking_time' => $data['booking_time'] ?? null,
            'total_amount' => $totalAmount,
            'discount_amount' => 0,
            'final_amount' => $totalAmount,
            'currency' => 'USD',
            'number_of_participants' => $data['number_of_participants'] ?? 1,
            'special_requests' => $data['special_requests'] ?? null,
            'internal_notes' => $data['internal_notes'] ?? null,
        ]);

        if (isset($data['items'])) {
            $booking->items()->createMany($data['items']);
        }

        if (isset($data['room_bookings'])) {
            $booking->roomBookings()->createMany($data['room_bookings']);
        }

        return response()->json($booking->load(['items', 'roomBookings']), 201);
    }

    public function show(Request $request, Booking $booking)
    {
        if ($booking->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($booking->load(['customer', 'items.bookable', 'roomBookings.room', 'payments']));
    }

    public function updateStatus(UpdateBookingStatusRequest $request, Booking $booking)
    {
        if ($booking->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if ($data['status'] === 'cancelled') {
            $data['cancelled_at'] = now();
        } else {
            $data['cancelled_at'] = null;
        }

        $booking->update($data);

        return response()->json($booking);
    }

    public function destroy(Request $request, Booking $booking)
    {
        if ($booking->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $booking->delete();

        return response()->json(null, 204);
    }
}
