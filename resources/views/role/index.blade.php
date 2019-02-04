@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row no-gutters align-items-center">
                            <div class="">
                                <h2>{{ __('Roles')  }}</h2>
                            </div>
                            <div class="ml-auto">
                                <small>
                                  @can('create', 'App\Role')
                                    <a href="javascript:" onclick="MF.create()" class="nav-link p-0">
                                        {{ __('Add a Role') }}
                                    </a>
                                  @endcan
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Rank</th>
                                    <th>Description</th>
                                    <th class="text-center">Holders</th>
                                    @can('create', 'App\Role')
                                      <th class="text-center">Actions</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $i => $role)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $role->name  }}</td>
                                        <td>{{ $role->rank  }}</td>
                                        <td>{{ $role->description  }}</td>
                                        <td class="text-center">
                                          {{ __($role->users()->count()) }}
                                        </td>
                                        @can('update', $role)
                                          <td class="px-0 text-center">
                                            <i class="fa fa-edit mx-md-2 py-0 px-2 btn btn-light" onclick="MF.edit({{$role->formValues}})"></i>

                                            @can('delete', $role)
                                              <i class="fa fa-trash mx-md-2 py-0 px-2 btn btn-light" onclick="MF.trash({{$role->formValues}})"></i>
                                            @else
                                              <i class="disabled fa fa-trash mx-md-2 py-0 px-2 btn btn-light" style="color: grey"></i>
                                            @endcan
                                          </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- Modal Script functions --}}
                            <script>
                              let MF = {};
                              setTimeout(() => {
                                MF = ModalForm.init({
                                  name: 'Role',
                                  fields: ['name', 'description'],
                                  titleField: 'name',
                                  actions: {
                                    create: [
                                      'addRoleForm', 'addRoleModal', '{{route("role.store")}}'
                                    ],
                                    edit: [
                                      'addRoleForm', 'addRoleModal', '{{route("role.update", ['role' => 'role-route-key'])}}'
                                    ],
                                    trash: [
                                      'deleteForm', 'deleteModal', '{{route("role.delete", ['role' => 'role-route-key'])}}'
                                    ]
                                  },
                                  deleteInfo: 'All Users having this role will also be deactivated',
                                });
                              }, 300);
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Add/Edit Modal --}}
    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
            <h5 class="modal-title py-1 px-3" style="color: #123466" id="exampleModalLabel">
              {{-- Title --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
            </button>
          </div>

          <form id="addRoleForm" method="POST" action="{{ route('role.store') }}">
            @csrf
              {{ method_field('PUT') }}

              <div class="modal-body">
                <div class="py-0 px-5">
                  <div class="form-group row">
                    <label for="name">{{ __('Name') }}</label>
                    <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                    <span class="invalid-feedback{{ $errors->has('name') ? '' : ' d-none' }}" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  </div>

                  <div class="form-group row">
                    <label for="description">{{ __('Description') }}</label>
                    <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" required>{{ old('description') }}</textarea>

                    <span class="invalid-feedback{{ $errors->has('description') ? '' : ' d-none' }}" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                  </div>
                </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-metal" data-dismiss="modal">
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                {{-- Action --}}
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>

    {{-- Delete Modal --}}
    @include('snippets.delete-modal')

    </div>
@endsection
