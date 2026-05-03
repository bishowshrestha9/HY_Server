<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isUpdate = $this->route('id') !== null;

        return [
            'description' => $isUpdate ? 'nullable|string' : 'nullable|string',
            'service_id' => $isUpdate ? 'sometimes|required|exists:services,id' : 'required|exists:services,id',
            'discount_percentage' => $isUpdate ? 'sometimes|required|numeric|min:0|max:100' : 'required|numeric|min:0|max:100',
            'start_date' => $isUpdate ? 'sometimes|required|date' : 'required|date',
            'end_date' => $isUpdate ? 'sometimes|required|date|after_or_equal:start_date' : 'required|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
