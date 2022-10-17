<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordRequest extends FormRequest
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
            'old_password' => [
                'required',
                'filled',
                'string',
                'min:8',
            ],
            'new_password' => [
                'required',
                'filled',
                'string',
                'min:8',
            ],
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Mật khẩu cũ không được để trống!',
            'old_password.string' => 'Mật khẩu cũ không hợp lệ !',
            'old_password.filled' => 'Mật khẩu cũ không được để trống !',
            'old_password.min' => 'Độ dài của mật khẩu phải trên 8 ký tự !',
            'new_password.required' => 'Mật khẩu mới không được để trống!',
            'new_password.string' => 'Mật khẩu mới không hợp lệ !',
            'new_password.filled' => 'Mật khẩu mới không được để trống !',
            'new_password.min' => 'Độ dài của mật khẩu phải trên 8 ký tự !',
        ];
    }
}
