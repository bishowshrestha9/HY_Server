<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePayoneerSessionRequest extends FormRequest
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
            'orderId' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'description' => 'nullable|string',
            'isAdvancePayment' => 'nullable|boolean',
            'advancePercentage' => 'nullable|numeric|min:0|max:100',
            'fullAmount' => 'nullable|numeric|min:0',
        ];
    }
}
