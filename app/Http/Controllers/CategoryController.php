<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $categories = Category::latest()->get();

        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        if( ! auth()->user()->isAdmin() ) {

            session()->flash('message', Category::error(Category::ERROR_NO_ACTION));

            return redirect()->back();
        }

        $this->validate(request(), [
            'name' => 'required|unique:categories|min:3|max:30',
            'description'  => 'required'
        ]);

        $data = $request->only(['name', 'description']);
        $data['slug'] = str_slug( $data['name'] );

        $aa = auth()->user()->createCategory( new Category($data) );

        session()->flash('message', "New category saved");

        return redirect()->route('category.index');
    }

    public function update(Request $request, Category $category)
    {
        //
    }


    public function destroy(Category $category)
    {
        //
    }
}
