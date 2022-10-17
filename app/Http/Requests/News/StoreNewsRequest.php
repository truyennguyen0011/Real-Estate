<?php

namespace App\Http\Requests\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreNewsRequest extends FormRequest
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
                'unique:news,title',
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
                'max:5000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề không được để trống !',
            'title.string' => 'Tiêu đề phải là chữ !',
            'title.filled' => 'Tiêu đề không hợp lệ !',
            'title.min' => 'Tiêu đề quá ngắn !',
            'title.max' => 'Tiêu đề quá dài !',
            'title.unique' => 'Tiêu đề đã tồn tại !',
            'content.required' => 'Nội dung không được để trống !',
            'content.string' => 'Nội dung phải là chữ !',
            'content.filled' => 'Nội dung không hợp lệ !',
            'content.min' => 'Nội dung quá ngắn !',
            'image_thumb.file' => 'Ảnh bìa phải là file ảnh !',
            'image_thumb.image' => 'Ảnh bìa phải là file ảnh !',
            'image_thumb.max' => 'Dung lượng của file ảnh tối đa 5000 kb !',
        ];
    }
}
