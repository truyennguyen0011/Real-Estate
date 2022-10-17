@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header-custom">
    </div>
@endpush

@section('content')
    <main class="main main-raised pb-1">
        <div class="container">
            @if (count($posts) > 0)
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="text-center h1-custom text-danger">Tất cả {{ ucfirst($current_category) }}</h1>

                        @foreach ($posts as $post)
                            <x-horizontal-post :post="$post" />
                        @endforeach
                        <div class="row pull-right">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            @else 
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h1 class="text-center h1-custom text-danger">Tất cả {{ ucfirst($current_category) }}</h1>

                    <p class="text-center text-danger mb-3">Không có tin đăng nào</p>
                </div>
            </div>
            @endif
        </div>
    </main>
@endsection
