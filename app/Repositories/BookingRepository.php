<?php

namespace App\Repositories;

use App\Interfaces\BookingRepositoryInterface;
use App\Models\Booking;

class BookingRepository implements BookingRepositoryInterface
{
    public function findByPaymentId($paymentId)
    {
        return Booking::where('payment_id', $paymentId)->first();
    }

    public function updateStatus($booking, string $status)
    {
        if (!($booking instanceof Booking)) {
            $booking = $this->findById($booking);
        }

        if ($booking) {
            $booking->update(['status' => $status]);
        }
        return $booking;
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function findById($id)
    {
        return Booking::find($id);
    }

    public function count()
    {
        return Booking::count();
    }
}
