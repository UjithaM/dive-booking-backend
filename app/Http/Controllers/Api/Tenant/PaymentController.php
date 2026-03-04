<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StorePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, Booking $booking)
    {
        if ($booking->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $payments = $booking->payments()->orderBy('created_at', 'desc')->get();

        return response()->json($payments);
    }

    public function store(StorePaymentRequest $request, Booking $booking)
    {
        if ($booking->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (!isset($data['status'])) {
            $data['status'] = 'completed';
        }

        if ($data['status'] === 'completed' && !isset($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $payment = $booking->payments()->create($data);

        return response()->json($payment, 201);
    }
}
