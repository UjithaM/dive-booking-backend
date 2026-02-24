<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreFaqRequest;
use App\Http\Requests\Tenant\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($faqs);
    }

    public function store(StoreFaqRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $faq = Faq::create($data);

        return response()->json($faq, 201);
    }

    public function show(Request $request, Faq $faq)
    {
        if ($faq->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($faq);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        if ($faq->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $faq->update($request->validated());

        return response()->json($faq);
    }

    public function destroy(Request $request, Faq $faq)
    {
        if ($faq->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $faq->delete();

        return response()->json(null, 204);
    }
}
