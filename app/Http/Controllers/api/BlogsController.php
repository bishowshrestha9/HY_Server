<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Exception;

class BlogsController extends Controller
{
    private BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $blogs = $this->blogService->getAllBlogs($perPage);

            if ($blogs->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No blogs found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Blogs fetched successfully',
                'data' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch blogs'], 500);
        }
    }

    public function store(BlogRequest $request)
    {
        try {
            $data = $request->only(['title', 'subtitle', 'description', 'excerpt', 'author', 'content', 'conclusion', 'is_active', 'slug']);
            $image = $request->file('image');

            $this->blogService->createBlog($data, $image);

            return response()->json(['status' => true, 'message' => 'Blog created successfully'], 201);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to create blog'], 500);
        }
    }

    public function show($title)
    {
        try {
            $blog = $this->blogService->getBlogByTitle($title);

            if (!$blog) {
                return response()->json(['status' => false, 'message' => 'Blog not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Blog fetched successfully', 'data' => $blog], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch blog'], 500);
        }
    }

    public function update(BlogRequest $request, $id)
    {
        try {
            $data = $request->only(['title', 'subtitle', 'description', 'excerpt', 'author', 'content', 'conclusion', 'is_active', 'slug']);
            $image = $request->file('image');

            $blog = $this->blogService->updateBlog($id, $data, $image);

            if (!$blog) {
                return response()->json(['status' => false, 'message' => 'Blog not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Blog updated successfully'], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to update blog'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->blogService->deleteBlog($id);

            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Blog not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Blog deleted successfully'], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete blog'], 500);
        }
    }

    public function getTotalBlogs()
    {
        $total = $this->blogService->getTotalBlogsCount();
        return response()->json(['status' => true, 'total_blogs' => $total]);
    }
}