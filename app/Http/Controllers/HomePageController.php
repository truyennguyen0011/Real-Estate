<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HomePageController extends Controller
{
    use ResponseTrait;

    private object $postModel;
    private object $newsModel;
    private object $categoryModel;
    private object $categories;

    public function __construct()
    {
        $this->postModel = Post::query();
        $this->newsModel = News::query();
        $this->categoryModel = Category::query();
        // Get only active category
        $this->categories = $this->categoryModel->active()->get();
    }

    public function index(Request $request)
    {
        try {
            $user = getUserSession($request);

            // navbar tranparent
            $navTransparent = 'navbar-transparent';
            $navColorScrollOnTop = 'navbar-color-on-scroll';

            // Get 12 latest posts 
            $posts = $this->postModel
                ->active()
                ->with('images')
                ->orderBy('published_at', 'DESC')
                ->paginate(12);

            if ($user != '') {
                return view('front_page.homepage', [
                    'user' => $user->id,
                    'categories' => $this->categories,
                    'posts' => $posts,
                    'navTransparent' => $navTransparent,
                    'navColorScrollOnTop' => $navColorScrollOnTop,
                ]);
            }

            return view('front_page.homepage', [
                'categories' => $this->categories,
                'posts' => $posts,
                'navTransparent' => $navTransparent,
                'navColorScrollOnTop' => $navColorScrollOnTop,
            ]);
        } catch (\Throwable $th) {
        }
    }

    function category(Request $request)
    {
        try {
            $user = getUserSession($request);

            $path = $request->getPathInfo();
            $slug = trim($path, "/");

            if ($slug != '') {
                $category = $this->categoryModel
                    ->active()
                    ->where('slug', $slug)
                    ->first();

                $category_id = $category->id;
                $current_category = $category->title;

                $posts = $this->postModel
                    ->active()
                    ->where('category_id', $category_id)
                    ->orderBy('published_at', 'DESC')
                    ->paginate(20);

                if ($user != '') {
                    return view('front_page.category', [
                        'user' => $user->id,
                        'categories' => $this->categories,
                        'current_category' => $current_category,
                        'posts' => $posts,
                    ]);
                }
                return view('front_page.category', [
                    'categories' => $this->categories,
                    'current_category' => $current_category,
                    'posts' => $posts,
                ]);
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function post(Request $request, $slug)
    {
        try {
            $user = getUserSession($request);
            if (trim($slug) != '') {
                $post = $this->postModel->clone()
                    ->active()
                    ->where('slug', $slug)
                    ->first();

                $idPost = $post->id;
                $categoryPost = $post->category_id;

                if ($categoryPost != '') {
                    $relatedPosts = $this->postModel
                        ->clone()
                        ->active()
                        ->where('id', '!=',  $idPost)
                        ->where('category_id', $categoryPost)
                        ->take(8)->get();
                }

                if ($post != null) {
                    if (count($relatedPosts) > 0) {
                        $num = (int) ceil(count($relatedPosts) / 2);
                        if ($num > 4) {
                            $num = 4;
                        }
                        if ($user != '') {
                            return view('front_page.post', [
                                'user' => $user->id,
                                'post' => $post,
                                'categories' => $this->categories,
                                'relatedPosts' => $relatedPosts,
                                'num' => $num,
                            ]);
                        }
                        return view('front_page.post', [
                            'post' => $post,
                            'categories' => $this->categories,
                            'relatedPosts' => $relatedPosts,
                            'num' => $num,
                        ]);
                    } else {
                        if ($user != '') {
                            return view('front_page.post', [
                                'user' => $user->id,
                                'post' => $post,
                                'categories' => $this->categories,
                                'relatedPosts' => $relatedPosts,
                                'num' => 0,
                            ]);
                        }
                        return view('front_page.post', [
                            'post' => $post,
                            'categories' => $this->categories,
                            'relatedPosts' => $relatedPosts,
                            'num' => 0,
                        ]);
                    }
                } else {
                    abort(404);
                }
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function search(Request $request)
    {
        try {
            $q = trim($request->q);
            $user = getUserSession($request);

            if ($q != '') {
                $posts = Post::search($q)
                    ->paginate(20);

                if ($user != '') {
                    return view('front_page.search', [
                        'user' => $user->id,
                        'categories' => $this->categories,
                        'posts' => $posts,
                        'q' => $q,
                    ]);
                }
                return view('front_page.search', [
                    'categories' => $this->categories,
                    'posts' => $posts,
                    'q' => $q,
                ]);
            } else {
                $posts = $this->postModel
                    ->active()
                    ->orderBy('published_at', 'desc')
                    ->paginate(20);
                if ($user != '') {
                    return view('front_page.search', [
                        'user' => $user->id,
                        'categories' => $this->categories,
                        'posts' => $posts,
                        'q' => $q,
                    ]);
                }
                return view('front_page.search', [
                    'categories' => $this->categories,
                    'posts' => $posts,
                    'q' => $q,
                ]);
            }
        } catch (\Throwable $th) {
            abort(500);
        }
    }

    // News
    function news(Request $request)
    {
        try {
            $user = getUserSession($request);

            $news = $this->newsModel
                ->active()
                ->orderBy('published_at', 'DESC')
                ->paginate(12);

            if ($user != '') {
                return view('front_page.news', [
                    'user' => $user->id,
                    'categories' => $this->categories,
                    'news' => $news,
                ]);
            }

            return view('front_page.news', [
                'categories' => $this->categories,
                'news' => $news,
            ]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    function newDetail(Request $request, $slug)
    {
        try {
            $user = getUserSession($request);
            if (trim($slug) != '') {
                $new = $this->newsModel->clone()
                    ->active()
                    ->where('slug', $slug)
                    ->first();

                if ($new != null) {
                    if ($user != '') {
                        return view('front_page.new-detail', [
                            'user' => $user->id,
                            'new' => $new,
                            'categories' => $this->categories,
                        ]);
                    }
                    return view('front_page.new-detail', [
                        'new' => $new,
                        'categories' => $this->categories,
                    ]);
                } else {
                    abort(404);
                }
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
