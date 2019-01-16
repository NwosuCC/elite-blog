@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters align-items-center">
              <div class="">
                <h2>{{ __($post->title)  }}</h2>
              </div>
              <div class="ml-auto">
                <small class="d-inline-block px-2">
                  <a class="nav-link p-0" href="{{ route('post.index') }}">{{ __('Home') }}</a>
                </small>
                <small class="d-inline-block pl-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ route('post.edit', ['post' => $post]) }}">{{ __('Edit Article') }}</a>
                </small>
              </div>
            </div>
          </div>

          <div class="card-body">
            <small class="row no-gutters">
              <span class="mr-4">
                  {{-- ToDo: Apply User-Post relation here --}}
                <b>Author</b>: {{ App\User::find( __($post->user_id) )->name }}
              </span>
              <span class="mr-4">
                <b>Published</b>: {{ __($post->created_at->diffForHumans()) }}
              </span>
            </small>

            <div class="pt-4">{{ __($post->body)  }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
