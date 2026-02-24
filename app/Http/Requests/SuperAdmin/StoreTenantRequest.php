<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tenants', 'slug')],
            'email' => ['required', 'email', 'max:255', Rule::unique('tenants', 'email'), Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'logo_url' => ['nullable', 'string', 'url', 'max:500'],
            'website' => ['nullable', 'string', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
            'currency' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:50', 'timezone:all'],
            'settings' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }
}
