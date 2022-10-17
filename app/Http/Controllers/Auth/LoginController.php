<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {

            // Check if the admin is logged in navigate to the admin page
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }

            // Check if admin is not logged in navigate to login page
            if ($request->getMethod() == 'GET') {
                return view('admin.auth.login');
            }

            // Validation request
            $this->validate(
                $request,
                [
                    'login' => 'required',
                    'password' => 'required',
                ],
                [
                    'login.required' => 'Email hoặc số điện thoại không được để trống!',
                    'password.required' => 'Mật khẩu không được để trống!',
                ]
            );

            // Check login by phone number or email
            $field = '';

            if (is_numeric($request->input('login'))) {
                $field = 'phone';
            } else if (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } else {
                return redirect()->back()->withErrors([
                    'login' => 'Email hoặc số điện thoại không hợp lệ!',
                ]);
            }

            // Check remember me
            $remember = $request->remember ? true : false;

            $request->merge([$field => $request->input('login')]);
            $credentials = $request->only([
                $field,
                'password',
            ]);
            $credentials['deleted_at'] = null;

            if (Auth::guard('admin')->attempt($credentials, $remember)) {
                // session()->put('user', Auth::guard('admin')->user());
                return redirect()->route('admin.dashboard')->with('my-success', 'Đăng nhập thành công!');
            } else {
                return redirect()->route('admin.login')->withErrors([
                    'invalid_user' => 'Thông tin đăng nhập không hợp lệ!',
                ]);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back();
        }
    }
}
