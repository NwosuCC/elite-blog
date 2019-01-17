@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                  <div class="row no-gutters align-items-center">
                    <div class="">
                      <h2>{{ __('Articles')  }}</h2>
                    </div>
                    <div class="ml-auto">
                      <small class="d-inline-block px-2">
                        <a class="nav-link p-0" href="{{ route('post.index') }}">
                          {{ __('Home') }}
                        </a>
                      </small>

                      <small class="d-inline-block pl-2 border-left">
                        <a class="nav-link p-0" href="{{ route('post.create') }}">
                          {{ __('Publish an Article') }}
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

                    <div class="row p-4">
                      @foreach($posts as $post)
                      <div class="col-4">
                        <h5 class="font-weight-bold">
                          {{--<a class="nav-link p-0" href="{{ route('post.show', ['post' => $post->id]) }}">
                            {{ __($post->title) }}
                          </a>--}}
                          {{ __($post->title) }}
                        </h5>
                        <div style="height: 70px; overflow: hidden;">
                          <p>
                            <span>
                              {{ __( str_words($post->body, 22) ) }}
                            </span>

                            <small class="d-inline-block">
                              <a class="nav-link p-0" href="{{ route('post.show', ['post' => $post->slug]) }}">
                                {{ __('Read more >>') }}
                              </a>
                            </small>
                          </p>
                        </div>
                      </div>
                      @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
