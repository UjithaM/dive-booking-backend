<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|string|in:pending,confirmed,completed,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled|string|nullable',
        ];
    }
}
