<?php

namespace App\Http\Controllers;

use App\Enums\AdminRoleEnum;
use App\Http\Requests\News\StoreNewsRequest;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\CheckActiveRequest;
use App\Http\Requests\News\UpdateNewsRequest;
use App\Models\News;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Throwable;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;
    private array $configs;
    private string $dirNewThumb;

    public function __construct()
    {
        $this->model = News::query();
        $this->table = (new News())->getTable();
        $this->configs = SystemConfigController::getAndCache();
        $this->dirNewThumb = config('app.image_new_thumb_direction');

        View::share('title', ucwords('Admin'));
        View::share('table', $this->table);
        View::share('roles', $this->configs['roles']);
    }

    public function index(Request $request)
    {
        $user = getUserSession($request);

        if ($user->id != '') {
            if ($user->role === AdminRoleEnum::MASTER) {
                $query = $this->model
                    ->with('administrator')
                    ->orderBy('created_at', 'DESC');

                $news = $query->paginate();
            } else {
                $query = $this->model
                    ->where('administrator_id', $user->id)
                    ->orderBy('created_at', 'DESC');

                $news = $query->paginate();
            }
        }

        return view("admin.$this->table.index", [
            'user' => $user,
            'news' => $news,
        ]);
    }

    public function create(Request $request)
    {
        $user = getUserSession($request);
        return view("admin.$this->table.create", [
            'user' => $user,
        ]);
    }

    public function store(StoreNewsRequest $request)
    {
        $image_name = '';
        DB::beginTransaction();
        try {

            $user = getUserSession($request);
            $id = $user->id;

            $arr = $request->validated();

            $title = $arr['title'];
            $slug  = SlugService::createSlug(News::class, 'slug', $title);

            $arr['administrator_id'] = $id;
            $arr['active'] = 1;
            $arr['published_at'] = now();
            $arr['slug'] = $slug;
            if ($request->has('content')) {
                $arr['content'] = htmlentities($request->content, ENT_QUOTES, 'UTF-8');
            }

            if ($request->hasFile('image_thumb')) {
                $image_thumb = $request->image_thumb;

                $rdString = Str::random(3);
                $format = explode(".", $image_thumb->getClientOriginalName());
                $tail = strtolower($format[count($format) - 1]) ?? 'jpg';
                $fileName = time() . '-' . $rdString . '.' . $tail;
                $image_name = $fileName;

                $img = \Image::make($image_thumb);

                $w = $img->width();
                $h = $img->height();

                if ($w > $h) {
                    $img->resize(1280, 1280 * $h / $w);
                } else if ($h > $w) {
                    $img->resize(720, 720 * $h / $w);
                }

                $img->save(public_path($this->dirNewThumb . $fileName));
                $arr['image_thumb'] = $fileName;
            }

            $this->model->create($arr);

            DB::commit();
            return redirect()->route("admin.$this->table.index")->with('my-success', 'Tạo tin tức thành công!');
        } catch (Throwable $e) {
            DB::rollBack();
            if (file_exists($this->dirNewThumb . $image_name)) {
                unlink($this->dirNewThumb . $image_name);
            }
            return redirect()->back()->withErrors([
                'my-error' => 'Tạo tin tức thất bại!'
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $user = getUserSession($request);
            $new = $this->model->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Sửa thất bại ! Không thể sửa bài viết của người khác !'
                ]);
            }

            return view("admin.$this->table.edit", [
                'user' => $user,
                'new' => $new,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Sửa thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    public function update(UpdateNewsRequest $request, $id)
    {
        $image_name = '';

        DB::beginTransaction();
        try {
            $user = getUserSession($request);
            $new = $this->model->findOrFail($id);

            $oldThumb = $new->image_thumb;

            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Sửa thất bại ! Không thể sửa bài viết của người khác !'
                ]);
            }

            $arr = $request->validated();

            if ($request->has('content')) {
                $arr['content'] = htmlentities($request->content, ENT_QUOTES, 'UTF-8');
            }

            if ($request->hasFile('image_thumb')) {
                $image_thumb = $request->image_thumb;

                $rdString = Str::random(3);
                $format = explode(".", $image_thumb->getClientOriginalName());
                $tail = strtolower($format[count($format) - 1]) ?? 'jpg';
                $fileName = time() . '-' . $rdString . '.' . $tail;
                $image_name = $fileName;

                $img = \Image::make($image_thumb);

                $w = $img->width();
                $h = $img->height();

                if ($w > $h) {
                    $img->resize(1280, 1280 * $h / $w);
                } else if ($h > $w) {
                    $img->resize(720, 720 * $h / $w);
                }

                if ($fileName !== $oldThumb) {
                    if (file_exists($this->dirNewThumb . $oldThumb)) {
                        unlink($this->dirNewThumb . $oldThumb);
                    }
                }

                $img->save(public_path($this->dirNewThumb . $fileName));
                $arr['image_thumb'] = $fileName;
            }

            $new->update($arr);

            DB::commit();
            return redirect()->route("admin.$this->table.index")->with('my-success', 'Sửa tin tức thành công!');
        } catch (Throwable $e) {
            DB::rollBack();
            if (file_exists($this->dirNewThumb . $image_name)) {
                unlink($this->dirNewThumb . $image_name);
            }
            return redirect()->back()->withErrors([
                'my-error' => 'Sửa tin tức thất bại!' . $e
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = getUserSession($request);

            $new = $this->model->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Xóa thất bại ! Không thể xóa bài viết của người khác !'
                ]);
            }

            if ($new->active == 1) {
                $new->active = 0;
                $new->save();
            }
            $new->delete();
            return redirect()->back()->with('my-success', 'Xóa thành công !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Xóa thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    public function trash(Request $request)
    {
        $user = getUserSession($request);

        if ($user->id != '') {
            if ($user->role === AdminRoleEnum::MASTER) {
                $query = $this->model->onlyTrashed()
                    ->orderBy('deleted_at', 'DESC');
            } else {
                $query = $this->model->onlyTrashed()
                    ->where('administrator_id', $user->id)
                    ->orderBy('deleted_at', 'DESC');
            }
        }

        $data = $query->paginate();

        return view("admin.$this->table.trash", [
            'user' => $user,
            'data' => $data,
        ]);
    }

    //  Restore admin
    public function restore(Request $request, $id)
    {
        try {
            $user = getUserSession($request);

            $new = $this->model->withTrashed()->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Khôi phục thất bại ! Không thể khôi phục bài viết của người khác !'
                ]);
            }

            $new->restore();

            return redirect()->back()->with('my-success', 'Khôi phục thành công !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Khôi phục thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    //  Restore all admin
    public function restoreAll(Request $request)
    {
        try {
            $user = getUserSession($request);

            if ($user->id != '') {
                if ($user->role === AdminRoleEnum::MASTER) {
                    $this->model->onlyTrashed()
                        ->restore();
                } else {
                    $this->model->onlyTrashed()
                        ->where('administrator_id', $user->id)
                        ->restore();
                }
            }

            return redirect()->back()->with('my-success', 'Khôi phục tất cả thành công !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Khôi phục thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    // force delete
    public function forceDelete(Request $request, $id)
    {
        try {
            $user = getUserSession($request);

            $new = $this->model->withTrashed()->findOrFail($id);
            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Xóa thất bại ! Không thể xóa bài viết của người khác !'
                ]);
            }
  
            if ($new->image_thumb != '') {
                if (file_exists(config('app.image_new_thumb_direction') . $new->image_thumb)) {
                    unlink(config('app.image_new_thumb_direction') . $new->image_thumb);
                }
            }

            $new->forceDelete();

            return redirect()->back()->with('my-success', 'Xóa thành công !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Xóa thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    // Change active
    public function changeActive(CheckActiveRequest $request)
    {
        try {
            $user = getUserSession($request);

            // Validation request
            $arr = $request->validated();

            $new = $this->model->findOrFail($arr['id']);

            // User role, id is integer variable and AdminRoleEnum::MASTER, $new->administrator_id is integer
            if ($user->role !== AdminRoleEnum::MASTER && $new->administrator_id != $user->id) {
                return $this->errorResponse('Thay đổi trạng thái thất bại ! Không thể thay đổi bài viết của người khác !');
            }

            if ($arr['status'] != 0 && $arr['status'] != 1) {
                return $this->errorResponse('Thay đổi trạng thái thất bại ! Trạng thái không hợp lệ !');
            }

            $new->active = $arr['status'];
            $new->update();

            return $this->successResponse('Thay đổi trạng thái thành công !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Thay đổi trạng thái thất bại ! Lỗi không xác định !');
        }
    }

    public function refreshNew(Request $request)
    {
        try {
            // Validation request
            $this->validate(
                $request,
                [
                    'id' => 'required|numeric',
                ],
                [
                    'id.required' => 'Id không được để trống!',
                    'id.numeric' => 'Id phải là số!',
                ]
            );
            $arr = $request->only([
                'id',
            ]);

            $new = $this->model->findOrFail($arr['id']);

            $time = 3600000;
            $time_now = round(microtime(true) * 1000);
            $publish_date = strtotime($new->published_at) * 1000;
            if ($time_now - $publish_date > $time) {
                $new->published_at = now();
                $new->save();
            } else {
                $num = ($time_now - $publish_date);
                $min = ($time - $num) / 1000 / 60;
                return $this->errorResponse('Làm mới sau: ' . (int) round($min) . ' phút !', 'warning');
            }

            return $this->successResponse('Làm mới thành công !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Làm mới thất bại ! Lỗi không xác định !');
        }
    }
}
