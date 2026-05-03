<?php

namespace App\Interfaces;

interface BookingRepositoryInterface
{
    public function findByPaymentId($paymentId);
    public function updateStatus($booking, string $status);
    public function create(array $data);
    public function findById($id);
    public function count();
}
