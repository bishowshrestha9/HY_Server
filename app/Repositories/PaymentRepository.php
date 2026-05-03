<?php

namespace App\Repositories;

use App\Interfaces\PaymentRepositoryInterface;
use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function create(array $data)
    {
        return Payment::create($data);
    }

    public function findById($id)
    {
        return Payment::find($id);
    }

    public function findByPayoneerIdentifiers($longId, $transactionId)
    {
        return Payment::where('payoneer_long_id', $longId)
            ->orWhere('payoneer_transaction_id', $transactionId)
            ->first();
    }

    public function update($payment, array $data)
    {
        // If checking only generic update, allow passing ID or model
        if (!($payment instanceof Payment)) {
            $payment = $this->findById($payment);
        }

        if ($payment) {
            $payment->update($data);
            return $payment;
        }
        return null;
    }
}
