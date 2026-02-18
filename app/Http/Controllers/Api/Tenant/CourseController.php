<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreCourseRequest;
use App\Http\Requests\Tenant\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('tenant_id', $request->user()->tenant_id)
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($courses);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        $course = Course::create($data);

        return response()->json($course, 201);
    }

    public function show(Request $request, Course $course)
    {
        if ($course->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($course);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        if ($course->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $course->update($request->validated());

        return response()->json($course);
    }

    public function destroy(Request $request, Course $course)
    {
        if ($course->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $course->delete();

        return response()->json(null, 204);
    }
}
