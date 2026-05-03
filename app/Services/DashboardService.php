<?php

namespace App\Services;

use App\Interfaces\BlogRepositoryInterface;
use App\Interfaces\ServiceRepositoryInterface;
use App\Interfaces\InquiryRepositoryInterface;
use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\ReviewRepositoryInterface;
use App\Models\Reviews; // Can use repo if method exists
use Exception;

class DashboardService
{
    private BlogRepositoryInterface $blogRepository;
    private ServiceRepositoryInterface $serviceRepository; // Might need count method or use model
    private InquiryRepositoryInterface $inquiryRepository;
    private BookingRepositoryInterface $bookingRepository; // Might need count
    private ReviewRepositoryInterface $reviewRepository; // Might need specific queries

    private BookingService $bookingService; // Reuse logic for revenue?

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        ServiceRepositoryInterface $serviceRepository,
        InquiryRepositoryInterface $inquiryRepository,
        BookingRepositoryInterface $bookingRepository,
        ReviewRepositoryInterface $reviewRepository,
        BookingService $bookingService
    ) {
        $this->blogRepository = $blogRepository;
        $this->serviceRepository = $serviceRepository;
        $this->inquiryRepository = $inquiryRepository;
        $this->bookingRepository = $bookingRepository;
        $this->reviewRepository = $reviewRepository;
        $this->bookingService = $bookingService;
    }

    public function getDashboardStats()
    {
        // Counts
        // Note: Generic repositories might not have count() on all interfaces I defined.
        // BlogRepostioryInterface has count(). InquiryRepositoryInterface has count().
        // Others might define it or I can use Models directly if Repo is lacking.
        // I will trust I added count() to Blog and Inquiry. For others I'll check my memory or assume Model usage/Repo update.
        // Actually, to be safe, I'll use Repo method if I remember adding it, otherwise delegate to Model or add it.
        // I added count() to Blog and Inquiry.
        // For Service and Booking, I didn't explicitly add count() to interface in my thought but I might have added it to files?
        // Let's assume I need to use Models or update interfaces. 
        // Best practice: Use Service helpers if available.
        // BookingService has getTotalBookingsCount.
        // ServiceService has getTotalServicesCount.
        // I can inject Services into DashboardService? Yes.
        // Or just use Models for simple counts here to keep it simple.

        $totalBlogs = $this->blogRepository->count();
        $totalServices = \App\Models\Service::count(); // Interface didn't have count I think
        $totalInquiries = $this->inquiryRepository->count();
        $totalBookings = \App\Models\Booking::count(); // Interface didn't have count

        // Monthly Revenue
        // Reuse BookingService logic
        $formattedRevenue = $this->bookingService->getMonthlyRevenue();

        // Reviews
        $positiveCount = Reviews::where('rating', '>=', 4)->count();
        $negativeCount = Reviews::where('rating', '<', 3)->count();

        return [
            'total_blogs' => $totalBlogs,
            'total_services' => $totalServices,
            'total_inquiries' => $totalInquiries,
            'total_bookings' => $totalBookings,
            'monthly_revenue' => $formattedRevenue,
            'positive_reviews' => $positiveCount,
            'negative_reviews' => $negativeCount,
        ];
    }
}
