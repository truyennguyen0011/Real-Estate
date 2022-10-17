<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'filled',
                'string',
                'min:20',
                'max:255',
                'unique:news,title,' . $this->new,
            ],
            'slug' => [
                'required',
                'filled',
                'string',
                'min:20',
                'max:255',
                'unique:news,slug,' . $this->new,
            ],
            'content' => [
                'required',
                'filled',
                'string',
                'min:8',
            ],
            'image_thumb' => [
                'nullable',
                'file',
                'image',
            ],
        ];
    }
}
