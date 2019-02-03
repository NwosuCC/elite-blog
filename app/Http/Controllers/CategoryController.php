<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }


    protected function validator(array $data, $category = null)
    {
        return Validator::make($data, [
            'name' => [
                'required', 'min:3', 'max:63',
                RUle::unique('categories')->ignore($category ? $category->id : '')
            ],
            'description'  => ['required', 'min:3']
        ])->validate();
    }


    public function index()
    {
        $categories = Category::latest()->get();

        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validator($request->all());

        $data = $request->only(['name', 'description']);
        $data['slug'] = str_slug( $data['name'] );

        auth()->user()->createCategory( new Category($data) );

        set_flash("New category saved");

        return redirect()->route('category.index');
    }

    public function update(Request $request, Category $category)
    {
        $this->validator($request->all(), $category);

        // ToDO: Update a Category? How about the Bookmarks using the former slug?? Any backwards compatibility???
        $category->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => str_slug( $request->input('name') ),
        ]);

        set_flash("Category updated");

        return redirect()->route('category.index');
    }


    public function destroy(Category $category)
    {
        // ToDO: Delete a Category? What happens to the Posts under it?? (See PostController@index)
        $category->delete();

        set_flash("Category deleted");

        return redirect()->route('category.index');
    }
}
