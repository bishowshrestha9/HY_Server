<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'invoice_number',
        'description',
        'amount',
        'currency',
        'status',
        'paid_at',
        'failure_message',
        'payment_intent_id',
        'client_secret',
        'payment_gateway',
        'payoneer_long_id',
        'payoneer_transaction_id',
        'payoneer_redirect_url',
        'isAdvancePayment',
        'advancePercentage',
        'fullAmount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'advancePercentage' => 'decimal:2',
        'fullAmount' => 'decimal:2',
        'isAdvancePayment' => 'boolean',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Allowed currencies (DB enum values)
    public const CURRENCIES = [
        'USD',
        'NPR',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';

    /**
     * Scope for successful payments
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCEEDED;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Mark payment as successful
     */
    public function markAsSuccessful(): self
    {
        $this->update([
            'status' => self::STATUS_SUCCEEDED,
            'paid_at' => now(),
        ]);

        return $this;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed(?string $message = null): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'failure_message' => $message,
        ]);

        return $this;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return strtoupper($this->currency) . ' ' . number_format((float) $this->amount, 2);
    }

    /**
     * Ensure currency is stored in uppercase and valid format.
     */
    public function setCurrencyAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['currency'] = null;
            return;
        }

        $val = strtoupper((string) $value);

        // If incoming currency isn't in allowed list, fall back to default 'NPR'
        if (!in_array($val, self::CURRENCIES, true)) {
            $val = 'NPR';
        }

        $this->attributes['currency'] = $val;
    }

    public function markAsCanceled(): self
    {
        $this->update([
            'status' => self::STATUS_CANCELED,
        ]);
        return $this;
    }




}
