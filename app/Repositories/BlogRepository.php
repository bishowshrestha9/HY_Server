<?php

namespace App\Repositories;

use App\Interfaces\BlogRepositoryInterface;
use App\Models\Blogs;

class BlogRepository implements BlogRepositoryInterface
{
    public function getAllPaginated($perPage)
    {
        return Blogs::paginate($perPage);
    }

    public function findById($id)
    {
        return Blogs::find($id);
    }

    public function findBySlug($slug)
    {
        // Matches logic in controller: LOWER(REPLACE(title, " ", "-")) = slug
        // But better is to just store slug or check checks.
        // Controller logic was: whereRaw('LOWER(REPLACE(title, " ", "-")) = ?', [strtolower($title)])
        // The argument passed to show($title) is likely a slug or title behaving as slug.
        return Blogs::whereRaw('LOWER(REPLACE(title, " ", "-")) = ?', [strtolower($slug)])->first();
    }

    public function create(array $data)
    {
        return Blogs::create($data);
    }

    public function update($id, array $data)
    {
        $blog = Blogs::find($id);
        if ($blog) {
            $blog->update($data);
            return $blog;
        }
        return null;
    }

    public function delete($id)
    {
        $blog = Blogs::find($id);
        if ($blog) {
            return $blog->delete();
        }
        return false;
    }

    public function count()
    {
        return Blogs::count();
    }
}
