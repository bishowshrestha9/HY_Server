<?php

namespace App\Services;

use App\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class BlogService
{
    private BlogRepositoryInterface $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function getAllBlogs($perPage = 10)
    {
        $blogs = $this->blogRepository->getAllPaginated($perPage);

        // Transform collection to add image_url
        $blogs->getCollection()->transform(function ($blog) {
            $blog->image_url = $blog->image ? url('storage/' . $blog->image) : null;
            return $blog;
        });

        return $blogs;
    }

    public function createBlog(array $data, $imageFile = null)
    {
        // Handle image upload
        if ($imageFile) {
            $tempPath = $imageFile->store('temp/blogs', 'public');
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'blog_' . time() . '_' . Str::random(10) . '.' . $extension;
            $permanentPath = 'blogs/' . $filename;
            Storage::disk('public')->move($tempPath, $permanentPath);
            $data['image'] = $permanentPath;
        }

        return $this->blogRepository->create($data);
    }

    public function getBlogByTitle(string $title)
    {
        // $title here acts as a slug or title to search
        $blog = $this->blogRepository->findBySlug($title);

        if ($blog) {
            $blog->image_url = $blog->image ? url('storage/' . $blog->image) : null;
        }

        return $blog;
    }

    public function updateBlog($id, array $data, $imageFile = null)
    {
        $blog = $this->blogRepository->findById($id);

        if (!$blog) {
            return null;
        }

        if ($imageFile) {
            // Delete old image
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }

            $tempPath = $imageFile->store('temp/blogs', 'public');
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'blog_' . time() . '_' . Str::random(10) . '.' . $extension;
            $permanentPath = 'blogs/' . $filename;
            Storage::disk('public')->move($tempPath, $permanentPath);
            $data['image'] = $permanentPath;
        }

        return $this->blogRepository->update($id, $data);
    }

    public function deleteBlog($id)
    {
        $blog = $this->blogRepository->findById($id);

        if ($blog) {
            // Delete image
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            return $this->blogRepository->delete($id);
        }

        return false;
    }

    public function getTotalBlogsCount()
    {
        return $this->blogRepository->count();
    }
}
