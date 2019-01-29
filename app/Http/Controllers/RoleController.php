<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }


    public function index()
    {
        $roles = Role::all();

        return view('role.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|unique:roles|min:3|max:30',
            'description'  => 'required'
        ]);

        $data = $request->only(['name', 'description']);
        $data['slug'] = str_slug( $data['name'] );

        auth()->user()->createRole( new Role($data) );

        set_flash("New role created");

        return redirect()->route('role.index');
    }

    public function update(Request $request, Role $role)
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:30',
            'description'  => 'required'
        ]);

        // ToDO: Update a Role? How about the Bookmarks using the former slug?? Any backwards compatibility???
        $role->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'slug' => str_slug( $request->input('name') ),
        ]);

        set_flash("Role updated");

        return redirect()->route('role.index');
    }


    public function destroy(Role $role)
    {
        if( $role->isDefault ){
            set_flash('Default roles cannot be modified', 'info');

            return redirect()->back();
        }

        // ToDO: Delete a CUSTOM Role? What happens to the Holders??
        $role->delete();

        set_flash("Role deleted");

        return redirect()->route('role.index');
    }
}
