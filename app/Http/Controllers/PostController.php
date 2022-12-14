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
            return redirect()->route("admin.$this->table.index")->with('my-success', 'T???o b??i ????ng th??nh c??ng!');
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
                'my-error' => 'T???o b??i ????ng th???t b???i!' . $e
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
                    'my-error' => 'S???a th???t b???i ! Kh??ng th??? s???a b??i vi???t c???a ng?????i kh??c !'
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
                'my-error' => 'S???a th???t b???i ! L???i kh??ng x??c ?????nh.'
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
                    'my-error' => 'S???a th???t b???i ! Kh??ng th??? s???a b??i vi???t c???a ng?????i kh??c !'
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
            return redirect()->route("admin.$this->table.index")->with('my-success', 'S???a b??i ????ng th??nh c??ng!');
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
                'my-error' => 'S???a b??i ????ng th???t b???i!' . $e
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
                    'my-error' => 'X??a th???t b???i ! Kh??ng th??? x??a b??i vi???t c???a ng?????i kh??c !'
                ]);
            }

            if ($post->active == 1) {
                $post->active = 0;
                $post->save();
            }
            $post->delete();
            return redirect()->back()->with('my-success', 'X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'X??a th???t b???i ! L???i kh??ng x??c ?????nh.'
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
                    'id.required' => 'ID kh??ng ???????c ????? tr???ng !',
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
                    'id.required' => 'ID kh??ng ???????c ????? tr???ng !',
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
                    'my-error' => 'Kh??i ph???c th???t b???i ! Kh??ng th??? kh??i ph???c b??i vi???t c???a ng?????i kh??c !'
                ]);
            }

            $post->restore();

            return redirect()->back()->with('my-success', 'Kh??i ph???c th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Kh??i ph???c th???t b???i ! L???i kh??ng x??c ?????nh.'
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

            return redirect()->back()->with('my-success', 'Kh??i ph???c t???t c??? th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'Kh??i ph???c th???t b???i ! L???i kh??ng x??c ?????nh.'
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
                    'my-error' => 'X??a th???t b???i ! Kh??ng th??? x??a b??i vi???t c???a ng?????i kh??c !'
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
                    'my-error' => 'X??a th???t b???i ! L???i kh??ng x??c ?????nh.'
                ]);
            }

            return redirect()->back()->with('my-success', 'X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'X??a th???t b???i ! L???i kh??ng x??c ?????nh.'
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
                return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! Kh??ng th??? thay ?????i b??i vi???t c???a ng?????i kh??c !');
            }

            if ($arr['status'] != 0 && $arr['status'] != 1) {
                return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! Tr???ng th??i kh??ng h???p l??? !');
            }

            $post->active = $arr['status'];
            $post->update();

            return $this->successResponse('Thay ?????i tr???ng th??i th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! L???i kh??ng x??c ?????nh !');
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
                    'id.required' => 'Id kh??ng ???????c ????? tr???ng!',
                    'id.numeric' => 'Id ph???i l?? s???!',
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
                return $this->errorResponse('L??m m???i sau: ' . (int) round($min) . ' ph??t !', 'warning');
            }

            return $this->successResponse('L??m m???i th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('L??m m???i th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }
}
