<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCategoryRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required'
            ],
            'title' => [
                'required',
                'string',
                'filled',
                'min:1',
                'max:255',
                'unique:categories,title,' . $this->id,
            ],
            'slug' => [
                'required',
                'string',
                'filled',
                'min:1',
                'max:255',
                'unique:categories,slug,' . $this->id,
            ],
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'ID không được để trống !',
            'title.required' => 'Tiêu đề không được để trống !',
            'title.string' => 'Tiêu đề phải là chữ !',
            'title.filled' => 'Tiêu đề không hợp lệ !',
            'title.min' => 'Tiêu đề quá ngắn !',
            'title.max' => 'Tiêu đề quá dài !',
            'title.unique' => 'Tiêu đề đã tồn tại !',
            'slug.required' => 'Slug không được để trống !',
            'slug.string' => 'Slug phải là chữ !',
            'slug.filled' => 'Slug không hợp lệ !',
            'slug.min' => 'Slug quá ngắn !',
            'slug.max' => 'Slug quá dài !',
            'slug.unique' => 'Slug đã tồn tại !',
        ];
    }
}
