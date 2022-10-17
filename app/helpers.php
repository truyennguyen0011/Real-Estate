<?php

use Illuminate\Support\Facades\Auth;

function getUserSession($request){
    if ($request->session()->exists('user')) {
        return $request->session()->get('user');
    } else {
        return Auth::guard('admin')->user();
    }
    return redirect()->route('admin.login');
}