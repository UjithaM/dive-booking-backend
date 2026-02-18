<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tenantId = $this->route('tenant');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('tenants', 'slug')->ignore($tenantId)],
            'email' => ['sometimes', 'email', 'max:255', Rule::unique('tenants', 'email')->ignore($tenantId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'logo_url' => ['nullable', 'string', 'url', 'max:500'],
            'website' => ['nullable', 'string', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
            'currency' => ['nullable', 'string', 'max:10'],
            'timezone' => ['nullable', 'string', 'max:50', 'timezone:all'],
            'settings' => ['nullable', 'array'],
        ];
    }
}
