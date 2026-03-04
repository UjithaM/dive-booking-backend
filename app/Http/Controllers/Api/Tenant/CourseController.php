<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreCourseRequest;
use App\Http\Requests\Tenant\UpdateCourseRequest;
use App\Models\Course;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $courses = Course::where('tenant_id', $request->user()->tenant_id)
            ->with('media')
            ->orderBy('sort_order')
            ->paginate(10);

        return response()->json($courses);
    }

    public function store(StoreCourseRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'courses', $data['tenant_id']);
        }

        $course = Course::create($data);

        if ($request->hasFile('main_image')) {
            $course->addMediaFromRequest('main_image')->toMediaCollection('main_image', 'media');
        }

        if ($request->hasFile('things_image')) {
            $files = $request->file('things_image');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $course->addMedia($file)->toMediaCollection('things_image', 'media');
                }
            } else {
                $course->addMediaFromRequest('things_image')->toMediaCollection('things_image', 'media');
            }
        }

        return response()->json($course->load('media'), 201);
    }

    public function show(Request $request, Course $course)
    {
        if ($course->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($course->load('media'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        if ($course->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('name', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], 'courses', $request->user()->tenant_id, $course->id);
        }

        $course->update($data);

        if ($request->hasFile('main_image')) {
            $course->addMediaFromRequest('main_image')->toMediaCollection('main_image', 'media');
        }

        if ($request->hasFile('things_image')) {
            $files = $request->file('things_image');
            if (is_array($files)) {
                foreach ($files as $file) {
                    $course->addMedia($file)->toMediaCollection('things_image', 'media');
                }
            } else {
                $course->addMediaFromRequest('things_image')->toMediaCollection('things_image', 'media');
            }
        }

        return response()->json($course->load('media'));
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
