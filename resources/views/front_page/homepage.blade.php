@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header header-filter clear-filter" data-parallax="true"
        style="background-image: url('{{ asset('images/bg10.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="brand">
                        <h1>
                            {{ config('app.name') }}
                        </h1>

                        <h4 class="title">
                            {{ config('app.description') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <main class="main main-raised">
        <div class="container">
            <h2 class="text-center" style="margin-top: 30px"><strong>Nhà đất nổi bật</strong></h2>

            @if (isset($posts))
                <div class="row">
                    @foreach ($posts as $post)
                        <x-post :post="$post" />
                    @endforeach
                </div>
                <div class="row pull-right">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </main>
@endsection
