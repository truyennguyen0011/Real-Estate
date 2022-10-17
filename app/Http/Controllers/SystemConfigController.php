<?php

namespace App\Http\Controllers;

use App\Enums\AdminRoleEnum;
use App\Enums\ImageRatioEnum;
use Illuminate\Http\Request;

class SystemConfigController extends Controller
{
    public static function getAndCache()
    {
        return cache()->remember('configs', 24 * 60 * 60, function () {
            $arr = [];
            $arr['roles'] = AdminRoleEnum::asArray();
            $arr['ratios'] = ImageRatioEnum::asArray();

            return $arr;
        });
    }

}
