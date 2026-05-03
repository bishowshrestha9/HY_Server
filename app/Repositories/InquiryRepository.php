<?php

namespace App\Repositories;

use App\Interfaces\InquiryRepositoryInterface;
use App\Models\Inquiry;

class InquiryRepository implements InquiryRepositoryInterface
{
    public function getAllPaginated($perPage)
    {
        return Inquiry::paginate($perPage);
    }

    public function findById($id)
    {
        return Inquiry::find($id);
    }

    public function create(array $data)
    {
        return Inquiry::create($data);
    }

    public function delete($id)
    {
        $inquiry = Inquiry::find($id);
        if ($inquiry) {
            return $inquiry->delete();
        }
        return false;
    }

    public function count()
    {
        return Inquiry::count();
    }
}
