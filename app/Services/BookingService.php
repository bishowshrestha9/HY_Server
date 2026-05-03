<?php

namespace App\Services;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;

class BookingService
{
    private BookingRepositoryInterface $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function getAllBookings()
    {
        $bookings = Booking::with('service')->orderBy('fromDate', 'desc')->get();

        $bookings->transform(function ($booking) {
            return [
                'id' => $booking->id,
                'userName' => $booking->userName,
                'userEmail' => $booking->userEmail,
                'service_name' => $booking->service ? $booking->service->title : null,
                'fromDate' => $booking->fromDate,
                'toDate' => $booking->toDate,
                'time' => $booking->time,
                'status' => $booking->status,
                'participants' => $booking->participants,
                'price' => $booking->price,
                'created_at' => is_string($booking->created_at) ? $booking->created_at : $booking->created_at->toDateTimeString(),
                'updated_at' => is_string($booking->updated_at) ? $booking->updated_at : $booking->updated_at->toDateTimeString(),
            ];
        });

        return $bookings;
    }

    public function createBooking(array $data)
    {
        return $this->bookingRepository->create($data);
    }

    public function updateStatus($id, $status)
    {
        $booking = $this->bookingRepository->findById($id);
        if (!$booking)
            return null;

        $booking->status = $status;
        $booking->save();

        return $booking;
    }

    public function getTotalBookingsCount()
    {
        return $this->bookingRepository->count();
    }

    public function getMonthlyRevenue()
    {
        $currentYear = date('Y');
        $monthlyRevenue = Booking::selectRaw('MONTH(created_at) as month, SUM(price) as total_revenue')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formattedRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $revenueData = $monthlyRevenue->firstWhere('month', $month);
            $formattedRevenue[] = [
                'month' => $month,
                'total_revenue' => $revenueData ? (float) $revenueData->total_revenue : 0.0
            ];
        }

        return $formattedRevenue;
    }
}
