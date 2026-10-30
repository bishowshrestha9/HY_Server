<?php

namespace App\Services;

use App\Interfaces\InstructorRepositoryInterface;
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
            $instructor->image_url = $instructor->image ? $this->publicAssetUrl($instructor->image) : null;
            return $instructor;
        });

        return $instructors;
    }

    public function createInstructor(array $data, $imageFile = null)
    {
        if ($imageFile) {
            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'instructor_' . time() . '_' . Str::random(10) . '.' . $extension;
            $this->ensurePublicDirectoryExists('instructors');
            $imageFile->move(public_path('instructors'), $filename);
            $data['image'] = 'instructors/' . $filename;
        }

        return $this->instructorRepository->create($data);
    }

    public function getInstructorById($id)
    {
        $instructor = $this->instructorRepository->findById($id);
        if ($instructor) {
            $instructor->image_url = $instructor->image ? $this->publicAssetUrl($instructor->image) : null;
        }
        return $instructor;
    }

    public function updateInstructor($id, array $data, $imageFile = null)
    {
        $instructor = $this->instructorRepository->findById($id);
        if (!$instructor)
            return null;

        if ($imageFile) {
            $this->deletePublicFile($instructor->image);

            $extension = $imageFile->getClientOriginalExtension();
            $filename = 'instructor_' . time() . '_' . Str::random(10) . '.' . $extension;
            $this->ensurePublicDirectoryExists('instructors');
            $imageFile->move(public_path('instructors'), $filename);
            $data['image'] = 'instructors/' . $filename;
        }

        return $this->instructorRepository->update($id, $data);
    }

    public function deleteInstructor($id)
    {
        $instructor = $this->instructorRepository->findById($id);
        if (!$instructor)
            return false;

        $this->deletePublicFile($instructor->image);

        return $this->instructorRepository->delete($id);
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
