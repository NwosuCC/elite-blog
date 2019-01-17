@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters pt-1">
              <div class="">
                <h5>{{ __('New Article')  }}</h5>
              </div>
              <div class="ml-auto">
                <small class="d-inline-block px-2">
                  <a class="nav-link p-0" href="{{ route('post.index') }}">{{ __('Home') }}</a>
                </small>
                <small class="d-inline-block pl-2 border-left">
                  <a class="nav-link p-0" href="{{ route('post.author', ['user' => user_slug()]) }}">
                    {{ __('My Articles') }}
                  </a>
                </small>
              </div>
            </div>
          </div>

          <div class="card-body">
            <form method="POST" action="{{ route('post.store') }}">
              @csrf

              <div class="form-group row">
                <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>

                <div class="col-md-6">
                  <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required autofocus>

                  @if ($errors->has('title'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row">
                <label for="body" class="col-md-4 col-form-label text-md-right">{{ __('Body') }}</label>

                <div class="col-md-6">
                  <textarea id="body" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body" required>{{ old('body') }}</textarea>

                  @if ($errors->has('body'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('body') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ __('Publish') }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
