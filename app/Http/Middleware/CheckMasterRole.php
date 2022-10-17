<?php

namespace App\Http\Middleware;

use App\Enums\AdminRoleEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMasterRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ((int) Auth::guard('admin')->user()->role !== AdminRoleEnum::MASTER) {
            return redirect()->back()->withErrors(['error-serious' => 'Mày không có quyền truy cập, hiểu chưa?']);
        }
        return $next($request);
    }
}
