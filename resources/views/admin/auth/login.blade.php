<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Đăng nhập - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ config('app.description') }}" name="description" />
    <meta content="{{ config('app.name') }}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />

</head>

<body class="authentication-bg">

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card">

                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="/">
                                <span><img src=" {{ asset(config('app.logo_image')) }}" alt="logo image"
                                        height="60px"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 font-weight-bold">Đăng nhập</h4>
                            </div>
                            @if ($errors->has('invalid_user'))
                                <div class="alert alert-danger">
                                    <span class="text-danger">{{ $errors->first('invalid_user') }}</span>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.login') }}" class="needs-validation"
                                novalidate>
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="login">Email hoặc số điện thoại</label>
                                    <input class="form-control" type="text" name="login" id="login" required
                                        value="{{ old('login') }}" autofocus="1"
                                        placeholder="Vui lòng điền email hoặc số điện thoại"
                                        aria-label="Vui lòng điền email hoặc số điện thoại">
                                    <div class="invalid-feedback">
                                        Email hoặc số điện thoại không được để trống
                                    </div>
                                    @if ($errors->has('login'))
                                        <div class="alert alert-danger mt-1">
                                            <span class="text-danger">{{ $errors->first('login') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="password">Mật khẩu</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" name="password" value="{{ old('password') }}"
                                            id="password" class="form-control" placeholder="Vui lòng điền mật khẩu"
                                            aria-label="Vui lòng điền mật khẩu" required>
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
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin"
                                            name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="checkbox-signin">Nhớ đăng nhập</label>
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary" type="submit"> Đăng nhập </button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    {{-- footer start --}}
    @include('admin.layouts.footer')
    {{-- footer end --}}

    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
</body>

</html>
