<?php

namespace App\Repositories;

use App\Interfaces\InstructorRepositoryInterface;
use App\Models\Instructor;

class InstructorRepository implements InstructorRepositoryInterface
{
    public function getAll()
    {
        return Instructor::all();
    }

    public function findById($id)
    {
        return Instructor::find($id);
    }

    public function create(array $data)
    {
        return Instructor::create($data);
    }

    public function update($id, array $data)
    {
        $instructor = Instructor::find($id);
        if ($instructor) {
            $instructor->update($data);
            return $instructor;
        }
        return null;
    }

    public function delete($id)
    {
        $instructor = Instructor::find($id);
        if ($instructor) {
            return $instructor->delete();
        }
        return false;
    }
}
