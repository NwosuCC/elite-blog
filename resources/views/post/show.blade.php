@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row no-gutters align-items-center">
              <div class="">
                <h2>
                  {{ __($post->title)  }}
                </h2>
              </div>

              <div class="ml-auto">
                <small class="d-inline-block px-2">
                  <a class="nav-link p-0" href="{{ $post->route->index }}">
                    {{ __('Home') }}
                  </a>
                </small>

                <small class="d-inline-block px-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ url()->previous() }}">
                    {{ __('Back') }}
                  </a>
                </small>

                @can('update', $post)
                <small class="d-inline-block pl-2 border-left">
                  <a class="nav-link d-inline-block p-0" href="{{ $post->route->edit }}">
                    {{ __('Edit Article') }}
                  </a>
                </small>
                @endcan
              </div>
            </div>
          </div>

          <div class="card-body">
            <small class="row no-gutters">
              <span class="mr-4">
                <b>Author</b>:
                <a class="nav-link d-inline-block p-0" href="{{ $post->route->index_author($post->user) }}">
                  {{ __($post->user->name) }}
                </a>

              </span>
              <span class="mr-4">
                <b>Published</b>:
                <span class="mr-2">
                  {{ __($post->published_at->toDayDateTimeString()) }}
                </span>
                <span class="mr-1">
                  ({{ __($post->published_at->diffForHumans()) }})
                </span>
              </span>
              <span class="mr-4">
                <b>Category</b>:
                <a class="nav-link d-inline-block p-0" href="{{ $post->route->index_category($post->category) }}">
                  {{ __($post->category->name) }}
                </a>
              </span>
            </small>

            <div class="pt-4">
              {{ __($post->body)  }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
