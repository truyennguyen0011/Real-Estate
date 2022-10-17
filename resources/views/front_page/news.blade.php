@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header-custom">
    </div>
@endpush

@section('content')
    <main class="main main-raised">
        <div class="container">
            <h2 class="text-center" style="margin-top: 30px"><strong>Tin tức nổi bật</strong></h2>

            @if (isset($news))
                <div class="row">
                    @foreach ($news as $new)
                        <x-news :new="$new" />
                    @endforeach
                </div>
                <div class="row pull-right">
                    {{ $news->links() }}
                </div>
            @endif
        </div>
    </main>
@endsection
