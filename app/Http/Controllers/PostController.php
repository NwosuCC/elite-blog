<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'index_author', 'show']);
    }

//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:6', 'confirmed'],
//        ]);
//    }


    public function index(User $user = null, Category $category = null)
    {
        $user = null;
        $category = null;
//        dd($user);
        switch (true) {
            case ($user and $category) : {
                $posts = $user->posts()->belongsTo($category);  break;
            }
            case ($user) : {
                $posts = $user->posts();  break;
            }
            case ($category) : {
                $posts = $category->posts(); break;
            }
            default : { $posts = Post::latest(); }
        }

        $posts = $posts->get();
//        dd($posts);

        return view('post.index', compact('posts'));
    }


    public function index_author(User $user)
    {
        return $this->index($user);
    }


    public function index_category(Category $category)
    {
        return $this->index(null, $category);
    }


    public function create() {
        $categories = Category::latest()->get();

        return view('post.create', compact('categories'));
    }


    public function store(Request $request) {
        $this->validate(request(), [
            'category' => 'required',
            'title' => 'required|unique:posts|min:3|max:30',
            'body'  => 'required'
        ]);

        if($category = Category::find($request->input('category')) ) {
            $data = $request->only(['title', 'body']);
            $data['slug'] = str_slug( $data['title'] );

            auth()->user()->publishPost( $category, new Post($data) );

            session()->flash('message', "New article saved");

            return redirect()->route('post.index');
        }

        session()->flash('message', "Article not created!");

        return redirect()->back();
    }


    public function show(Post $post) {
//        dd( is_a($post, Post::class));  // true
//        dd( is_subclass_of($post, Model::class));  // true
//        dd( is_subclass_of(Post::class, Model::class));  // true

        return view('post.show', compact('post'));
    }


    public function edit(Post $post) {
        // ToDo: use Gates or Authorize to implement this check
        if( ! $post->author() ) {
            session()->flash('message', Post::error(Post::ERROR_NO_MODIFY, Post::class));

            return redirect()->back();
        }

        $categories = Category::latest()->get();

        return view('post.edit', compact('categories', 'post'));
    }


    public function update(Request $request, Post $post) {
        // ToDo: use Gates or Authorize to implement this check
        if( ! $post->author() ) {
            session()->flash('message', Post::error(Post::ERROR_NO_MODIFY, Post::class));

            return redirect()->back();
        }

        $this->validate(request(), [
            'title' => 'required|unique:posts|min:3|max:30',
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
