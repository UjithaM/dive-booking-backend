<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|size:3',
            'method' => 'required|string|max:20',
            'status' => 'nullable|string|in:pending,completed,failed',
            'transaction_reference' => 'nullable|string',
            'gateway' => 'nullable|string',
            'gateway_response' => 'nullable|array',
            'paid_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
