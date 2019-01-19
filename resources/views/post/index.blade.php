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
                      <small>
                        <a class="nav-link p-0" href="{{ route('post.create') }}">{{ __('Publish an Article') }}</a>
                      </small>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                    <div class="row p-4">
                      @foreach($posts as $post)
                      <div class="col-12 col-lg-6">
                        <h5 class="font-weight-bold">
                          {{ __($post->title) }}
                        </h5>
                        <div class="mb-4 mb-md-0" style="height: 70px; overflow: hidden;">
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
                      @if(!count($posts))
                        <div class="col-12 text-center">
                          There are no published articles
                        </div>
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
