<?php

namespace App\Http\Controllers;

use App\Enums\AdminRoleEnum;
use App\Models\Post;
use App\Http\Controllers\ResponseTrait;
use App\Http\Requests\CheckActiveRequest;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Category;
use App\Models\City;
use App\Models\District;
use App\Models\Images;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Throwable;

class PostController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;
    private array $configs;
    private string $dirNow;
    private string $dirTemp;
    private string $dirPostThumb;

    public function __construct()
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");

        $this->dirNow = 'uploads/' . $year . '/' . $month . '/' . $day . '/';
        $this->dirTemp = config('app.image_temp_direction');
        $this->dirPostThumb = config('app.image_post_thumb_direction');
        $this->model = Post::query();
        $this->table = (new Post())->getTable();
        $this->configs = SystemConfigController::getAndCache();

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

                $posts = $query->paginate();
            } else {
                $query = $this->model
                    ->where('administrator_id', $user->id)
                    ->orderBy('created_at', 'DESC');

                $posts = $query->paginate();
            }
        }

        return view("admin.$this->table.index", [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::active()->get();
        $cities = City::all();

        $user = getUserSession($request);
        return view("admin.$this->table.create", [
            'user' => $user,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $images_arr = [];
        DB::beginTransaction();
        try {

            $user = getUserSession($request);
            $id = $user->id;

            $arr = $request->validated();

            $title = $arr['title'];
            $slug  = SlugService::createSlug(Post::class, 'slug', $title);

            $arr['administrator_id'] = $id;
            $arr['active'] = 1;
            $arr['published_at'] = now();
            $arr['slug'] = $slug;
            if ($request->has('description')) {
                $arr['description'] = htmlentities($request->description, ENT_QUOTES, 'UTF-8');
            }

            $post = $this->model->create($arr);

            // Images handle
            $images_arr = json_decode($arr['list_images']);
            if (count($images_arr) > 0) {
                // Create thumbnail image for post

                $imgThumb = \Image::make($this->dirTemp . $images_arr[0]);
                $imgThumb->resize(150, 150);
                $imgThumb->save(public_path($this->dirPostThumb . $imgThumb->basename ?? $images_arr[0]));
                $post->thumb_image = $imgThumb->basename ?? $images_arr[0];
                $post->save();

                foreach ($images_arr as $key => $img) {
                    if (file_exists($this->dirTemp . $img)) {
                        File::ensureDirectoryExists($this->dirNow, 0777, true, true);
                        File::move(public_path($this->dirTemp . $img), public_path($this->dirNow . $img));
                        $a = [];
                        $a['title'] = $post->title;
                        $a['url'] = $img;
                        $a['post_id'] = $post->id;
                        $a['folder'] = $this->dirNow;
                        // Create ratio image here

                        Images::create($a);
                    }
                }
            }

            DB::commit();
            return redirect()->route("admin.$this->table.index")->with('my-success', 'Tạo bài đăng thành công!');
        } catch (Throwable $e) {
            DB::rollBack();
            if (count($images_arr) > 0) {
                foreach ($images_arr as $key => $img) {
                    if (file_exists($this->dirNow . $img)) {
                        unlink($this->dirNow . $img);
                    }
                }
            }
            return redirect()->back()->withErrors([
                'my-error' => 'Tạo bài đăng thất bại!' . $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        try {
            $user = getUserSession($request);
            $post = $this->model->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Sửa thất bại ! Không thể sửa bài viết của người khác !'
                ]);
            }
            $categories = Category::active()->get();
            $cities = City::all();


            $districts = City::findOrFail($post->city_id)->districts;
            $communes = District::findOrFail($post->district_id)->communes;

            return view("admin.$this->table.edit", [
                'user' => $user,
                'post' => $post,
                'categories' => $categories,
                'cities' => $cities,
                'districts' => $districts,
                'communes' => $communes,
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Sửa thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $images_arr = [];
        $images_remove_arr = [];
        DB::beginTransaction();
        try {
            $user = getUserSession($request);
            $post = $this->model->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Sửa thất bại ! Không thể sửa bài viết của người khác !'
                ]);
            }

            $arr = $request->validated();

            if ($arr['youtube_id'] == '') {
                if ($post->youtube_id != '') {
                    $arr['youtube_id'] = $post->youtube_id;
                }
            }
            if ($request->has('description')) {
                $arr['description'] = htmlentities($request->description, ENT_QUOTES, 'UTF-8');
            }

            $post->update($arr);

            // Old Images handle
            if ($request->has('list_remove_images')) {
                if ($arr['list_remove_images'] != null) {
                    $images_remove_arr = json_decode($arr['list_remove_images']);
                    if (count($images_remove_arr) > 0) {
                        foreach ($images_remove_arr as $key => $id) {
                            // Find image
                            $img = Images::findOrFail($id);

                            // Delete if image exist
                            if (file_exists($img->folder . $img->url)) {
                                unlink($img->folder . $img->url);
                            }

                            // Delete thumbnail image if same name
                            if ($post->thumb_image == $img->url) {
                                if (file_exists(config('app.image_thumb_direction') . $post->thumb_image)) {
                                    unlink(config('app.image_thumb_direction') . $post->thumb_image);
                                }
                                $post->thumb_image = '';
                            }

                            $img->delete();
                        }
                        if (count($post->images) > 0 && $post->thumb_image == '') {
                            // if images length > 0 and thumbnail image is null create new thumbnail image
                            $img = $post->images[0];

                            $imgThumb = \Image::make($img->folder . $img->url);
                            $imgThumb->resize(150, 150);
                            $imgThumb->save(public_path($this->dirPostThumb . $img->url));
                            $post->thumb_image = $img->url;
                            $post->save();
                        }
                    }
                }
            }

            // Images handle
            if ($request->has('list_images')) {
                if ($arr['list_images'] != null) {
                    $images_arr = json_decode($arr['list_images']);
                }
            }
            if (count($images_arr) > 0) {
                if (count($post->images) == 0) {
                    // Create thumbnail image for post
                    $imgThumb = \Image::make($this->dirTemp . $images_arr[0]);
                    $imgThumb->resize(150, 150);
                    $imgThumb->save(public_path($this->dirPostThumb . $imgThumb->basename ?? $images_arr[0]));
                    $post->thumb_image = $imgThumb->basename ?? $images_arr[0];
                    $post->save();
                }

                foreach ($images_arr as $key => $img) {
                    if (file_exists($this->dirTemp . $img)) {
                        File::ensureDirectoryExists($this->dirNow, 0777, true, true);
                        File::move(public_path($this->dirTemp . $img), public_path($this->dirNow . $img));
                        $a = [];
                        $a['title'] = $post->title;
                        $a['url'] = $img;
                        $a['post_id'] = $post->id;
                        $a['folder'] = $this->dirNow;
                        Images::create($a);
                    }
                }
            }

            DB::commit();
            return redirect()->route("admin.$this->table.index")->with('my-success', 'Sửa bài đăng thành công!');
        } catch (Throwable $e) {
            DB::rollBack();
            if (count($images_arr) > 0) {
                foreach ($images_arr as $key => $img) {
                    if (file_exists($this->dirNow . $img)) {
                        unlink($this->dirNow . $img);
                    }
                }
            }
            return redirect()->back()->withErrors([
                'my-error' => 'Sửa bài đăng thất bại!' . $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = getUserSession($request);

            $post = $this->model->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Xóa thất bại ! Không thể xóa bài viết của người khác !'
                ]);
            }

            if ($post->active == 1) {
                $post->active = 0;
                $post->save();
            }
            $post->delete();
            return redirect()->back()->with('my-success', 'Xóa thành công !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Xóa thất bại ! Lỗi không xác định.'
            ]);
        }
    }

    public function loadDistrict(Request $request)
    {
        try {
            // Validation request
            $this->validate(
                $request,
                [
                    'id' => 'required',
                ],
                [
                    'id.required' => 'ID không được để trống !',
                ]
            );

            $arr = $request->only('id');
            $id = $arr['id'];

            $districts = City::findOrFail($id)->districts;
            return $this->successResponse('Success !', $districts);
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed !');
        }
    }

    public function loadCommunes(Request $request)
    {
        try {
            // Validation request
            $this->validate(
                $request,
                [
                    'id' => 'required',
                ],
                [
                    'id.required' => 'ID không được để trống !',
                ]
            );

            $arr = $request->only('id');
            $id = $arr['id'];

            $communes = District::findOrFail($id)->communes;
            return $this->successResponse('Success !', $communes);
        } catch (\Throwable $th) {
            return $this->errorResponse('Failed !');
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

            $post = $this->model->withTrashed()->findOrFail($id);

            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Khôi phục thất bại ! Không thể khôi phục bài viết của người khác !'
                ]);
            }

            $post->restore();

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

            $post = $this->model->withTrashed()->findOrFail($id);
            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return redirect()->back()->withErrors([
                    'my-error' => 'Xóa thất bại ! Không thể xóa bài viết của người khác !'
                ]);
            }
            $images = $post->images;

            if (count($images) > 0) {

                $dir = '';
                foreach ($images as $key => $image) {
                    $dir = $image->folder;
                    $url = $image->url;
                    if (file_exists($dir . $url)) {
                        unlink($dir . $url);
                    }
                }

                if ($post->thumb_image != '') {
                    if (file_exists(config('app.image_post_thumb_direction') . $post->thumb_image)) {
                        unlink(config('app.image_post_thumb_direction') . $post->thumb_image);
                    }
                }

                $post->forceDelete();
            } else {
                return redirect()->back()->withErrors([
                    'my-error' => 'Xóa thất bại ! Lỗi không xác định.'
                ]);
            }

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

            $post = $this->model->findOrFail($arr['id']);

            // dd(gettype($post->administrator_id));
            // User role, id is integer variable and AdminRoleEnum::MASTER, $post->administrator_id is integer
            if ($user->role !== AdminRoleEnum::MASTER && $post->administrator_id != $user->id) {
                return $this->errorResponse('Thay đổi trạng thái thất bại ! Không thể thay đổi bài viết của người khác !');
            }

            if ($arr['status'] != 0 && $arr['status'] != 1) {
                return $this->errorResponse('Thay đổi trạng thái thất bại ! Trạng thái không hợp lệ !');
            }

            $post->active = $arr['status'];
            $post->update();

            return $this->successResponse('Thay đổi trạng thái thành công !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Thay đổi trạng thái thất bại ! Lỗi không xác định !');
        }
    }

    public function refreshPost(Request $request)
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

            $post = $this->model->findOrFail($arr['id']);

            $time = 3600000;
            $time_now = round(microtime(true) * 1000);
            $publish_date = strtotime($post->published_at) * 1000;
            if ($time_now - $publish_date > $time) {
                $post->published_at = now();
                $post->save();
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
