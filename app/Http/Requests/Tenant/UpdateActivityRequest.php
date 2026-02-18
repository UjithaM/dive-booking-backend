<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
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
                'required',
                'string',
                'max:255',
                Rule::unique('activities')->ignore($this->activity->id)->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_hours' => ['nullable', 'numeric', 'min:0'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'includes' => ['nullable', 'array'],
            'hero_image_url' => ['nullable', 'string', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer'],
        ];
    }
}
