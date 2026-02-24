<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('promotions')->ignore($this->promotion->id)->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'discount_type' => ['sometimes', 'required', 'string', 'max:20', Rule::in(['percentage', 'fixed'])],
            'discount_value' => ['sometimes', 'required', 'numeric', 'min:0'],
            'min_booking_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'valid_from' => ['sometimes', 'required', 'date'],
            'valid_until' => ['sometimes', 'required', 'date', 'after:valid_from'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'applicable_to' => ['string', 'max:20', Rule::in(['all', 'courses', 'services', 'activities', 'rooms'])],
            'is_active' => ['boolean'],
        ];
    }
}
