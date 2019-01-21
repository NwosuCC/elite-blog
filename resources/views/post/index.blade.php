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
                    <div class="row px-4">
                        <div class="col-12">
                            <span class="pr-3">Filter posts by:</span>
                            <span class="pr-1 font-weight-bold">Category</span>
                            <div class="d-inline-block">
                                <select id="category" onchange="goTo(this.value);" class="form-control-sm" name="category" style="font-size: 14px;" required title="">
                                    <option value=""> All Categories </option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->slug }}" {{$category && $category->slug === $cat->slug ? 'selected' : ''}}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Script function goto() --}}
                                <script>
                                    const goTo = (category) => {
                                        let href = '', author = '{{ $user ? $user->slug : '' }}';

                                        switch (true) {
                                            case !!(author && category) : {
                                                href = '{{route('post.author.category',['user'=>'', 'category'=>''])}}/' + category;
                                                href = href.replace('by/', 'by/' + author);
                                                break;
                                            }
                                            case !!(author) : {
                                                href = '{{route('post.author',['user'=>''])}}/' + author;
                                                break;
                                            }
                                            case !!(category) : {
                                                href = '{{route('post.category',['category'=>''])}}/' + category;
                                                break;
                                            }
                                            default : {
                                                href = '{{route('post.index')}}/';
                                            }
                                        }

                                        window.location.href = href;
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                    <hr />

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
                          There are no published articles here
                        </div>
                      @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

