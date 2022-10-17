<?php

namespace App\Http\Controllers;

use App\Enums\ImageRatioEnum;
use App\Http\Requests\Image\StoreImagesRequest;
use App\Http\Controllers\ResponseTrait;
use App\Models\Images;
use Illuminate\Support\Facades\View;
use Throwable;
use Illuminate\Support\Str;

class ImagesController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;
    private array $configs;
    private string $dir;
    private string $ratioDefault;

    public function __construct()
    {
        $this->dir = config('app.image_temp_direction');
        $this->model = Images::query();
        $this->table = (new Images())->getTable();
        $this->configs = SystemConfigController::getAndCache();
        $this->ratioDefault = ImageRatioEnum::_16x9;

        View::share('table', $this->table);
        View::share('roles', $this->configs['roles']);
    }

    public function store(StoreImagesRequest $request)
    {
        try {
            $images_arr = [];

            if ($request->hasFile('images')) {
                $x = 4;
                foreach ($request->file('images') as $key => $image) {
                    $rdString = Str::random(3);

                    $format = explode(".", $image->getClientOriginalName());
                    $tail = strtolower($format[count($format) - 1]) ?? 'jpg';
                    $fileName = time() . '-' . $rdString . '.' . $tail;

                    $imgTemp = \Image::make($image);

                    $w = $imgTemp->width();
                    $h = $imgTemp->height();

                    if ($w > $h) {
                        $rs = $this->simplify($w, $h);

                        if ($rs == $this->ratioDefault) {
                            $imgTemp->resize(1280, 720);
                        } else {
                            $imgTemp->resize(1280, 1280 * $h / $w);
                        }
                    } else if ($h > $w) {
                        $rs = $this->simplify($h, $w);

                        if ($rs == $this->ratioDefault) {
                            $imgTemp->resize(720, 1280);
                        } else {
                            $imgTemp->resize(720, 720 * $h / $w);
                        }
                    } else {
                        $rs = '1x1';
                        $imgTemp->resize(1280, 1280);
                    }

                    // $imgTemp->insert(public_path('images/watermark-199.png'), 'top-left', 10, 10);
                    $imgTemp->save(public_path($this->dir . $fileName));

                    array_push($images_arr, $fileName);
                }
                if (count($images_arr) > 0) {
                    return $this->successResponse('Success !', $images_arr);
                }
            }
        } catch (Throwable $e) {
            return $this->errorResponse('Upload failed !');
        }
    }

    public function gcd($a, $b)
    {
        $a = abs($a);
        $b = abs($b);
        if ($a < $b) list($b, $a) = array($a, $b);
        if ($b == 0) return $a;
        $r = $a % $b;
        while ($r > 0) {
            $a = $b;
            $b = $r;
            $r = $a % $b;
        }
        return $b;
    }

    function simplify($num, $den)
    {
        $g = $this->gcd($num, $den);
        return $num / $g . 'x' . $den / $g;
    }
}
