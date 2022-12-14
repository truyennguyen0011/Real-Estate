<?php

namespace App\Http\Controllers;

use App\Enums\AdminRoleEnum;
use App\Http\Requests\Administrator\UpdateAdministratorRequest;
use App\Models\Administrator;
use App\Models\Category;
use App\Models\News;
use App\Models\Post;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\Setting\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    use ResponseTrait;

    private array $configs;
    private object $postModel;
    private object $newsModel;
    private object $adminModel;
    private object $categoryModel;
    private string $dir;
    private string $imagesDir;
    private string $avatarAdmin;

    public function __construct()
    {
        $this->configs = SystemConfigController::getAndCache();
        $this->postModel = Post::query();
        $this->newsModel = News::query();
        $this->adminModel = Administrator::query();
        $this->categoryModel = Category::query();
        $this->dir = config('app.image_avatar_direction');
        $this->imagesDir = config('app.images_direction');
        $this->avatarAdmin = config('app.image_avatar_admin');

        View::share('title', ucwords('Dashboard'));
        View::share('roles', $this->configs['roles']);
    }

    public function index(Request $request)
    {
        $user = getUserSession($request);

        if ($user->role == AdminRoleEnum::MASTER) {
            $isMaster = true;
            $totalPost = $this->postModel
                ->count();
            $totalNews = $this->newsModel
                ->count();
            $totalAdmins = $this->adminModel
                ->count() - 1;
            $totalCategories = $this->categoryModel
                ->count();
            return view('admin.dashboard', [
                'user' => $user,
                'isMaster' => $isMaster,
                'totalPost' => $totalPost,
                'totalNews' => $totalNews,
                'totalAdmins' => $totalAdmins,
                'totalCategories' => $totalCategories,
            ]);
        } else {
            $totalPost = $this->postModel
                ->where('administrator_id', $user->id)
                ->count();
            $totalNews = $this->newsModel
                ->where('administrator_id', $user->id)
                ->count();
            return view('admin.dashboard', [
                'user' => $user,
                'totalPost' => $totalPost,
                'totalNews' => $totalNews,
            ]);
        }
    }

    public function show(Request $request)
    {
        $user = getUserSession($request);
        return view('admin.account', [
            'user' => $user,
        ]);
    }

    public function edit(UpdateAdministratorRequest $request, $id)
    {
        $user = getUserSession($request);
        $admin = $this->adminModel->findOrFail($id);

        DB::beginTransaction();
        try {
            if ($user->id == $id) {
                $arr = $request->validated();
                $arr['about_me'] = htmlentities($request->about_me, ENT_QUOTES, 'UTF-8');

                if ($request->has('password')) {
                    $password = Str::of($request->password)->trim();

                    if (Hash::check($password, $user->password)) {
                        $admin->update($arr);
                        DB::commit();
                        return redirect()->back()->with('my-success', 'C???p nh???t th??nh c??ng !');
                    } else {
                        return redirect()->back()->withErrors([
                            'my-error' => 'M???t kh???u kh??ng h???p l??? !'
                        ]);
                    }
                } else {
                    return redirect()->back()->withErrors([
                        'my-error' => 'Vui l??ng nh???p m???t kh???u !'
                    ]);
                }
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'my-error' => 'L???i kh??ng x??c ?????nh !'
            ]);
        }
    }

    public function changeAvatar(Request $request)
    {
        $new_image = '';

        DB::beginTransaction();
        try {
            // Validation request
            $this->validate(
                $request,
                [
                    'user_id' => 'required',
                    'avatar' => 'required',
                ],
                [
                    'user_id.required' => 'Id kh??ng ???????c ????? tr???ng!',
                    'avatar.required' => 'Avatar kh??ng ???????c ????? tr???ng!',
                ]
            );

            if ($request->user_id != '') {
                $id = $request->user_id;
            }

            $admin = $this->adminModel->findOrFail($id);
            $oldAvatar = $admin->avatar;

            if ($request->hasFile('avatar')) {
                $avatar = $request->avatar;
                $fileName = time() . '.' . $request->avatar->getClientOriginalName();

                $img = \Image::make($avatar);
                $size = $img->width();
                $img->resize($size, $size);

                if ($fileName !== $oldAvatar) {
                    if ($oldAvatar != '' && $oldAvatar != $this->avatarAdmin) {
                        if (file_exists($this->dir . $oldAvatar)) {
                            unlink($this->dir . $oldAvatar);
                        }
                    }
                    $img->save(public_path($this->dir . $fileName));
                    $new_image = $fileName;
                    $admin->update(['avatar' => $fileName]);
                }
            }

            DB::commit();
            return $this->successResponse('S???a avatar th??nh c??ng !', $fileName);
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($new_image != '') {
                if (file_exists($this->dir . $new_image)) {
                    unlink($this->dir . $new_image);
                }
            }
            return $this->errorResponse('S???a avatar th???t b???i !');
        }
    }

    public function changePassword(Request $request)
    {
        $user = getUserSession($request);

        return view('admin.change-password', [
            'user' => $user,
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = getUserSession($request);
        $id = $user->id;

        DB::beginTransaction();
        try {

            $request->validated();

            if ($id != '') {
                $admin = $this->adminModel->findOrFail($id);

                if ($request->has('old_password')) {
                    $oldPassword = Str::of($request->old_password)->trim();
                    $newPassword = Str::of($request->new_password)->trim();

                    if (Hash::check($oldPassword, $user->password)) {
                        $admin->password = Hash::make($newPassword);;
                        $admin->update();
                        DB::commit();
                        return redirect()->back()->with('my-success', 'C???p nh???t m???t kh???u th??nh c??ng !');
                    } else {
                        return redirect()->back()->withErrors([
                            'my-error' => 'M???t kh???u kh??ng h???p l??? !'
                        ]);
                    }
                } else {
                    return redirect()->back()->withErrors([
                        'my-error' => 'Vui l??ng nh???p m???t kh???u !'
                    ]);
                }
            }

            DB::commit();
            return $this->successResponse('C???p nh???t m???t kh???u th??nh c??ng !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'my-error' => 'L???i kh??ng x??c ?????nh !',
            ]);
        }
    }

    protected function putPermanentEnv($envKey, $envValue)
    {
        $envValue = '"' . $envValue . '"';
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = '"' . env($envKey) . '"';

        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);

        fclose($fp);
    }

    public function settingWebsite(Request $request)
    {
        $user = getUserSession($request);

        if ($user != '') {
            return view('admin.setting-website', [
                'user' => $user,
            ]);
        }
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = getUserSession($request);

        DB::beginTransaction();
        try {
            $request->validated();

            if ($request->has('password')) {
                $password = Str::of($request->password)->trim();

                if (Hash::check($password, $user->password)) {
                    if (config('app.name') != $request->app_name) {
                        $this->putPermanentEnv('APP_NAME', $request->app_name);
                    }
                    if (config('app.description') != $request->app_description) {
                        $this->putPermanentEnv('APP_DESCRIPTION', $request->app_description);
                    }
                    if (config('app.my_services_1') != $request->my_services_1) {
                        $this->putPermanentEnv('MY_SERVICES_1', $request->my_services_1);
                    }
                    if (config('app.my_services_2') != $request->my_services_2) {
                        $this->putPermanentEnv('MY_SERVICES_2', $request->my_services_2);
                    }
                    if (config('app.my_services_3') != $request->my_services_3) {
                        $this->putPermanentEnv('MY_SERVICES_3', $request->my_services_3);
                    }
                    if (config('app.my_services_4') != $request->my_services_4) {
                        $this->putPermanentEnv('MY_SERVICES_4', $request->my_services_4);
                    }
                    if (config('app.my_services_5') != $request->my_services_5) {
                        $this->putPermanentEnv('MY_SERVICES_5', $request->my_services_5);
                    }
                    if (config('app.company_address') != $request->company_address) {
                        $this->putPermanentEnv('COMPANY_ADDRESS', $request->company_address);
                    }
                    if (config('app.company_email') != $request->company_email) {
                        $this->putPermanentEnv('COMPANY_EMAIL', $request->company_email);
                    }
                    if (config('app.company_hotline') != $request->company_hotline) {
                        $this->putPermanentEnv('COMPANY_HOTLINE', $request->company_hotline);
                    }
                    if (config('app.fb_page') != $request->fb_page) {
                        $this->putPermanentEnv('FB_PAGE', str_replace('"', "'", $request->fb_page));
                    }
                    if (config('app.map_embed') != $request->map_embed) {
                        $this->putPermanentEnv('MAP_EMBED', str_replace('"', "'", $request->map_embed));
                    }

                    DB::commit();
                    return redirect()->back()->with('my-success', 'C???p nh???t th??nh c??ng !');
                } else {
                    return redirect()->back()->withErrors([
                        'my-error' => 'M???t kh???u kh??ng h???p l??? !'
                    ]);
                }
            } else {
                return redirect()->back()->withErrors([
                    'my-error' => 'Vui l??ng nh???p m???t kh???u !'
                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'my-error' => 'L???i kh??ng x??c ?????nh !',
            ]);
        }
    }

    public function changeImageWebsite(Request $request)
    {
        $new_image = '';
        $str = '';

        DB::beginTransaction();
        try {
            if ($request->has('name')) {
                $str = $request->name;
            } else {
                return $this->errorResponse("S???a th???t b???i !");
            }
            // Validation request
            $this->validate(
                $request,
                [
                    'user_id' => 'required',
                    'image' => 'required',
                    'name' => 'required',
                ],
                [
                    'user_id.required' => 'Id kh??ng ???????c ????? tr???ng!',
                    'image.required' => '???nh kh??ng ???????c ????? tr???ng!',
                    'name.required' => 'Lo???i ???nh kh??ng ???????c ????? tr???ng!',
                ]
            );

            if ($request->user_id != '') {
                $id = $request->user_id;
            }

            $admin = $this->adminModel->findOrFail($id);

            if ($admin->role == AdminRoleEnum::MASTER) {
                if ($request->hasFile('image')) {
                    $image = $request->image;

                    if ($str == 'favicon') {
                        $fileName = 'favicon.ico';
                    } else {
                        $fileName = "$str.png";
                    }

                    $img = \Image::make($image);
                    $w = $img->width();
                    $h = $img->height();
                    // Need edit
                    if ($w > 1584) {
                        $img->resize($w / 2, $h / 2);
                    }

                    $img->save(public_path($this->imagesDir . $fileName));
                    $new_image = $fileName;

                    DB::commit();
                    return $this->successResponse("S???a $str th??nh c??ng ! Website s??? l??m m???i sau v??i gi??y");
                }
            } else {
                return $this->errorResponse('Kh??ng ????? quy???n ????? th???c hi???n !');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($new_image != '') {
                if (file_exists($this->imagesDir . $new_image)) {
                    unlink($this->imagesDir . $new_image);
                }
            }
            return $this->errorResponse("S???a $str th???t b???i !" . $th);
        }
    }
}
