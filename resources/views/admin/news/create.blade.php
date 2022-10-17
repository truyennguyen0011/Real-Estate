@extends('admin.layouts.master')
@push('css')
    <!-- Summernote css -->
    <link href="{{ asset('css/vendor/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                </div>
                <a class="btn btn-success mt-3 mb-3" href="{{ route("admin.$table.index") }}">
                    <span>
                        <i class="dripicons-arrow-thin-left"></i>
                        Trở về
                    </span>
                </a>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6 col-lg-12 order-lg-2 order-xl-1">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mt-2 mb-3">Đăng tin tức</h4>

                    <div>
                        <form class="form-horizontal needs-validation" novalidate action="{{ route("admin.$table.store") }}"
                            spellcheck="false" method="post" id="form-create-news" enctype='multipart/form-data'>
                            @csrf

                            {{-- Title start --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title">Tiêu đề:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="title" autofocus="1"
                                            maxlength="255" name="title" value="{{ old('title') }}"
                                            placeholder="Nhập tiêu đề" required>
                                        <div class="invalid-feedback">
                                            Tiêu đề không được để trống
                                        </div>
                                        @if ($errors->has('title'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('title') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Title end --}}

                            <!-- Content start-->
                            <textarea id="summernote-basic" name="content" style="min-height: 230px; min-width: 100%;">
                            </textarea>
                            <!-- Content end-->

                            {{-- Images upload start --}}
                            <div class="form-group mt-3 mb-3">
                                <label for="image-thumb" class="btn btn-block btn-social btn-success"><i
                                        class="mdi mdi-upload-multiple"></i> Ảnh bìa </label>
                                <input accept="image/*" type="file" name="image_thumb" id="image-thumb"
                                    oninput="pic.src=window.URL.createObjectURL(this.files[0])" style="display: none">
                                <img id="pic" width="100%" />
                                @if ($errors->has('image_thumb'))
                                    <div class="alert alert-danger mt-1">
                                        <span class="text-danger">{{ $errors->first('image_thumb') }}</span>
                                    </div>
                                @endif
                            </div>
                            {{-- Images upload end --}}

                            <div class="form-group mb-0 pt-3 text-center">
                                <button id="btn-submit-news" class="btn btn-primary" type="button"> Hoàn thành </button>
                            </div>

                            <div class="row d-flex align-items-center justify-content-center">
                                <div id="loading-submit" class="spinner-border m-3 text-primary" role="status"
                                    style="display: none">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row-->
@endsection

@push('js')
    <!-- plugin js -->
    <script src="{{ asset('js/vendor/summernote-bs4.min.js') }}"></script>
    <!-- Summernote demo -->
    <script src="{{ asset('js/pages/demo.summernote.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote-basic').summernote();

            $('#btn-submit-news').on('click', function() {
                let content = $('#summernote-basic').val();
                let image = $('#image-thumb').val();

                if (content == '' || image == '') {
                    notifyWarning('Vui lòng nhập nội dung và chọn ảnh bìa !');
                } else {
                    $('#form-create-news').submit();
                    $('#loading-submit').show();
                }
            })
        });
    </script>
@endpush
