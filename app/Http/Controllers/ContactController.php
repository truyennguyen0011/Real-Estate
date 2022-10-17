<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    private object $categoryModel;
    private object $categories;

    public function __construct()
    {
        $this->categoryModel = Category::query();
        // Get only active category
        $this->categories = $this->categoryModel->active()->get();
    }

    function contact(Request $request)
    {
        $user = getUserSession($request);
        // navbar tranparent
        $navTransparent = 'navbar-transparent';
        $navColorScrollOnTop = 'navbar-color-on-scroll';

        if ($user != '') {
            return view('front_page.contact', [
                'user' => $user->id,
                'categories' => $this->categories,
                'navTransparent' => $navTransparent,
                'navColorScrollOnTop' => $navColorScrollOnTop,
            ]);
        }
        return view('front_page.contact', [
            'categories' => $this->categories,
            'navTransparent' => $navTransparent,
            'navColorScrollOnTop' => $navColorScrollOnTop,
        ]);
    }

    function sendContact(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'phone' => 'required',
                'name' => 'required',
                'message' => 'required',
            ]);

            $data = [
                'phone' => $request->phone,
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message
            ];
            Mail::to('nguyentruyen28101999@gmail.com')->send(new ContactMail($data));

            return back()->with(['send_success' => 'Xin cảm ơn, form đã được gửi thành công.']);
        } catch (\Throwable $th) {
        }
    }
}
