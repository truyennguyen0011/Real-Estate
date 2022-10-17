<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInfoRequest extends FormRequest
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
            'app_name' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'app_description' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'my_services_1' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'my_services_2' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'my_services_3' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'my_services_4' => [
                'nullable',
                'string',
                'min:3',
            ],
            'my_services_5' => [
                'nullable',
                'string',
                'min:3',
            ],
            'company_address' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'company_email' => [
                'required',
                'filled',
                'email',
                'min:3',
            ],
            'company_hotline' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'fb_page' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'map_embed' => [
                'required',
                'filled',
                'string',
                'min:3',
            ],
            'password' => [
                'required',
                'string',
                'filled',
                'min:8',
            ],
        ];
    }

    public function messages()
    {
        return [
            'app_name.required' => 'Tên không được để trống !',
            'app_name.filled' => 'Tên không được để trống !',
            'app_name.string' => 'Tên không hợp lệ !',
            'app_name.min' => 'Tên ít nhất phải có 3 ký tự !',
            'app_description.required' => 'Mô tả không được để trống !',
            'app_description.filled' => 'Mô tả không được để trống !',
            'app_description.string' => 'Mô tả không hợp lệ !',
            'app_description.min' => 'Mô tả ít nhất phải có 3 ký tự !',
            'my_services_1.required' => 'Trường này không được để trống !',
            'my_services_1.filled' => 'Trường này không được để trống !',
            'my_services_1.string' => 'Trường này không hợp lệ !',
            'my_services_1.min' => 'Trường này ít nhất phải có 3 ký tự !',
            'my_services_2.required' => 'Trường này không được để trống !',
            'my_services_2.filled' => 'Trường này không được để trống !',
            'my_services_2.string' => 'Trường này không hợp lệ !',
            'my_services_2.min' => 'Trường này ít nhất phải có 3 ký tự !',
            'my_services_3.required' => 'Trường này không được để trống !',
            'my_services_3.filled' => 'Trường này không được để trống !',
            'my_services_3.string' => 'Trường này không hợp lệ !',
            'my_services_3.min' => 'Trường này ít nhất phải có 3 ký tự !',
            'my_services_4.string' => 'Trường này không hợp lệ !',
            'my_services_4.min' => 'Trường này ít nhất phải có 3 ký tự !',
            'my_services_5.string' => 'Trường này không hợp lệ !',
            'my_services_5.min' => 'Trường này ít nhất phải có 3 ký tự !',
            'company_address.required' => 'Địa chỉ không được để trống !',
            'company_address.filled' => 'Địa chỉ không được để trống !',
            'company_address.string' => 'Địa chỉ không hợp lệ !',
            'company_address.min' => 'Địa chỉ ít nhất phải có 3 ký tự !',
            'company_email.required' => 'Email không được để trống !',
            'company_email.filled' => 'Email không được để trống !',
            'company_email.email' => 'Email không hợp lệ !',
            'company_email.min' => 'Email ít nhất phải có 3 ký tự !',
            'company_hotline.required' => 'Hotline không được để trống !',
            'company_hotline.filled' => 'Hotline không được để trống !',
            'company_hotline.string' => 'Hotline không hợp lệ !',
            'company_hotline.min' => 'Hotline ít nhất phải có 3 ký tự !',
            'fb_page.required' => 'Facebook page không được để trống !',
            'fb_page.filled' => 'Facebook page không được để trống !',
            'fb_page.string' => 'Facebook page không hợp lệ !',
            'fb_page.min' => 'Facebook page ít nhất phải có 3 ký tự !',
            'map_embed.required' => 'Google map không được để trống !',
            'map_embed.filled' => 'Google map không được để trống !',
            'map_embed.string' => 'Google map không hợp lệ !',
            'map_embed.min' => 'Google map ít nhất phải có 3 ký tự !',
            'password.required' => 'Password không được để trống !',
            'password.filled' => 'Password không được để trống !',
            'password.string' => 'Password không hợp lệ !',
            'password.min' => 'Password ít nhất phải có 8 ký tự !',
        ];
    }
}
