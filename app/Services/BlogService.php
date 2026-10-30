<?php

namespace App\Services;

use App\Interfaces\BlogRepositoryInterface;
use Illuminate\Support\Str;

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
            $blog->image_url = $blog->image ? $this->publicAssetUrl($blog->image) : null;
            return $blog;
        });

        return $blogs;
    }

    public function createBlog(array $data, $imageFile = null)
    {
        // Handle image upload
        if ($imageFile) {
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'blog_' . time() . '_' . Str::random(10) . '.' . $extension;
            $this->ensurePublicDirectoryExists('blogs');
            $imageFile->move(public_path('blogs'), $filename);
            $data['image'] = 'blogs/' . $filename;
        }

        return $this->blogRepository->create($data);
    }

    public function getBlogByTitle(string $title)
    {
        // $title here acts as a slug or title to search
        $blog = $this->blogRepository->findBySlug($title);

        if ($blog) {
            $blog->image_url = $blog->image ? $this->publicAssetUrl($blog->image) : null;
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
            $this->deletePublicFile($blog->image);

            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'blog_' . time() . '_' . Str::random(10) . '.' . $extension;
            $this->ensurePublicDirectoryExists('blogs');
            $imageFile->move(public_path('blogs'), $filename);
            $data['image'] = 'blogs/' . $filename;
        }

        return $this->blogRepository->update($id, $data);
    }

    public function deleteBlog($id)
    {
        $blog = $this->blogRepository->findById($id);

        if ($blog) {
            // Delete image
            $this->deletePublicFile($blog->image);
            return $this->blogRepository->delete($id);
        }

        return false;
    }

    public function getTotalBlogsCount()
    {
        return $this->blogRepository->count();
    }

    private function publicAssetUrl(string $path): string
    {
        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, strlen('storage/'));
        }

        $publicPath = public_path($cleanPath);

        if (!file_exists($publicPath)) {
            $storagePath = storage_path('app/public/' . $cleanPath);

            if (file_exists($storagePath)) {
                $this->ensurePublicDirectoryExists(dirname($cleanPath));
                @copy($storagePath, $publicPath);
            }
        }

        return asset($cleanPath);
    }

    private function deletePublicFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        $cleanPath = ltrim($path, '/');

        if (str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, strlen('storage/'));
        }

        $publicPath = public_path($cleanPath);
        $storagePath = storage_path('app/public/' . $cleanPath);

        if (file_exists($publicPath)) {
            @unlink($publicPath);
        }

        if (file_exists($storagePath)) {
            @unlink($storagePath);
        }
    }

    private function ensurePublicDirectoryExists(string $directory): void
    {
        if ($directory === '.' || $directory === '') {
            return;
        }

        $path = public_path($directory);

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }
}
