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
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses')->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'min_age' => ['nullable', 'integer', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'includes' => ['nullable', 'array'],
            'hero_image_url' => ['nullable', 'string', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer'],
        ];
    }
}
