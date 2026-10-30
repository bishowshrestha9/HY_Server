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

    /**
     * @OA\Get(
     *   path="/bookings",
     *   tags={"Bookings"},
     *   summary="Get all bookings",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Response(response=200, description="List of bookings")
     * )
     */
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

    /**
     * @OA\Put(
     *   path="/bookings/{id}/status",
     *   tags={"Bookings"},
     *   summary="Update booking status",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="status", type="string"))),
     *   @OA\Response(response=200, description="Booking status updated")
     * )
     */
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

    /**
     * @OA\Post(
     *   path="/bookings",
     *   tags={"Bookings"},
     *   summary="Create new booking",
     *   @OA\RequestBody(required=true, @OA\JsonContent(
     *     required={"service_id","user_name","email"},
     *     @OA\Property(property="service_id", type="integer"),
     *     @OA\Property(property="user_name", type="string"),
     *     @OA\Property(property="email", type="string")
     *   )),
     *   @OA\Response(response=201, description="Booking created")
     * )
     */
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

    /**
     * @OA\Get(
     *   path="/bookings/total",
     *   tags={"Bookings"},
     *   summary="Get total bookings count",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Response(response=200, description="Total bookings count")
     * )
     */
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

    /**
     * @OA\Get(
     *   path="/bookings/monthly-revenue",
     *   tags={"Bookings"},
     *   summary="Get monthly revenue",
     *   security={{"bearerAuth":{},"cookieAuth":{}}},
     *   @OA\Response(response=200, description="Monthly revenue data")
     * )
     */
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
