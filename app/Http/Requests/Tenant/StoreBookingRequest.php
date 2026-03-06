<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer' => 'required|array',
            'customer.email' => 'required|email|max:255',
            'customer.first_name' => 'required|string|max:255',
            'customer.last_name' => 'required|string|max:255',
            'customer.phone' => 'nullable|string|max:255',
            'customer.date_of_birth' => 'nullable|date',

            'centre_id' => 'required|uuid|exists:centres,id',
            'promotion_id' => 'nullable|uuid|exists:promotions,id',
            'booking_date' => 'required|date',
            'booking_time' => 'nullable|date_format:H:i',
            'number_of_participants' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string',
            'internal_notes' => 'nullable|string',

            // Sub-items
            'items' => 'required|array|min:1',
            'items.*.bookable_type' => 'required|string|in:course,activity,service',
            'items.*.bookable_id' => 'required|uuid',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.scheduled_date' => 'nullable|date',
            'items.*.scheduled_time' => 'nullable|date_format:H:i',
            'items.*.unit_price' => 'numeric|min:0',
            'items.*.notes' => 'nullable|string',

            // Room Bookings
            'room_bookings' => 'nullable|array',
            'room_bookings.*.room_id' => 'required|uuid|exists:rooms,id',
            'room_bookings.*.check_in' => 'required|date',
            'room_bookings.*.check_out' => 'required|date|after:room_bookings.*.check_in',
            'room_bookings.*.guests' => 'required|integer|min:1',
            'room_bookings.*.price_per_night' => 'numeric|min:0',
            'room_bookings.*.special_requests' => 'nullable|string',
        ];
    }
}
