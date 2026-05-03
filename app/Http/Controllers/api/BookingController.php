<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Exception;

class BookingController extends Controller
{
    private BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index()
    {
        try {
            $bookings = $this->bookingService->getAllBookings();

            if ($bookings->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'No bookings found'], 404);
            }

            return response()->json(['status' => true, 'data' => $bookings]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to retrieve bookings'], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:confirmed,pending,cancelled',
            ]);

            $booking = $this->bookingService->updateStatus($id, $request->input('status'));

            if (!$booking) {
                return response()->json(['status' => false, 'message' => 'Booking not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Booking status updated successfully',
                'data' => $booking
            ]);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to update booking status'], 500);
        }
    }

    public function store(BookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking($request->validated());
            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully',
                'data' => $booking
            ], 201);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to create booking'], 500);
        }
    }

    public function getTotalBookings()
    {
        try {
            $total = $this->bookingService->getTotalBookingsCount();
            return response()->json(['status' => true, 'data' => ['total_bookings' => $total]]);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to retrieve total bookings'], 500);
        }
    }

    public function getMonthlyRevenue()
    {
        try {
            $data = $this->bookingService->getMonthlyRevenue();
            return response()->json(['status' => true, 'data' => $data]);
        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to retrieve monthly revenue'], 500);
        }
    }
}
