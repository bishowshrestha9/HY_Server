<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstructorRequest;
use App\Services\InstructorService;
use Exception;

class InstructorController extends Controller
{
    private InstructorService $instructorService;

    public function __construct(InstructorService $instructorService)
    {
        $this->instructorService = $instructorService;
    }

    public function index()
    {
        try {
            $instructors = $this->instructorService->getAllInstructors();

            if ($instructors->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No instructors found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Instructors fetched successfully',
                'data' => $instructors,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch instructors', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch instructors'], 500);
        }
    }

    public function store(InstructorRequest $request)
    {
        try {
            $this->instructorService->createInstructor($request->validated(), $request->file('image'));

            return response()->json(['status' => true, 'message' => 'Instructor created successfully'], 201);

        } catch (Exception $e) {
            \Log::error('Failed to create instructor', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to create instructor'], 500);
        }
    }

    public function show($id)
    {
        try {
            $instructor = $this->instructorService->getInstructorById($id);

            if (!$instructor) {
                return response()->json(['status' => false, 'message' => 'Instructor not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Instructor fetched successfully',
                'data' => $instructor,
            ], 200);

        } catch (Exception $e) {
            \Log::error('Failed to fetch instructor', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to fetch instructor'], 500);
        }
    }

    public function update(InstructorRequest $request, $id)
    {
        try {
            $instructor = $this->instructorService->updateInstructor($id, $request->validated(), $request->file('image'));

            if (!$instructor) {
                return response()->json(['status' => false, 'message' => 'Instructor not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Instructor updated successfully'], 200);

        } catch (Exception $e) {
            \Log::error('Failed to update instructor', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to update instructor'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->instructorService->deleteInstructor($id);

            if (!$deleted) {
                return response()->json(['status' => false, 'message' => 'Instructor not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Instructor deleted successfully'], 200);

        } catch (Exception $e) {
            \Log::error('Failed to delete instructor', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => 'Failed to delete instructor'], 500);
        }
    }
}
