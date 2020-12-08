<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Topic;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller {

    public function index(Request $request) {
        //    dd(Auth::user()->role);

        $user = Auth::user();


        $categories = Category::query()->get();
        $subcategories = Subcategory::query()->get();
        $topics = Topic::query()->get();
        $messages = Message::query()->get();
        $users = User::query()->get();



        return view('welcome',
                ['categories' => $categories,
                    'subcategories' => $subcategories,
                    'topics' => $topics,
                    'users' => $users,
                    'userName' => $user->name ?? null,
                    'userRole' => $user->role,
                    'messages' => $messages]);
    }

}