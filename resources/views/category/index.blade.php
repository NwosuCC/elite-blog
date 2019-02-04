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
                                    <a href="#" onclick="MF.create()" class="nav-link p-0">
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
                                    <th class="text-center">Posts</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $i => $category)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $category->name  }}</td>
                                        <td>{{ $category->description  }}</td>
                                        <td class="text-center">
                                          <a class="nav-link d-inline-block p-0" href="{{ route('post.category', ['category' => $category->slug]) }}">
                                            {{ __($category->posts()->published()->count()) }}
                                          </a>
                                        </td>
                                        <td class="px-0 text-center">
                                            <i class="fa fa-edit py-0 px-2 btn btn-light" onclick="MF.edit({{$category->formValues}})"></i>
                                            <i class="fa fa-trash py-0 px-2 btn btn-light" onclick="MF.trash({{$category->formValues}})"></i>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{-- Script function edit() --}}
                            <script>
                              let MF = {};
                              setTimeout(() => {
                                MF = ModalForm.init({
                                  name: 'Category',
                                  fields: ['name', 'description'],
                                  titleField: 'name',
                                  actions: {
                                    create: [
                                      'categoryForm', 'addCategoryModal', '{{route("category.store")}}'
                                    ],
                                    edit: [
                                      'categoryForm', 'addCategoryModal', '{{route("category.update", ['category' => 'category-route-key'])}}'
                                    ],
                                    trash: [
                                      'deleteForm', 'deleteModal', '{{route("category.delete", ['category' => 'category-route-key'])}}'
                                    ]
                                  },
                                  deleteInfo: 'All Posts under this category will also be deleted',
                                });
                              }, 300);
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
