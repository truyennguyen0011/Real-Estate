<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Requests\CheckActiveRequest;
use App\Http\Requests\Slug\CheckSlugCategoryRequest;
use App\Http\Requests\Slug\GenerateSlugRequest;
use App\Models\Category;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Throwable;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    use ResponseTrait;

    private object $model;
    private string $table;
    private array $configs;
    private string $dir;

    public function __construct()
    {
        $this->model = Category::query();
        $this->table = (new Category())->getTable();
        $this->configs = SystemConfigController::getAndCache();

        View::share('title', ucwords('Categories'));
        View::share('table', $this->table);
        View::share('roles', $this->configs['roles']);
    }

    public function index(Request $request)
    {
        $user = getUserSession($request);

        return view("admin.$this->table.index", [
            'user' => $user,
        ]);
    }

    public function api()
    {
        return DataTables::of($this->model)
            ->editColumn('active', function ($object) {
                return $object;
            })
            ->editColumn('created_at', function ($object) {
                return $object->created_at_vn;
            })
            ->addColumn('action', function ($object) {
                $arr['routeEdit'] = route('admin.categories.edit', $object);
                $arr['routeDelete'] = route('admin.categories.destroy', $object);
                $arr['category'] = $object;
                return $arr;
            })
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $arr = $request->validated();

            $this->model->create($arr);

            DB::commit();

            return $this->successResponse('T???o danh m???c th??nh c??ng !');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse('T???o danh m???c th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request)
    {
        $user = getUserSession($request);

        $query = $this->model->onlyTrashed()
            ->orderBy('deleted_at', 'DESC');
        $data = $query->paginate();

        return view("admin.$this->table.trash", [
            'user' => $user,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            $arr = $request->validated();
            $id = $arr['id'];

            $title = $arr['title'];
            $slug = $arr['slug'];
            $category = $this->model->findOrFail($id);

            $category->update([
                'title' => $title,
                'slug' => $slug,
            ]);

            DB::commit();
            return $this->successResponse('S???a danh m???c th??nh c??ng !');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('S???a danh m???c th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = $this->model->findOrFail($id);

            if ($category->active == 1) {
                $category->active = 0;
                $category->save();
            }
            $category->delete();
            return $this->successResponse('X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('X??a th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }

    public function generateSlug(GenerateSlugRequest $request)
    {
        try {
            $title = $request->get('title');

            $slug  = SlugService::createSlug(Category::class, 'slug', $title);

            return $this->successResponse('T???o slug th??nh c??ng !', $slug);
        } catch (\Throwable $th) {
            return $this->errorResponse('T???o slug th???t b???i !');
        }
    }

    public function checkSlug(CheckSlugCategoryRequest $request)
    {
        try {
            return $this->successResponse('Slug h???p l??? !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Slug ???? t???n t???i !');
        }
    }

    // Change active
    public function changeActive(CheckActiveRequest $request)
    {
        try {

            // Validation request
            $arr = $request->validated();

            $category = $this->model->findOrFail($arr['id']);

            if ($arr['status'] != 0 && $arr['status'] != 1) {
                return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! Tr???ng th??i kh??ng h???p l??? !');
            }

            $category->active = $arr['status'];
            $category->save();

            return $this->successResponse('Thay ?????i tr???ng th??i th??nh c??ng !');
        } catch (\Throwable $th) {
            return $this->errorResponse('Thay ?????i tr???ng th??i th???t b???i ! L???i kh??ng x??c ?????nh !');
        }
    }

    //  Restore category
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

    //  Restore all categories
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
    public function force_delete($id)
    {
        try {
            $category = $this->model->withTrashed()->findOrFail($id);

            $category->forceDelete();

            return redirect()->back()->with('my-success', 'X??a th??nh c??ng !');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors([
                'my-error' => 'X??a th???t b???i ! L???i kh??ng x??c ?????nh.'
            ]);
        }
    }
}
