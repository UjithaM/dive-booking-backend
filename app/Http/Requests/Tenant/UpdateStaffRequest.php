<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'sometimes',
                'required',
                'uuid',
                Rule::exists('users', 'id'),
                Rule::unique('staff_profiles')->ignore($this->staff->id)->where(function ($query) {
                    return $query->where('tenant_id', $this->user()->tenant_id);
                }),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'profile_photo_url' => ['nullable', 'string', 'url', 'max:255'],
            'specialties' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }
}
