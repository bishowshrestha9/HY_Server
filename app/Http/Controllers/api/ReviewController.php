<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewsRequest;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Exception;

class ReviewController extends Controller
{
    private ReviewService $reviewService;


    

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * @OA\Post(
     *   path="/reviews",
     *   tags={"Reviews"},
     *   summary="Submit a review",
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"name","email","rating","comment"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="email", type="string"),
     *     @OA\Property(property="rating", type="integer"),
     *     @OA\Property(property="comment", type="string")
     *   )),
     *   @OA\Response(response=201, description="Review submitted")
     * )
     */
    public function submitReview(ReviewsRequest $request)
    {
        try {
            $this->reviewService->submitReview($request->all());

            return response()->json(['status' => true, 'message' => 'Review created successfully'], 201);

        } catch (Exception $e) {
            $code = $e->getCode();
            $statusCode = ($code >= 400 && $code < 500) ? $code : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage() ?: 'Failed to create review'], $statusCode);
        }
    }

    /**
     * @OA\Get(
     *   path="/reviews",
     *   tags={"Reviews"},
     *   summary="Get all reviews (admin)",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="List of reviews")
     * )
     */
    public function getReviews(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $reviews = $this->reviewService->getAllReviews($perPage);

            if ($reviews->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No reviews found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Reviews fetched successfully',
                'data' => $reviews->items(), // Items are already transformed in Service? No, transformation logic in Service returns collection of arrays or transformed items?
                // Wait, Controller logic manually loops. Service transforms collection. paginate() returns length aware paginator.
                // In service: $reviews->getCollection()->transform(...) 
                // So $reviews->items() will contain transformed items.
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to fetch reviews'], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/reviews/publishable",
     *   tags={"Reviews"},
     *   summary="Get publishable reviews",
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="List of publishable reviews")
     * )
     */
    public function getPublishableReviews(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 8);
            $reviews = $this->reviewService->getPublishableReviews($perPage);

            return response()->json([
                'status' => true,
                'message' => $reviews->isEmpty() ? 'No publishable reviews found' : 'Publishable reviews fetched successfully',
                'data' => $reviews->items(),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'last_page' => $reviews->lastPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                ]
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to fetch publishable reviews'], 500);
        }
    }

    /**
     * @OA\Delete(
     *   path="/reviews/{id}",
     *   tags={"Reviews"},
     *   summary="Delete review",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Review deleted")
     * )
     */
    public function delete($id)
    {
        try {
            $this->reviewService->deleteReview($id);
            return response()->json(['status' => true, 'message' => 'Reviews deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete reviews'], 500);
        }
    }

    /**
     * @OA\Post(
     *   path="/reviews/{id}/approve",
     *   tags={"Reviews"},
     *   summary="Approve review",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Review approved")
     * )
     */
    public function approveReview($id)
    {
        try {
            $review = $this->reviewService->approveReview($id);

            if (!$review) {
                return response()->json(['status' => false, 'message' => 'Review not found'], 404);
            }

            return response()->json(['status' => true, 'message' => 'Review approved successfully'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to approve review'], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/reviews/latest",
     *   tags={"Reviews"},
     *   summary="Get latest reviews",
     *   @OA\Response(response=200, description="Latest reviews")
     * )
     */
    public function getFourReviews()
    {
        try {
            $reviews = $this->reviewService->getLatestReviews(4);

            return response()->json([
                'status' => true,
                'message' => $reviews->isEmpty() ? 'No reviews found' : 'Reviews fetched successfully',
                'data' => $reviews,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to fetch reviews'], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/reviews/stats",
     *   tags={"Reviews"},
     *   summary="Get positive and negative reviews count",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Response(response=200, description="Review stats")
     * )
     */
    public function getPositiveAndNegativeReviewsCount()
    {
        try {
            $data = $this->reviewService->getReviewsCountStats();
            return response()->json(['status' => true, 'data' => $data]);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to retrieve reviews count'], 500);
        }
    }
}
