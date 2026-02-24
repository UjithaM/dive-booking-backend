<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'centre_id' => ['sometimes', 'required', 'uuid', 'exists:centres,id'],
            'room_type_id' => ['sometimes', 'required', 'uuid', 'exists:room_types,id'],
            'room_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->ignore($this->room->id)->where(function ($query) {
                    $centreId = $this->centre_id ?? $this->room->centre_id;
                    return $query->where('tenant_id', $this->user()->tenant_id)
                        ->where('centre_id', $centreId);
                }),
            ],
            'floor' => ['nullable', 'string', 'max:255'],
            'base_price_per_night' => ['sometimes', 'required', 'numeric', 'min:0'],
            'status' => ['string', 'max:20', Rule::in(['available', 'occupied', 'maintenance', 'out_of_service'])],
            'notes' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
