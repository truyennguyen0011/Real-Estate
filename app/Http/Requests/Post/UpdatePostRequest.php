<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePostRequest extends FormRequest
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
            'title' => [
                'required',
                'filled',
                'string',
                'min:20',
                'max:255',
                'unique:posts,title,' . $this->post,
            ],
            'slug' => [
                'required',
                'filled',
                'string',
                'min:20',
                'max:255',
                'unique:posts,slug,' . $this->post,
            ],
            'address' => [
                'required',
                'filled',
                'string',
                'min:2',
                'max:255',
            ],
            'price' => [
                'required',
                'filled',
                'string',
                'min:3',
                'max:64',
            ],
            'area' => [
                'required',
                'filled',
                'string',
                'min:2',
                'max:128',
            ],
            'name_seller' => [
                'required',
                'filled',
                'string',
                'min:1',
                'max:128',
            ],
            'email_seller' => [
                'nullable',
                'email:rfc,dns',
                'regex:/(.+)@(.+)\.(.+)/i',
                'max:128',
            ],
            'phone_seller' => [
                'required',
                'string',
                'regex:/(0)[0-9]/',
                'not_regex:/[a-z]/',
                'min:10',
                'max:16',
            ],
            'direction' => [
                'nullable',
                'string',
                'min:1',
                'max:16',
            ],
            'description' => [
                'required',
                'filled',
                'string',
                'min:20',
            ],
            'category_id' => [
                'required',
                'numeric',
                'min:1',
            ],
            'city_id' => [
                'required',
                'string',
                'min:1',
            ],
            'district_id' => [
                'required',
                'string',
                'min:1',
            ],
            'commune_id' => [
                'required',
                'string',
                'min:1',
            ],
            'youtube_id' => [
                'nullable',
                'string',
                'min:3',
            ],
            'list_images' => [
                'nullable',
                'string',
            ],
            'list_remove_images' => [
                'nullable',
                'string',
            ],
        ];
    }
}
