<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255',
            'service_id' => 'required|exists:services,id',
            'fromDate' => 'required|string',
            'toDate' => 'required|string',
            'time' => 'required|string',
            'status' => 'required|in:confirmed,pending,cancelled',
            'participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'paidAmount' => 'nullable|numeric|min:0',
            'paymentType' => 'nullable|string|in:advance,full',
            'advancePercentage' => 'nullable|numeric|min:0|max:100',
            'remainingAmount' => 'nullable|numeric|min:0',
            'phone' => 'nullable|string|max:20',
            'payment_id' => 'nullable|integer',
            'pricing_tier_label' => 'nullable|string|max:255',
            'pricing_tier_description' => 'nullable|string',
            'payment_method' => 'nullable|string|max:50',
        ];
    }
}
