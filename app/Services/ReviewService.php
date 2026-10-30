<?php

namespace App\Services;

use App\Interfaces\ReviewRepositoryInterface;
use App\Models\Reviews;
use App\Models\Lead;
use Exception;

class ReviewService
{
    private ReviewRepositoryInterface $reviewRepository;

    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function submitReview(array $data)
    {
        // Check for existing review from this email in the last hour
        $recentReview = Reviews::where('email', $data['email'])
            ->where('created_at', '>=', now()->subHour())
            ->first();

        if ($recentReview) {
            throw new Exception('You can only submit one review per hour with this email address.', 429);
        }

        // Always force status to false — reviews must be explicitly approved by admin
        $data['status'] = false;

        $review = $this->reviewRepository->create($data);

        // Store review as a lead
        Lead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['review'],
            'source' => 'review',
            'status' => 'pending',
            'metadata' => [
                'review_id' => $review->id,
                'rating' => $data['rating'],
                'review_text' => $data['review'],
                'status' => false, // new reviews are always pending
            ],
        ]);

        return $review;
    }

    public function getAllReviews($perPage = 10)
    {
        $reviews = Reviews::with('service')->paginate($perPage);

        $reviews->getCollection()->transform(function ($review) {
            return $this->transformReview($review);
        });

        return $reviews;
    }

    public function getPublishableReviews($perPage = 8)
    {
        $reviews = Reviews::with('service')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $reviews->getCollection()->transform(function ($review) {
            return $this->transformReview($review, true);
        });

        return $reviews;
    }

    public function deleteReview($id)
    {
        return $this->reviewRepository->delete($id);
    }

    public function approveReview($id)
    {
        $review = $this->reviewRepository->update($id, ['status' => true]);

        if ($review) {
            // Also update any corresponding Lead status
            \App\Models\Lead::where('source', 'review')
                ->where('metadata->review_id', $review->id)
                ->update([
                    'status' => 'converted',
                    'metadata->status' => true,
                ]);
        }

        return $review;
    }

    public function getLatestReviews($limit = 4)
    {
        $reviews = Reviews::with('service')
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        $reviews->transform(function ($review) {
            return $this->transformReview($review, true);
        });

        return $reviews;
    }

    public function getReviewsCountStats()
    {
        $positiveCount = Reviews::where('rating', '>=', 4)->count();
        $negativeCount = Reviews::where('rating', '<', 3)->count();

        return [
            'positive_reviews' => $positiveCount,
            'negative_reviews' => $negativeCount
        ];
    }

    private function transformReview($review, $simple = false)
    {
        $data = [
            'id' => $review->id,
            'name' => $review->name,
            'email' => $review->email,
            'review' => $review->review,
            'rating' => $review->rating,
            'service' => $review->service ? $review->service->title : null,
            'status' => (bool) $review->status,
            'created_at' => is_string($review->created_at) ? $review->created_at : $review->created_at->toDateString(), // Ensure generic format
        ];

        return $data;
    }
}
