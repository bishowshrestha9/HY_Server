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

    public function getPublishableReviews(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 8);
            $reviews = $this->reviewService->getPublishableReviews($perPage);

            if ($reviews->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No publishable reviews found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Publishable reviews fetched successfully',
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

    public function delete($id)
    {
        try {
            $this->reviewService->deleteReview($id);
            return response()->json(['status' => true, 'message' => 'Reviews deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to delete reviews'], 500);
        }
    }

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

    public function getFourReviews()
    {
        try {
            $reviews = $this->reviewService->getLatestReviews(4);

            if ($reviews->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No reviews found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Reviews fetched successfully',
                'data' => $reviews,
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to fetch reviews'], 500);
        }
    }

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
