<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('room_types')->ignore($this->room_type->id)->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'description' => ['nullable', 'string'],
            'max_occupancy' => ['sometimes', 'required', 'integer', 'min:1'],
            'bed_configuration' => ['nullable', 'string', 'max:255'],
            'amenities' => ['nullable', 'array'],
            'image' => ['nullable', 'image', 'max:5120'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer'],
        ];
    }
}
