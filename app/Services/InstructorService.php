<?php

namespace App\Services;

use App\Interfaces\InstructorRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InstructorService
{
    private InstructorRepositoryInterface $instructorRepository;

    public function __construct(InstructorRepositoryInterface $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function getAllInstructors()
    {
        $instructors = $this->instructorRepository->getAll();

        $instructors->transform(function ($instructor) {
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;
            return $instructor;
        });

        return $instructors;
    }

    public function createInstructor(array $data, $imageFile = null)
    {
        if ($imageFile) {
            $tempPath = $imageFile->store('temp/instructors', 'public');
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'instructor_' . time() . '_' . Str::random(10) . '.' . $extension;
            $permanentPath = 'instructors/' . $filename;
            Storage::disk('public')->move($tempPath, $permanentPath);
            $data['image'] = $permanentPath;
        }

        return $this->instructorRepository->create($data);
    }

    public function getInstructorById($id)
    {
        $instructor = $this->instructorRepository->findById($id);
        if ($instructor) {
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;
        }
        return $instructor;
    }

    public function updateInstructor($id, array $data, $imageFile = null)
    {
        $instructor = $this->instructorRepository->findById($id);
        if (!$instructor)
            return null;

        if ($imageFile) {
            if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
                Storage::disk('public')->delete($instructor->image);
            }

            $tempPath = $imageFile->store('temp/instructors', 'public');
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'instructor_' . time() . '_' . Str::random(10) . '.' . $extension;
            $permanentPath = 'instructors/' . $filename;
            Storage::disk('public')->move($tempPath, $permanentPath);
            $data['image'] = $permanentPath;
        }

        return $this->instructorRepository->update($id, $data);
    }

    public function deleteInstructor($id)
    {
        $instructor = $this->instructorRepository->findById($id);
        if (!$instructor)
            return false;

        if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
            Storage::disk('public')->delete($instructor->image);
        }

        return $this->instructorRepository->delete($id);
    }
}
