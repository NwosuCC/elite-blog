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


    public function index() {
        $posts = Post::latest()->get();

        return view('post.index', compact('posts'));
    }


    public function index_author(User $user) {
        $posts = $user->posts()->get();

        return view('post.index-author', compact('posts'));
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
        $this->validate(request(), [
            'title' => 'required | min:3 | max:30',
            'body'  => 'required'
        ]);

        $data = $request->only(['title', 'body']);
        $data['slug'] = str_slug( $data['title'] );

        auth()->user()->publishPost( new Post($data) );

        session()->flash('message', "New article saved");

        return redirect()->route('post.index');
    }


    public function edit(Post $post) {
        if( $post->author() ) {
//            $categories = Category::all();
            $categories = [];

            return view('post.edit', compact('categories', 'post'));
        }

        session()->flash('message', Post::error(Post::ERROR_NO_MODIFY));

        return redirect()->back();
    }


    public function update(Request $request, Post $post) {
        $this->validate(request(), [
            'title' => 'required | min:3 | max:30',
            'body'  => 'required'
        ]);

        $post->update([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'slug' => str_slug( $request->input('title') ),
        ]);

        session()->flash('message', "Article updated");

        return redirect()->route('post.show', ['post' => $post ]);
    }


    public function destroy(Post $post) {
        // ToDo: add 'deleted' column to migration
        // ToDo: add 'delete' action button in post.index-author view
        $post->update([ 'deleted' => 1 ]);

        auth()->user()->posts()->save($post);

        session()->flash('message', "Article deleted");

        return redirect()->route('post.index');
    }

}
