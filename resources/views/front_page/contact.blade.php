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

            <div class="row mt-2 mb-2 pl-3 pr-3">
                <div class="col-md-6">
                    <h2 class="text-center" style="margin-top: 30px"><strong>LIÊN HỆ NGAY ĐỂ ĐƯỢC TƯ VẤN MIỄN PHÍ</strong>
                    </h2>
                    <div class="row">
                        <h3 class="text-center">
                            <i class="material-icons">call</i>

                            <strong>
                                <a href="tel:{{ config('app.company_hotline') }}">{{ config('app.company_hotline') }}</a>
                                (Văn phòng {{ config('app.name') }})
                            </strong>
                        </h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        @if (Session::has('send_success'))
                            <div class="alert alert-success mt-2" role="alert">
                                {{ Session::get('send_success') }}
                            </div>
                        @endif
                    </div>
                    <form role="form" id="contact-form" method="post">
                        @csrf
                        <div class="form-group label-floating">
                            <label class="control-label">Tên của bạn</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Địa chỉ email</label>
                            <input type="email" name="email" class="form-control" />
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" />
                        </div>
                        <div class="form-group label-floating">
                            <label class="control-label">Nhu cầu tìm kiếm</label>
                            <textarea name="message" class="form-control" id="message" rows="6"></textarea>
                        </div>
                        <div class="submit text-center">
                            <input id="btn-submit-contact" type="submit" class="btn btn-primary btn-raised btn-round"
                                value="Gửi liên hệ" />
                        </div>
                        <div id="loading-submit-contact" class="spinner-border text-center" role="status" style="display: none">
                            <span class="visually-hidden">Đang gửi...</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script>
        $(function() {
            $(document).on('click', '#btn-submit-contact', function() {
                $('#loading-submit-contact').show();
            });
        });
    </script>
@endpush
