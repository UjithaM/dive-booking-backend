<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $faqs = Faq::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($faqs);
    }

    public function show(Request $request, Faq $faq)
    {
        if ($faq->tenant_id !== $request->tenant_id || !$faq->is_active) {
            abort(404);
        }

        return response()->json($faq);
    }
}
