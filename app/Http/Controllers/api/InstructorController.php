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

    /**
     * @OA\Get(
     *   path="/instructors",
     *   tags={"Instructors"},
     *   summary="Get all instructors",
     *   @OA\Response(response=200, description="List of instructors"),
     *   @OA\Response(response=404, description="No instructors found")
     * )
     */
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

    /**
     * @OA\Post(
     *   path="/instructors",
     *   tags={"Instructors"},
     *   summary="Create new instructor",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(
     *     required={"name","email"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string", format="email"),
     *     @OA\Property(property="bio", type="string"),
     *     @OA\Property(property="specialization", type="string"),
     *     @OA\Property(property="image", type="string", format="binary")
     * ))),
     *   @OA\Response(response=201, description="Instructor created"),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
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

    /**
     * @OA\Get(
     *   path="/instructors/{id}",
     *   tags={"Instructors"},
     *   summary="Get instructor by ID",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Instructor details"),
     *   @OA\Response(response=404, description="Instructor not found")
     * )
     */
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

    /**
     * @OA\Post(
     *   path="/instructors/{id}",
     *   tags={"Instructors"},
     *   summary="Update instructor",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\MediaType(mediaType="multipart/form-data", @OA\Schema(
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="bio", type="string"),
     *     @OA\Property(property="image", type="string", format="binary")
     *   ))),
     *   @OA\Response(response=200, description="Instructor updated"),
     *   @OA\Response(response=404, description="Instructor not found")
     * )
     */
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

    /**
     * @OA\Delete(
     *   path="/instructors/{id}",
     *   tags={"Instructors"},
     *   summary="Delete instructor",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Instructor deleted"),
     *   @OA\Response(response=404, description="Instructor not found")
     * )
     */
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
