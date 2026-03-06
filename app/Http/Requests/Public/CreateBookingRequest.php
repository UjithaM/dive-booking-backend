<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer'                         => ['required', 'array'],
            'customer.first_name'              => ['required', 'string', 'max:255'],
            'customer.last_name'               => ['required', 'string', 'max:255'],
            'customer.email'                   => ['required', 'email', 'max:255'],
            'customer.phone'                   => ['nullable', 'string', 'max:50'],
            'customer.date_of_birth'           => ['nullable', 'date'],
            'customer.nationality'             => ['nullable', 'string', 'max:100'],
            'customer.diving_experience_level' => ['nullable', 'string', 'in:beginner,intermediate,advanced,professional'],

            'centre_id'       => ['required', 'uuid', 'exists:centres,id'],
            'booking_date'    => ['required', 'date'],
            'booking_time'    => ['nullable', 'date_format:H:i'],
            'currency'        => ['nullable', 'string', 'size:3'],
            'special_requests' => ['nullable', 'string'],

            'items'                    => ['required', 'array', 'min:1'],
            'items.*.bookable_type'    => ['required', 'string', 'in:activity,course,service'],
            'items.*.bookable_id'      => ['required', 'uuid'],
            'items.*.quantity'         => ['required', 'integer', 'min:1'],
            'items.*.participant_count' => ['nullable', 'integer', 'min:1'],
            'items.*.scheduled_date'   => ['nullable', 'date'],
            'items.*.scheduled_time'   => ['nullable', 'date_format:H:i'],
            'items.*.notes'            => ['nullable', 'string'],
        ];
    }
}
