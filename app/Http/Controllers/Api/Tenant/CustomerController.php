<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreCustomerRequest;
use App\Http\Requests\Tenant\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(10);

        return response()->json($customers);
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $customer = Customer::create($data);

        return response()->json($customer, 201);
    }

    public function show(Request $request, Customer $customer)
    {
        if ($customer->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($customer);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        if ($customer->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $customer->update($request->validated());

        return response()->json($customer);
    }

    public function destroy(Request $request, Customer $customer)
    {
        if ($customer->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $customer->delete();

        return response()->json(null, 204);
    }
}
