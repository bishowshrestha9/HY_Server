<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;


class BannerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'banner_text' => 'required|string|max:255',
            'banner_link' => 'required|string',
            'is_active' => 'required|boolean',
            'background_color' => 'required|string',
            'text_color' => 'required|string',
        ];
    }
}