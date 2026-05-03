<?php

namespace App\Interfaces;

interface PaymentRepositoryInterface
{
    public function create(array $data);
    public function findById($id);
    public function findByPayoneerIdentifiers($longId, $transactionId);
    public function update($payment, array $data);
}
