<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bookable_type' => ['required', 'string', 'in:activity,course,service,room'],
            'bookable_id' => ['required', 'string'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'participant_count' => ['sometimes', 'integer', 'min:1'],
            'scheduled_date' => ['sometimes', 'nullable', 'date'],
            'scheduled_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
