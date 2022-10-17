@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header-custom">
    </div>
@endpush

@section('content')

    <main class="main main-raised pb-1">
        <div class="container">
            @if (isset($new))
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="h1-custom text-danger">{{ $new->title }}</h1>

                        <div class="description-custom">
                            {!! html_entity_decode($new->content) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection