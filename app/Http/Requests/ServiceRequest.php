<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
        $isUpdate = $this->route('id') !== null;
        
        $rules = [
            'title' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'description' => $isUpdate ? 'sometimes|required|string' : 'required|string',
            'yoga_type' => $isUpdate ? 'sometimes|required|string|max:255' : 'required|string|max:255',
            'benefits' => 'nullable',
            'class_schedule' => 'nullable',
            'session_time' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:255',
            'instructor_id' => $isUpdate ? 'sometimes|required|exists:instructors,id' : 'required|exists:instructors,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'price' => $isUpdate ? 'sometimes|required|numeric|min:0' : 'required|numeric|min:0',
            'capacity' => $isUpdate ? 'sometimes|required|integer|min:1' : 'required|integer|min:1',
            'currency' => 'nullable|in:USD,NPR',
            'pricing_options' => 'nullable|array',
            'pricing_options.*.label' => 'required_with:pricing_options|string|max:255',
            'pricing_options.*.price' => 'required_with:pricing_options|numeric|min:0',
            'pricing_options.*.discount_price' => 'nullable|numeric|min:0',
            'pricing_options.*.currency' => 'required_with:pricing_options|in:USD,NPR',
            'pricing_options.*.min_pax' => 'required_with:pricing_options|integer|min:1',
            'pricing_options.*.max_pax' => 'required_with:pricing_options|integer|min:1',
            'pricing_options.*.description' => 'nullable|string',
            'pricing_options.*.images' => 'nullable|array',
            'pricing_options.*.images.*' => 'string|url',
            'available_time_slots' => 'nullable|array',
        ];
        
        // After prepareForValidation runs, validate array items
        if (is_array($this->benefits)) {
            $rules['benefits.*'] = 'string';
        }
        
        if (is_array($this->class_schedule)) {
            $rules['class_schedule.*'] = 'string';
        }
        
        return $rules;
    }
    
    public function prepareForValidation()
    {
        $data = [];
        
        // Benefits comes as comma-separated string
        if ($this->has('benefits')) {
            if (is_string($this->benefits)) {
                $data['benefits'] = array_map('trim', explode(',', $this->benefits));
            }
        }
        
        // Class schedule comes as JSON string
        if ($this->has('class_schedule')) {
            if (is_string($this->class_schedule)) {
                $decoded = json_decode($this->class_schedule, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data['class_schedule'] = $decoded;
                }
            }
        }
        
        // Pricing options comes as JSON string
        if ($this->has('pricing_options')) {
            if (is_string($this->pricing_options)) {
                $decoded = json_decode($this->pricing_options, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data['pricing_options'] = $decoded;
                }
            }
        }
        
        // Available time slots comes as JSON string
        if ($this->has('available_time_slots')) {
            if (is_string($this->available_time_slots)) {
                $decoded = json_decode($this->available_time_slots, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['available_time_slots'] = $decoded;
                }
            }
        }
        
        if (!empty($data)) {
            $this->merge($data);
        }
    }
}
