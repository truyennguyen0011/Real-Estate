@extends('front_page.layouts.master')

@push('header_page')
    <div class="page-header-custom">
    </div>
@endpush

@section('content')
    <main class="main main-raised pb-1">
        <div class="container">
            @if (isset($posts))
                @if (count($posts) > 0)
                    <div class="row" style="margin: 0">
                        <div class="col-md-8 col-md-offset-2">
                            <h1 class="h1-custom text-danger">Kết quả tìm kiếm: "{{ $q }}"</h1>

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
                            <h1 class="h1-custom text-danger">Kết quả tìm kiếm: "{{ $q }}"</h1>
                            <p>Không tìm thấy sản phẩm nào khớp với lựa chọn của bạn.</p>
                        </div>
                    </div>
                @endif

            @endif
        </div>
    </main>
    </div>
@endsection
