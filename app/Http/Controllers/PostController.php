<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\User;
use App\Category;
use App\Post;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except([
            'index', 'index_author', 'index_category', 'show'
        ]);
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
        switch (true) {
            case ($user and $category) : {
                $posts = $user->posts()->filter($category); break;
            }
            case ($user) : {
                $posts = $user->posts(); break;
            }
            case ($category) : {
                $posts = $category->posts(); break;
            }
            default : { $posts = Post::latest(); }
        }

        $posts = $posts->filter()->published()->get();

        $categories = Category::latest()->get();

        return view('post.index', compact('posts', 'categories', 'user', 'category'));
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


    public function store(PostRequest $request) {
        $category = Category::find( $request->input(['category']) );

        $post = (new Post())->fill([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'slug' => str_slug( $request->input('title') ),
            'published_at' => $request->input('publish_at') ?: Carbon::now(),
        ]);

        auth()->user()->publishPost( $category, $post );

        session()->flash('message', "New article saved");

        return redirect()->route('post.index');
    }


    public function show(Post $post) {
        if( ! $post->published() ) {
            throw new NotFoundHttpException();
        }

        return view('post.show', compact('post'));
    }


    public function edit(Post $post) {
        // ToDo: use Gates or Authorize to implement this check
        if( ! $post->author() ) {
            return redirect()->back();
        }

        $categories = Category::latest()->get();

        return view('post.edit', compact('categories', 'post'));
    }


    public function update(PostRequest $request, Post $post) {
        // ToDo: use Gates (instead of Authorize) to implement this check

        $category = Category::find($request->input('category'));

        $post->fill([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'slug' => str_slug( $request->input('title') ),
        ]);

        auth()->user()->publishPost( $category, $post );

        session()->flash('message', "Article updated");

        return redirect()->route('post.show', ['post' => $post ]);
    }


    public function destroy(PostRequest $request, Post $post) {
        // ToDo: add 'delete' action button in post.index-author view

        $post->delete();

//        dd( Post::onlyTrashed()->restore() );

        session()->flash('message', "Article deleted");

        return redirect()->route('post.index');
    }

}
