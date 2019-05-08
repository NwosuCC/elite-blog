<?php

namespace App\Http\Controllers;

use App\Events\PostSaved;
use App\Http\Requests\PostRequest;
use App\User;
use App\Category;
use App\Post;

use Carbon\Carbon;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except([
            'index', 'index_author', 'index_category', 'show'
        ]);
    }


    public function index(User $user = null, Category $category = null)
    {
        $Post = Post::instance();

        $posts = $user ? $user->posts() : $Post;

        $posts = $posts->published()->in($category)->get();

        $categories = Category::latest()->get();

        return view('post.index', compact('Post', 'posts', 'categories', 'user', 'category'));
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

        return view('post.create', ['Post' => Post::instance(), 'categories' => $categories]);
    }


    public function store(PostRequest $request) {
        $category = Category::find( $request->input(['category']) );

        $post = Post::instance()->fill([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'slug' => str_slug( $request->input('title') ),
            'published_at' => $request->input('publish_at') ?: Carbon::now(),
        ]);

        auth()->user()->publishPost( $category, $post );

        set_flash('New article saved');

        return redirect()->route('post.index');
    }


    public function show(Post $post) {
        if( ! $post->published() ) {
            abort(404, 'Article not found');
        }

        return view('post.show', compact('post'));
    }


    public function edit(Post $post) {
        if(auth()->user()->cant('update', $post)) {
            // ToDo: customize error messages class
            set_flash('You are not authorized to perform this action', 'danger');
            return redirect()->back();
        }

        $categories = Category::latest()->get();

        return view('post.edit', compact('categories', 'post'));
    }


    public function update(PostRequest $request, Post $post) {
        $this->authorize('update', $post);

        $category = Category::find($request->input('category'));

        $post->fill([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'slug' => str_slug( $request->input('title') ),
        ]);

        auth()->user()->publishPost( $category, $post );

        event(new PostSaved($post));

        [$message, $type] = $post->getChanges() ? ['Article updated', ''] : ['No changes made', 'info'];
        set_flash($message, $type);

        return redirect()->route('post.show', ['post' => $post ]);
    }


    public function destroy(PostRequest $request, Post $post) {
        $this->authorize('delete', $post);

        $post->delete();

        set_flash('Article deleted');

        return redirect()->route('post.index');
    }

}
