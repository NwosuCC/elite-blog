@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row no-gutters align-items-center">
                            <div class="">
                                <h2>{{ __('Categories')  }}</h2>
                            </div>
                            <div class="ml-auto">
                                <small>
                                    <a href="#" onclick="create()" class="nav-link p-0">
                                        {{ __('Add a Category') }}
                                    </a>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

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
                                @foreach($categories as $i => $category)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $category->name  }}</td>
                                        <td>{{ $category->description  }}</td>
                                        <td class="px-0 text-center">
                                            <i class="fa fa-edit mx-md-2 py-0 px-2 btn btn-light" onclick="edit('{{$category->formValues}}')"></i>
                                            <i class="fa fa-trash mx-md-2 py-0 px-2 btn btn-light" onclick="trash('{{$category->formValues}}')"></i>
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
                                        Form = $('#categoryForm');
                                        DeleteForm = $('#deleteForm');
                                    } catch(error){
                                        // ToDo: handle "JQuery not (yet) loaded" error
                                    }
                                }, 300);

                                const showModal = function(labels, del = false){
                                    let TargetForm = del === true ? DeleteForm : Form;
                                    TargetForm.find('input[name="_method"]').val(labels.method);
                                    TargetForm.attr( 'action', labels.action );

                                    Modal = del === true ? $('#deleteCategoryModal') : $('#addCategoryModal');
                                    Modal.find('.modal-title').text(labels.title);
                                    Modal.find('.modal-footer').find('button:submit').text(labels.button);

                                    Modal.modal('show');
                                };

                                const create = function(){
                                    Form.find('input,textarea').not('[type="hidden"]').val('');
                                    showModal({
                                        title: 'Create A Category', button: 'Create Category',
                                        method: 'POST', action:'{{route("category.store")}}'
                                    });
                                };

                                const edit = function(category){
                                    category = parseToJSON(category);

                                    Form.find('#name').val( category.name );
                                    Form.find('#description').val( category.description );
                                    showModal({
                                        title: 'Edit Category', button: 'Update Category', method: 'PUT',
                                        action:'{{route("category.update", ['category' => ''])}}/' + category.slug,
                                    });
                                };

                                const trash = function(category){
                                    category = parseToJSON(category);

                                    DeleteForm.find('#prompt').text(category.name);
                                    showModal({
                                        title: 'Delete Category', button: 'Delete Category', method: 'DELETE',
                                        action:'{{route("category.delete", ['category' => ''])}}/' + category.slug,
                                    }, true);
                                };
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Add/Edit Modal --}}
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
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

          <form id="categoryForm" method="POST" action="{{ route('category.store') }}">
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
    <div class="modal fade" id="deleteCategoryModal" tabindex="-2" role="dialog" aria-hidden="true">
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
                      <div>All Posts under this category will also be deleted</div>
                      <div>Delete category "<b id="prompt"></b>" ?</div>
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
