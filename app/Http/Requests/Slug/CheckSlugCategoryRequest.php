<?php

namespace App\Http\Requests\Slug;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckSlugCategoryRequest extends FormRequest
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
            'slug' => [
                'required',
                'string',
                'filled',
                Rule::unique(Category::class),
            ]
        ];
    }

    public function messages()
    {
        return [
            'slug.required' => 'Slug không được để trống !',
            'slug.string' => 'Slug phải là chữ !',
            'slug.filled' => 'Slug không hợp lệ !',
            'slug.unique' => 'Slug đã tồn tại !',
        ];
    }
}
