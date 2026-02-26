<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $blogPosts = BlogPost::where('tenant_id', $request->tenant_id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with('media')
            ->latest('published_at')
            ->paginate(10);

        return response()->json($blogPosts);
    }

    public function show(Request $request, BlogPost $blogPost)
    {
        if ($blogPost->tenant_id !== $request->tenant_id || $blogPost->status !== 'published' || $blogPost->published_at > now()) {
            abort(404);
        }

        return response()->json($blogPost->load('media'));
    }
}
