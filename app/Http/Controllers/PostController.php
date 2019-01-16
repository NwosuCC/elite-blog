<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Post;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(User $user = null) {
        $posts = Post::all();

        // $user

        return view('post.index', compact('posts'));
    }

    public function show(Post $post) {
        return view('post.show', compact('post'));
    }

    public function create() {
//        $categories = Category::all();
        $categories = [];

        return view('post.create', compact('categories'));
    }

    public function store(Request $request) {
        //

        session()->flash('message', "New article saved");

        return redirect( route('post.index') );
    }

    public function edit(Post $post) {
//        $categories = Category::all();
        $categories = [];

        return view('post.edit', compact('categories', 'post'));
    }

    public function update(Request $request, $post) {
        //

        session()->flash('message', "Article updated");

        return redirect( route('post.show', ['post' => $post ]) );
    }

    public function destroy(Post $post) {
        //

        session()->flash('message', "Article deleted");

        return redirect( route('post.index') );
    }

}
