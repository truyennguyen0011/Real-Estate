<?php

namespace App\Http\Controllers;

use App\Enums\AdminRoleEnum;
use App\Models\Administrator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Throwable;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\Administrator\StoreAdministratorRequest;
use App\Http\Requests\Administrator\UpdateAdministratorRequest;
use App\Http\Requests\CheckActiveRequest;

class AdministratorController extends Controller
{

    use ResponseTrait;

    private object $model;
    private string $table;
    private array $configs;
    private string $dir;
    private string $avatarAdmin;

    public function __construct()
    {
        $this->model = Administrator::query();
        $this->table = (new Administrator())->getTable();
        $this->configs = SystemConfigController::getAndCache();
        $this->dir = config('app.image_avatar_direction');
        $this->avatarAdmin = config('app.image_avatar_admin');

        View::share('title', ucwords('Admin'));
        View::share('table', $this->table);
        View::share('roles', $this->configs['roles']);
    }

    public function index(Request $request)
    {
        $user = getUserSession($request);

        // $query = $this->model->clone()
        //     ->where('role', '!=', AdminRoleEnum::MASTER)
        //     ->latest();
        // $data = $query->paginate();

        return view("admin.$this->table.index", [
            'user' => $user,
        ]);
    }

    public function api()
    {
        $model = $this->model->where('role', '!=', AdminRoleEnum::MASTER);

        return DataTables::of($model)
            ->editColumn('role', function ($object) {
                return $object->role_name;
            })
            ->editColumn('avatar', function ($object) {
                $src = asset(config('app.image_avatar_direction') . ($object->avatar ? $object->avatar : 'no-avatar.png'));
                return "<img src='$src' height='100'>";
            })
            ->editColumn('contact', function ($object) {
                return $object;
            })
            ->editColumn('active', function ($object) {
                return $object;
            })
            ->addColumn('action', function ($object) {
                $arr['routeEdit'] = route("admin.$this->table.edit", $object);
                $arr['routeDelete'] = route("admin.$this->table.destroy", $object);
                return $arr;
            })
            ->rawColumns(['avatar'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = getUserSession($request);
        return view("admin.$this->table.create", [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdministratorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdministratorRequest $request)
    {
        DB::beginTransaction();
        try {
            $arr = $request->validated();
            if ($request->hasFile('avatar')) {
                $fileName = time() . '.' . $request->avatar->getClientOriginalName();
                $request->avatar->move(public_path($this->dir), $fileName);

                $arr['avatar'] = $fileName;
            }
            $arr['password'] = Hash::make(Str::of($arr['password'])->trim());
            $this->model->create($arr);

            DB::commit();
            return redirect()->route("admin.$this->table.index")->with('my-success', '????ng k?? admin th??nh c??ng!');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'my-error' => '????ng k?? admin th???t b???i!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function show(Administrator $administrator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            $user = getUserSession($request);
            $admin = $this->model->findOrFail($id);

            if ($admin->role === $this->configs['roles']['MASTER']) {
                return redirect()->back()->withErrors([
                    'my-error' => 'S???a th???t b???i ! Kh??ng th??? s???a ng?????i n??y'
                ]);
            }

            return view("admin.$this->table.edit", [
                'user' => $user,
                'admin' => $admin,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'S???a th???t b???i ! L???i kh??ng x??c ?????nh.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdministratorRequest  $request
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdministratorRequest $request, $id)
    {
        $new_image = '';

        DB::beginTransaction();
        try {
            $admin = $this->model->findOrFail($id);
            $arr = $request->validated();
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
                    $arr['avatar'] = $fileName;
                }
            }
            $admin->update($arr);

            DB::commit();
            return redirect()->back()->with('my-success', 'S???a admin th??nh c??ng!');
        } catch (\Throwable $th) {
            DB::rollBack();
            if (file_exists($this->dir . $new_image)) {
                unlink($this->dir . $new_image);
            }
            return redirect()->back()->withErrors([
                'my-error' => 'S???a admin th???t b???i!'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Administrator  $administrator
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $admin = $this->model->findOrFail($id);
            if ($admin->role === $this->configs['roles']['MASTER']) {
                return $this->errorResponse('X??a th???t b???i ! B???n kh??ng ????? quy???n l??m ??i???u n??y !');
            }

            if ($admin->active == 1) {
                $admin->active = 0;
                $admin->save();
            }
            $admin->delete();
            return $this->successResponse('X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('X??a th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }

    // Trash admin
    public function admin_trash(Request $request)
    {
        $user = getUserSession($request);

        $query = $this->model->onlyTrashed()
            ->where('role', '!=', AdminRoleEnum::MASTER)
            ->orderBy('deleted_at', 'DESC');
        $data = $query->paginate();

        return view("admin.$this->table.trash", [
            'user' => $user,
            'data' => $data,
        ]);
    }

    //  Restore admin
    public function restore($id)
    {
        try {
            $this->model->withTrashed()->findOrFail($id)->restore();

            return redirect()->back()->with('my-success', 'Kh??i ph???c th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Kh??i ph???c th???t b???i ! L???i kh??ng x??c ?????nh.'
            ]);
        }
    }

    //  Restore all admin
    public function restoreAll()
    {
        try {
            $this->model->onlyTrashed()->restore();

            return redirect()->back()->with('my-success', 'Kh??i ph???c t???t c??? th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Kh??i ph???c th???t b???i ! L???i kh??ng x??c ?????nh.'
            ]);
        }
    }

    // force delete
    public function forceDelete($id)
    {
        try {
            $admin = $this->model->withTrashed()->findOrFail($id);
            $avatar = $admin->avatar;

            if ($admin->role === $this->configs['roles']['MASTER']) {
                return redirect()->back()->withErrors([
                    'my-error' => 'X??a th???t b???i ! Kh??ng th??? x??a ng?????i n??y'
                ]);
            }

            $admin->forceDelete();
            if ($avatar != $this->avatarAdmin && file_exists($this->dir . $avatar)) {
                unlink($this->dir . $avatar);
            }
            return redirect()->back()->with('my-success', 'X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'X??a th???t b???i ! L???i kh??ng x??c ?????nh.'
            ]);
        }
    }

    public function changeActive(CheckActiveRequest $request)
    {
        try {

            // Validation request
            $arr = $request->validated();

            $admin = $this->model->findOrFail($arr['id']);

            if ($admin->role === $this->configs['roles']['MASTER']) {
                return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! B???n kh??ng ????? quy???n l??m ??i???u n??y !');
            }

            if ($arr['status'] != 0 && $arr['status'] != 1) {
                return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! Tr???ng th??i kh??ng h???p l??? !');
            }

            $admin->active = $arr['status'];
            $admin->save();

            return $this->successResponse('Thay ?????i tr???ng th??i th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }
}
