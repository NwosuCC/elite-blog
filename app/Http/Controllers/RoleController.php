<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

  public function __construct()
  {
    $this->middleware(['auth', 'admin']);
    $this->middleware([Role::str('SuperAdmin')])->except(['index']);
  }

  protected function validator(array $data, $role = null)
  {
    return Validator::make($data, [
      'name' => [
        'required', 'min:3', 'max:63',
        RUle::unique('roles')->whereNull('deleted_at')->ignore($role ? $role->id : '')
      ],
      'description'  => ['required', 'min:3']
    ])->validate();

    /*if($validator->fails()) {
//            return redirect( url()->previous() )->withErrors($validator)->withInput();
        return redirect( url()->previous() )->withErrors($validator, 'create')->withInput();
    }*/
  }


  public function index()
  {
    $roles = Role::ranked()->withCount(['users'])->get();

    $M_Role = new Role;

    return view('role.index', compact('M_Role', 'roles'));
  }

  public function store(Request $request)
  {
    $this->validator($request->all());

    $data = [
      'name' => $request->input('name'),
      'description' => $request->input('description'),
      'slug' => str_slug( $request->input('name') ),
    ];

    auth()->user()->createRole( new Role($data) );

    set_flash("New role created");

    return redirect()->route('role.index');
  }

  public function update(Request $request, Role $role)
  {
    $this->validator($request->all(), $role);

    // ToDO: Update a Role? How about the Bookmarks using the former slug?? Any backwards compatibility???
    $role->update([
      'name' => $request->input('name'),
      'description' => $request->input('description'),
      'slug' => str_slug( $request->input('name') ),
    ]);

    set_flash("Role updated");

    return redirect()->route('role.index');
  }


  public function destroy(Request $request, Role $role)
  {
    if($request->user()->cant('delete', $role)) {
      set_flash('Default roles cannot be modified', 'info');
      return redirect()->back();
    }

    // ToDO: Delete a CUSTOM Role? What happens to the Holders??
    $role->delete();

    set_flash("Role deleted");

    return redirect()->route('role.index');
  }
}
