<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_hours' => ['nullable', 'numeric', 'min:0'],
            'min_age' => ['nullable', 'integer', 'min:0'],
            'includes' => ['nullable', 'array'],
            'main_image' => ['nullable', 'image', 'max:5120'],
            'things_image' => ['nullable', 'array'],
            'things_image.*' => ['image', 'max:5120'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'is_per_person' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer'],
        ];
    }
}
