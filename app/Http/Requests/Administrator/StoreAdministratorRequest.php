<?php

namespace App\Http\Requests\Administrator;

use App\Enums\AdminRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreAdministratorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Check logged in
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
            'name' => [
                'required',
                'filled',
                'string',
                'min:1',
                'max:128',
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
                'max:5000',
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'regex:/(.+)@(.+)\.(.+)/i',
                'max:128',
                'unique:administrators,email',
            ],
            'phone' => [
                'required',
                'string',
                'regex:/(0)[0-9]/',
                'not_regex:/[a-z]/',
                'min:10',
                'max:16',
                'unique:administrators,phone',
            ],
            'password' => [
                'required',
                'string',
                'filled',
                'min:8',
            ],
            'active' => [
                'nullable',
                'boolean',
            ],
            'role' => [
                'required',
                Rule::in(AdminRoleEnum::getValues()),
                Rule::notIn(AdminRoleEnum::MASTER),
            ],
            'about_me' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống !',
            'name.filled' => 'Tên không được để trống !',
            'name.string' => 'Tên không hợp lệ !',
            'name.min' => 'Tên ít nhất phải có một ký tự !',
            'name.max' => 'Tên nhiều nhất là 128 ký tự !',
            'avatar.file' => 'Avatar phải là file ảnh !',
            'avatar.image' => 'Avatar phải là file ảnh !',
            'avatar.max' => 'Dung lượng của file ảnh tối đa 5000 kb !',
            'email.required' => 'Email không được để trống !',
            'email.email' => 'Email không hợp lệ !',
            'email.regex' => 'Email không hợp lệ !',
            'email.max' => 'Độ dài của email tối đa 128 ký tự !',
            'email.unique' => 'Email đã được sử dụng !',
            'phone.required' => 'Số điện thoại không được để trống !',
            'phone.string' => 'Số điện thoại không hợp lệ !',
            'phone.regex' => 'Số điện thoại không hợp lệ !',
            'phone.not_regex' => 'Số điện thoại không hợp lệ !',
            'phone.min' => 'Độ dài của email tối thiểu 10 ký tự !',
            'phone.max' => 'Độ dài của email tối đa 16 ký tự !',
            'phone.unique' => 'Số điện thoại đã được sử dụng !',
            'password.required' => 'Mật khẩu không được để trống !',
            'password.string' => 'Mật khẩu không hợp lệ !',
            'password.filled' => 'Mật khẩu không được để trống !',
            'password.min' => 'Độ dài của mật khẩu phải trên 8 ký tự !',
            'active.boolean' => 'Trạng thái chỉ có thể là true hoặc false !',
            'role.required' => 'Quyền không được để trống !',
            'role.in' => 'Quyền không được để trống !',
            'role.not_in' => 'Quyền không hợp lệ !',
            'about_me.string' => 'Thông tin giới thiệu không hợp lệ !',
        ];
    }
}
