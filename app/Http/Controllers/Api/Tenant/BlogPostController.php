<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreBlogPostRequest;
use App\Http\Requests\Tenant\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Traits\GeneratesSlug;
use App\Traits\HandlesMediaUpload;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    use GeneratesSlug, HandlesMediaUpload;

    public function index(Request $request)
    {
        $blogPosts = BlogPost::where('tenant_id', $request->user()->tenant_id)
            ->with('author', 'media')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($blogPosts);
    }

    public function store(StoreBlogPostRequest $request)
    {
        $data = $request->validated();
        $data['tenant_id'] = $request->user()->tenant_id;
        $data['author_id'] = $request->user()->id;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], 'blog_posts', $data['tenant_id']);
        }

        $blogPost = BlogPost::create($data);

        $this->handleMediaUpload($request, $blogPost);

        return response()->json($blogPost->load('author', 'media'), 201);
    }

    public function show(Request $request, BlogPost $blogPost)
    {
        if ($blogPost->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        return response()->json($blogPost->load('author', 'media'));
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost)
    {
        if ($blogPost->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $data = $request->validated();

        if (array_key_exists('title', $data) && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], 'blog_posts', $request->user()->tenant_id, $blogPost->id);
        }

        $blogPost->update($data);

        $this->handleMediaUpload($request, $blogPost);

        return response()->json($blogPost->load('author', 'media'));
    }

    public function destroy(Request $request, BlogPost $blogPost)
    {
        if ($blogPost->tenant_id !== $request->user()->tenant_id) {
            abort(403);
        }

        $blogPost->delete();

        return response()->json(null, 204);
    }
}
