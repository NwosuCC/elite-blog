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
                                    <a href="#" onclick="create()" class="nav-link p-0">
                                        {{ __('Add a Role') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $i => $role)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $role->name  }}</td>
                                        <td>{{ $role->description  }}</td>
                                        <td class="px-0 text-center">
                                            <i class="fa fa-edit mx-md-2 py-0 px-2 btn btn-light" onclick="edit('{{$role->formValues}}')"></i>
                                            <i class="fa fa-trash mx-md-2 py-0 px-2 btn btn-light" onclick="trash('{{$role->formValues}}')"></i>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- Script function edit() --}}
                            <script>
                                let Modal, Form, DeleteForm;
                                setTimeout(() => {
                                    try{
                                        Form = $('#roleForm');
                                        DeleteForm = $('#deleteForm');
                                    } catch(error){
                                        // ToDo: handle "JQuery not (yet) loaded" error
                                    }
                                }, 300);

                                const showModal = function(labels, del = false){
                                    let TargetForm = del === true ? DeleteForm : Form;
                                    TargetForm.find('input[name="_method"]').val(labels.method);
                                    TargetForm.attr( 'action', labels.action );

                                    Modal = del === true ? $('#deleteRoleModal') : $('#addRoleModal');
                                    Modal.find('.modal-title').text(labels.title);
                                    Modal.find('.modal-footer').find('button:submit').text(labels.button);

                                    Modal.modal('show');
                                };

                                const create = function(){
                                    Form.find('input,textarea').not('[type="hidden"]').val('');
                                    showModal({
                                        title: 'Create A Role', button: 'Create Role',
                                        method: 'POST', action:'{{route("role.store")}}'
                                    });
                                };

                                const edit = function(role){
                                    role = parseToJSON(role);

                                    Form.find('#name').val( role.name );
                                    Form.find('#description').val( role.description );
                                    showModal({
                                        title: 'Edit Role', button: 'Update Role', method: 'PUT',
                                        action:'{{route("role.update", ['role' => ''])}}/' + role.slug,
                                    });
                                };

                                const trash = function(role){
                                    role = parseToJSON(role);

                                    DeleteForm.find('#prompt').text(role.name);
                                    showModal({
                                        title: 'Delete Role', button: 'Delete Role', method: 'DELETE',
                                        action:'{{route("role.delete", ['role' => ''])}}/' + role.slug,
                                    }, true);
                                };
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

          <form id="roleForm" method="POST" action="{{ route('role.store') }}">
            @csrf
              {{ method_field('PUT') }}

              <div class="modal-body">

              <div class="py-0 px-5">
                <div class="form-group row">
                  <label for="name">{{ __('Name') }}</label>
                  <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="form-group row">
                  <label for="description">{{ __('Description') }}</label>
                  <textarea id="description" class="form-control" name="description" required>{{ old('description') }}</textarea>
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
    <div class="modal fade" id="deleteRoleModal" tabindex="-2" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
            <h5 class="modal-title py-1 px-3" style="color: #123466">
              {{-- Title --}}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
            </button>
          </div>

          <form id="deleteForm" method="POST" action="">
            @csrf
              {{ method_field('DELETE') }}

              <div class="modal-body">

              <div class="py-0 px-5">
                <div class="form-group row">
                  <div class="col-1 pl-0 row">
                      <i class="fa fa-exclamation-triangle fa-2x align-self-center" style="color: indianred;"></i>
                  </div>
                  <div class="col-11 pl-4">
                      <div>All Posts under this role will also be deleted</div>
                      <div>Delete role "<b id="prompt"></b>" ?</div>
                  </div>
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

    </div>
@endsection
