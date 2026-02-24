<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centre_id' => ['required', 'uuid', 'exists:centres,id'],
            'room_type_id' => ['required', 'uuid', 'exists:room_types,id'],
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id)
                        ->where('centre_id', $this->centre_id);
                }),
            ],
            'floor' => ['nullable', 'string', 'max:255'],
            'base_price_per_night' => ['required', 'numeric', 'min:0'],
            'status' => ['string', 'max:20', Rule::in(['available', 'occupied', 'maintenance', 'out_of_service'])],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
