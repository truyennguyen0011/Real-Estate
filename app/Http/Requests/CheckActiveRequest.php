<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CheckActiveRequest extends FormRequest
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
            'id' => 'required|numeric',
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id không được để trống!',
            'id.numeric' => 'Id phải là số!',
            'status.required' => 'Status không được để trống!',
            'status.boolean' => 'Status phải là true hoặc false!',
        ];
    }
}
