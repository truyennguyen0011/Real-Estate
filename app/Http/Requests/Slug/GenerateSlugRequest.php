<?php

namespace App\Http\Requests\Slug;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GenerateSlugRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'filled',
                'min:1',
                'max:255',
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống !',
            'title.string' => 'Tiêu đề phải là chữ !',
            'title.filled' => 'Tiêu đề không hợp lệ !',
            'title.min' => 'Tiêu đề quá ngắn!',
            'title.max' => 'Tiêu đề quá dài!',
        ];
    }
}
