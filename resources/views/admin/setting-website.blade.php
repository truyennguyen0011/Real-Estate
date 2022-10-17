@extends('admin.layouts.master')
@push('css')
    <style>
        #logo-img,
        #favicon-img,
        #logo-sm-img {
            max-width: 30%;
            height: auto;
            margin: 0 auto
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Tab start -->
        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3 mt-3">
            <li class="nav-item">
                <a title="Chỉnh sửa thông tin" href="#edit-info" data-toggle="tab" aria-expanded="true"
                    class="nav-link rounded-0 active">
                    <i class="mdi mdi-home-variant d-md-none d-block"></i>
                    <span class="d-none d-md-block">Thông tin</span>
                </a>
            </li>
            <li class="nav-item">
                <a title="Chỉnh sửa ảnh" href="#edit-images" data-toggle="tab" aria-expanded="false"
                    class="nav-link rounded-0">
                    <i class="mdi mdi-account-circle d-md-none d-block"></i>
                    <span class="d-none d-md-block">Ảnh</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane show active" id="edit-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="settings">
                                    <form class="form-horizontal needs-validation" novalidate
                                        action="{{ route('admin.update-info') }}" method="post" spellcheck="false">
                                        @csrf
                                        @method('PATCH')

                                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Chỉnh
                                            sửa thông tin website </h5>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="app-name">Tên website
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="app_name" type="text" class="form-control"
                                                        id="app-name" placeholder="Nhập tên website..."
                                                        value="{{ config('app.name') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Tên website không được để trống
                                                    </div>
                                                    @if ($errors->has('app_name'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('app_name') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="app-description">Mô tả website
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="app_description" type="text" class="form-control"
                                                        id="app-description" placeholder="Nhập mô tả website..."
                                                        value="{{ config('app.description') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Mô tả không được để trống
                                                    </div>
                                                    @if ($errors->has('app_description'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('app_description') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="my-services-1">My services 1
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="my_services_1" type="text" class="form-control"
                                                        id="my-services-1" placeholder="Nhập dịch vụ của chúng tôi..."
                                                        value="{{ config('app.my_services_1') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Dịch vụ của chúng tôi không được để trống
                                                    </div>
                                                    @if ($errors->has('my_services_1'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('my_services_1') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="my-services-2">My services 2
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="my_services_2" type="text" class="form-control"
                                                        id="my-services-2" placeholder="Nhập dịch vụ của chúng tôi..."
                                                        value="{{ config('app.my_services_2') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Dịch vụ của chúng tôi không được để trống
                                                    </div>
                                                    @if ($errors->has('my_services_2'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('my_services_2') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="my-services-3">My services 3
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="my_services_3" type="text" class="form-control"
                                                        id="my-services-3" placeholder="Nhập dịch vụ của chúng tôi..."
                                                        value="{{ config('app.my_services_3') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Dịch vụ của chúng tôi không được để trống
                                                    </div>
                                                    @if ($errors->has('my_services_3'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('my_services_3') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="my-services-4">My services 4</label>
                                                    <input name="my_services_4" type="text" class="form-control"
                                                        id="my-services-4" placeholder="Nhập dịch vụ của chúng tôi..."
                                                        value="{{ config('app.my_services_4') ?? '' }}">
                                                    <div class="invalid-feedback">
                                                        Dịch vụ của chúng tôi không được để trống
                                                    </div>
                                                    @if ($errors->has('my_services_4'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('my_services_4') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="my-services-5">My services 5</label>
                                                    <input name="my_services_5" type="text" class="form-control"
                                                        id="my-services-5" placeholder="Nhập dịch vụ của chúng tôi..."
                                                        value="{{ config('app.my_services_5') ?? '' }}">
                                                    <div class="invalid-feedback">
                                                        Dịch vụ của chúng tôi không được để trống
                                                    </div>
                                                    @if ($errors->has('my_services_5'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('my_services_5') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Địa chỉ
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="company_address" type="text" class="form-control"
                                                        id="address" placeholder="Nhập địa chỉ"
                                                        value="{{ config('app.company_address') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Địa chỉ không được để trống
                                                    </div>
                                                    @if ($errors->has('company_address'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('company_address') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="company_email" type="text" class="form-control"
                                                        id="email" placeholder="Nhập hotline"
                                                        value="{{ config('app.company_email') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Email không được để trống
                                                    </div>
                                                    @if ($errors->has('company_email'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('company_email') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> <!-- end row -->

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="fb-page">Nhúng fanpage
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="fb_page" type="text" class="form-control"
                                                        id="fb-page" placeholder="Nhập iframe facebook page"
                                                        value="{{ config('app.fb_page') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Iframe facebook page không được để trống
                                                    </div>
                                                    @if ($errors->has('fb_page'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('fb_page') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="map_embed">Nhúng google map
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="map_embed" type="text" class="form-control"
                                                        id="map_embed" placeholder="Nhập iframe google map"
                                                        value="{{ config('app.map_embed') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Iframe google map không được để trống
                                                    </div>
                                                    @if ($errors->has('map_embed'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('map_embed') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> <!-- end row -->

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="hotline">Hotline
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <input name="company_hotline" type="text" class="form-control"
                                                        id="hotline" placeholder="Nhập hotline"
                                                        value="{{ config('app.company_hotline') ?? '' }}" required>
                                                    <div class="invalid-feedback">
                                                        Hotline không được để trống
                                                    </div>
                                                    @if ($errors->has('company_hotline'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('company_hotline') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Mật khẩu
                                                        <small style="color: red;">*</small>
                                                    </label>
                                                    <div class="input-group input-group-merge">
                                                        <input type="password" name="password" id="password"
                                                            class="form-control" placeholder="Nhập mật khẩu đề xác nhận"
                                                            aria-label="Nhập mật khẩu đề xác nhận" required>
                                                        <div class="input-group-append" data-password="false">
                                                            <div class="input-group-text">
                                                                <span class="password-eye"></span>
                                                            </div>
                                                        </div>
                                                        <div class="invalid-feedback">
                                                            Mật khẩu không được để trống
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('password'))
                                                        <div class="alert alert-danger mt-1">
                                                            <span
                                                                class="text-danger">{{ $errors->first('password') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->



                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success mt-2"><i
                                                    class="mdi mdi-content-save"></i>
                                                Lưu </button>
                                        </div>
                                    </form>
                                </div>
                                <!-- end settings content-->
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div>
            </div>
            <div class="tab-pane" id="edit-images">
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-image-edit mr-1"></i> Chỉnh
                            sửa Logo </h5>

                        <img id="logo-img" role="button"
                            src="{{ asset(config('app.logo_image') ?? config('app.no_image')) }}" alt="Logo"
                            title="Đổi logo" width="50%" class="d-block" />

                        <div class="text-center d-flex align-items-center justify-content-center mt-3 mb-3">
                            <label for="logo" id="btn-change-logo" class="btn btn-success btn-sm m-0">Đổi Logo</label>
                            <div id="spinnerLogo" class="spinner-border text-warning ml-1" role="status"
                                style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <form id="form-change-logo" action="{{ route('api.change-image') }}"
                            enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                            <input type="hidden" name="name" value="logo">
                            <input type="file" id="logo" name="image" accept="image/*"
                                style="display: none;">
                        </form>
                        <!-- end settings content-->
                    </div> <!-- end col -->
                    <div class="col-12">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-image-edit mr-1"></i> Chỉnh sửa Favicon </h5>

                        <img id="favicon-img" role="button"
                            src="{{ asset(config('app.favicon_image') ?? config('app.no_image')) }}" alt="Favicon"
                            title="Đổi favicon" width="50%" class="d-block" />

                            
                        <div class="text-center d-flex align-items-center justify-content-center mt-3 mb-3">
                            <label for="favicon" id="btn-change-favicon" class="btn btn-success btn-sm m-0">Đổi Favicon</label>
                            <div id="spinnerFavicon" class="spinner-border text-warning ml-1" role="status"
                                style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <form id="form-change-favicon" action="" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                            <input type="hidden" name="name" value="favicon">
                            <input type="file" id="favicon" name="image" accept=".ico"
                                style="display: none;">
                        </form>
                        <!-- end settings content-->
                    </div> <!-- end col -->
                    <div class="col-12">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-image-edit mr-1"></i> Chỉnh sửa Logo sm </h5>

                        <img id="logo-sm-img" role="button"
                            src="{{ asset(config('app.logo_sm_image') ?? config('app.no_image')) }}" alt="Favicon"
                            title="Đổi logo sm" width="50%" class="d-block" />

                        <div class="text-center d-flex align-items-center justify-content-center mt-3 mb-3">
                            <label for="logo-sm" id="btn-change-logo-sm" class="btn btn-success btn-sm m-0">Đổi Logo sm</label>
                            <div id="spinnerLogoSm" class="spinner-border text-warning ml-1" role="status"
                                style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <form id="form-change-logo-sm" action="{{ route('api.change-image') }}" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                            <input type="hidden" name="name" value="logo_sm">
                            <input type="file" id="logo-sm" name="image" accept="image/*"
                                style="display: none;">
                        </form>
                        <!-- end settings content-->
                    </div> <!-- end col -->
                </div>
            </div>
        </div>
        <!-- Tab end -->
    </div>
    <!-- container -->
@endsection

@push('js')
    <script>
        $(function() {
            $('#logo-img').on('click', function() {
                $("#logo").click();
            });
            $('#favicon-img').on('click', function() {
                $("#favicon").click();
            });
            $('#logo-sm-img').on('click', function() {
                $("#logo-sm").click();
            });

            $("#logo").change(function(event) {
                const obj = $("#form-change-logo");
                const formData = new FormData(obj[0]);

                $.ajax({
                    url: obj.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $("#spinnerLogo").show();
                        $("#btn-change-logo").attr('disable', true);
                    },
                    success: function(response) {
                        $("#spinnerLogo").hide();
                        notifySuccess(response.message);
                        imgUrl = response.data;
                        if (imgUrl == '') {
                            imgUrl = "no-image.png"
                        }

                        $("#btn-change-logo").attr('disable', false);
                        $("#logo").val('');
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 5000);
                    },
                    error: function(response) {
                        $("#spinnerLogo").hide();
                        $("#btn-change-logo").attr('disable', false);
                        $("#logo").val('');
                        notifyError(response.responseJSON.message);
                    }
                });
            });

            $("#logo-sm").change(function(event) {
                const obj = $("#form-change-logo-sm");
                const formData = new FormData(obj[0]);

                $.ajax({
                    url: obj.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function() {
                        $("#spinnerLogoSm").show();
                        $("#btn-change-logo-sm").attr('disable', true);
                    },
                    success: function(response) {
                        $("#spinnerLogoSm").hide();
                        notifySuccess(response.message);
                        imgUrl = response.data;
                        if (imgUrl == '') {
                            imgUrl = "no-image.png"
                        }

                        $("#btn-change-logo-sm").attr('disable', false);
                        $("#logo-sm").val('');
                        setTimeout(function() {
                            window.location.href = window.location.href;
                        }, 5000);
                    },
                    error: function(response) {
                        $("#spinnerLogoSm").hide();
                        $("#btn-change-logo-sm").attr('disable', false);
                        $("#logo-sm").val('');
                        notifyError(response.responseJSON.message);
                    }
                });
            });

            $("#favicon").change(function(event) {
                notifyWarning('Chưa thể đổi favicon');
            });
        })
    </script>
@endpush
