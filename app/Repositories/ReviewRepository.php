<?php

namespace App\Repositories;

use App\Interfaces\ReviewRepositoryInterface;
use App\Models\Reviews;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function getAll()
    {
        return Reviews::all();
    }

    public function findById($id)
    {
        return Reviews::find($id);
    }

    public function create(array $data)
    {
        return Reviews::create($data);
    }

    public function update($id, array $data)
    {
        $review = Reviews::find($id);
        if ($review) {
            $review->update($data);
            return $review;
        }
        return null;
    }

    public function delete($id)
    {
        $review = Reviews::find($id);
        if ($review) {
            return $review->delete();
        }
        return false;
    }
}
