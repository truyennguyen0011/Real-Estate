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
                    <h4 class="header-title mt-2 mb-3">Sửa tin</h4>

                    <div>
                        <form class="form-horizontal needs-validation" novalidate
                            action="{{ route("admin.$table.update", $new) }}" method="post" id="form-update-new"
                            spellcheck="false" enctype='multipart/form-data'>
                            @csrf
                            @method('PATCH')

                            {{-- Title start --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="title">Tiêu đề:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="title" autofocus="1"
                                            maxlength="255" name="title" value="{{ $new->title }}"
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

                            {{-- Slug start --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="slug">Slug:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="slug" autofocus="1"
                                            maxlength="255" name="slug" value="{{ $new->slug }}"
                                            placeholder="Nhập slug" required>
                                        <div class="invalid-feedback">
                                            Slug không được để trống
                                        </div>
                                        @if ($errors->has('slug'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('slug') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Slug end --}}

                            {{-- Content start --}}
                            <div style="min-height: 290px; min-width: 100%;">
                                <textarea id="summernote-basic" name="content" style="display: none;">
                                {!! $new->content !!}
                                </textarea>
                            </div>

                            {{-- Content end --}}

                            {{-- Images upload start --}}
                            <div class="form-group mt-3 mb-3">
                                <label for="image-thumb" class="btn btn-block btn-social btn-success"><i
                                        class="mdi mdi-upload-multiple"></i> Ảnh bìa </label>
                                <input accept="image/*" type="file" name="image_thumb" id="image-thumb"
                                    oninput="pic.src=window.URL.createObjectURL(this.files[0])" style="display: none">
                                @if ($new->image_thumb == '')
                                    <img id="pic" width="100%" src="{{ asset('images/no-image.png') }}"
                                        alt="No image" title="No image">
                                @else
                                    <div class="position-relative">
                                        <div class="position-absolute img-id">{{ $new->id }}</div>
                                        <img id="pic" width="100%"
                                            src="{{ asset(config('app.image_new_thumb_direction')) . '/' . $new->image_thumb }}"
                                            alt="{{ $new->title }}" title="{{ $new->title }}">
                                        <div class="position-absolute img-created-at">
                                            {{ $new->date_and_hour_vn }}</div>
                                    </div>
                                @endif
                            </div>
                            {{-- Images upload end --}}

                            <div class="form-group mb-0 pt-3 text-center">
                                <div class="float-right">
                                    <button id="btn-update-new" class="btn btn-primary mr-2" type="button"> Cập nhật
                                    </button>
                                    <a href='{{ route("admin.$table.index") }}'' class="btn btn-secondary"> Hủy </a>
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
    <script type="text/javascript">
        $(document).ready(function() {
            let _new = {!! $new !!};
            let newContent = '{!! html_entity_decode($new->content) !!}';

            // Check form change data
            $('#btn-update-new').click(function() {
                let title = $('#title').val();
                let slug = $('#slug').val();
                let content = $('#summernote-basic').val();
                let image_thumb = $('#image-thumb').val();
                if (title.trim() === _new.title.trim() && slug.trim() === _new.slug.trim() && content
                    .trim() === newContent.trim() && image_thumb == '') {
                    notifyWarning("Bạn chưa thay đổi gì !");
                } else {
                    $('#form-update-new').submit();
                }
            });
        });
    </script>
@endpush
