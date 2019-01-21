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

        auth()->user()->createCategory( new Category($data) );

        session()->flash('message', "New category saved");

        return redirect()->route('category.index');
    }

    public function update(Request $request, Category $category)
    {
        // ToDO: Update a Category? How about the Bookmarks using the former slug?? Any backwards compatibility???

        if( ! auth()->user()->isAdmin() ) {

            session()->flash('message', Category::error(Category::ERROR_NO_MODIFY));

            return redirect()->back();
        }

        $this->validate(request(), [
            'name' => 'required|min:3|max:30',
            'description'  => 'required'
        ]);

        $category->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => str_slug( $request->input('name') ),
        ]);

        session()->flash('message', "Category updated");

        return redirect()->route('category.index');
    }


    public function destroy(Category $category)
    {
        // ToDO: Delete a Category? What happens to the Posts under it?? (See PostController@index)
        $category->delete();

        session()->flash('message', "Category deleted");

        return redirect()->route('category.index');
    }
}
