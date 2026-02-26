<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('tenant_id', $request->tenant_id)
            ->where('is_active', true)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($courses);
    }

    public function show(Request $request, Course $course)
    {
        if ($course->tenant_id !== $request->tenant_id || !$course->is_active) {
            abort(404);
        }

        return response()->json($course->load('media'));
    }
}
