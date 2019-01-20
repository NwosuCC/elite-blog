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
                                    <a href="#" data-toggle="modal" data-target="#addCategoryModal" class="nav-link p-0">
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
                                    <th>Name</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->name  }}</td>
                                        <td>{{ $category->description  }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #f7f8fa;border-color: #f7f8fa">
            <h5 class="modal-title" style="color: #123466" id="exampleModalLabel">
              Create A Category
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                  &times;
              </span>
            </button>
          </div>

          <form method="POST" action="{{ route('category.store') }}">
            @csrf

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
                Create Category
              </button>
            </div>

          </form>

        </div>
      </div>
    </div>

    </div>
@endsection
