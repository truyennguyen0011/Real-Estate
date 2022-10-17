@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header-custom">
    </div>
@endpush

@section('content')

    <main class="main main-raised pb-1">
        <div class="container">
            @if (isset($post))
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="h1-custom text-danger">{{ $post->title }}</h1>

                        <div class="row row-post-info">
                            <div class="col-sm-2 d-flex">
                                <i class="material-icons">location_on</i>
                                Địa điểm:
                            </div>
                            <div class="col-sm-10 d-flex">
                                <strong>{{ $post->address }}</strong>
                            </div>
                        </div>
                        <div class="row row-post-info">
                            <div class="col-sm-2 d-flex">
                                <i class="material-icons">area_chart</i>
                                Diện tích:
                            </div>
                            <div class="col-sm-10 d-flex">
                                <strong>{{ $post->area }}</strong>
                            </div>
                        </div>
                        <div class="row row-post-info">
                            <div class="col-sm-2 d-flex">
                                <i class="material-icons">sell</i>
                                Giá:
                            </div>
                            <div class="col-sm-10 d-flex text-danger">
                                <strong>{{ $post->price }}</strong>
                            </div>
                        </div>
                        @isset($post->direction)
                            <div class="row row-post-info">
                                <div class="col-sm-2 d-flex">
                                    <i class="material-icons">explore</i>
                                    Hướng:
                                </div>
                                <div class="col-sm-10 d-flex">
                                    <strong>{{ $post->direction }}</strong>
                                </div>
                            </div>
                        @endisset

                        <h3><small><strong>Mô tả</strong></small></h3>

                        <div class="description-custom">
                            {!! nl2br($post->description) !!}
                        </div>

                        @foreach ($post->images as $image)
                            <p>
                                <img loading="lazy" width="750" height="422" class="img-post lazy-load"
                                    lazy-src="{{ asset($image->folder . $image->url) }}"
                                    src="data:image/svg+xml,%3Csvg%20viewBox%3D%220%200%20700%20933%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3C%2Fsvg%3E"
                                    alt="{{ $post->title }}" title="{{ $post->title }}">
                            </p>
                        @endforeach

                        @if ($post->youtube_id != '')
                            <h3><small><strong>Video</strong></small></h3>

                            <iframe class="yt_frame" width="100%" height="409"
                                src="https://www.youtube.com/embed/{{ $post->youtube_id }}" title="{{ $post->title }}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

    <div class="section" style="padding: 32px 0">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title text-center" style="margin: 0">
                        <small><strong>Tin tương tự</strong></small>
                    </h2>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                <div class="carousel slide" data-ride="carousel">

                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        @if ($num != 0)
                                            @for ($i = 0; $i < $num; $i++)
                                                <li data-target="#carousel-example-generic"
                                                    data-slide-to="{{ $i }}"
                                                    class="{{ $i == 0 ? 'active' : '' }}">
                                                </li>
                                            @endfor
                                        @endif
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner">
                                        @php
                                            $count = count($relatedPosts);
                                            $x = 0;
                                        @endphp
                                        @if ($count > 0)
                                            @for ($i = 0; $i < $num; $i++)
                                                <div class="item {{ $i == 0 ? 'active' : '' }}">
                                                    <div class="row">
                                                        @for ($j = 0; $j < 2; $j++)
                                                            <x-related-post :post="$relatedPosts[$x]"></x-related-post>
                                                            @php
                                                                $x++;
                                                            @endphp
                                                            @if ($x == $count)
                                                            @break
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        @endfor
                                    @else
                                        <p class="text-center text-danger">Không có tin đăng tương tự</p>
                                    @endif
                                </div>
                                @if ($num > 1)
                                    <!-- Controls -->
                                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                        <i class="material-icons">keyboard_arrow_left</i>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic"
                                        data-slide="next">
                                        <i class="material-icons">keyboard_arrow_right</i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
