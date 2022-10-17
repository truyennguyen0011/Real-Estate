@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <a class="btn btn-success mt-3" href="{{ route('admin.administrators.index') }}">
                <i class="dripicons-arrow-thin-left"></i>
                <span> Back</span>
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card mt-3">

                <!-- Logo-->
                <div class="card-header pt-4 pb-4 text-center bg-primary">
                    <div class="text-center w-75 m-auto">
                        <h4 class="text-light text-center mt-0 font-weight-bold">Thêm admin</h4>
                    </div>
                </div>

                <div class="card-body">

                    <form class="form-horizontal needs-validation" novalidate
                        action="{{ route('admin.administrators.store') }}" method="post" id="form-create-admin"
                        enctype='multipart/form-data'>
                        @csrf

                        <div class="form-group">
                            <label for="name">Họ Tên</label>
                            <input class="form-control" type="text" id="name" autofocus="1" name="name" value="{{ old('name') }}"
                                placeholder="Nhập tên" required>
                            <div class="invalid-feedback">
                                Tên không được để trống
                            </div>
                            @if ($errors->has('name'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" required
                                value="{{ old('email') }}" placeholder="Nhập email" aria-label="Nhập email">
                            <div class="invalid-feedback">
                                Email không được để trống
                            </div>
                            @if ($errors->has('email'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone">Số điện thoại</label>
                            <input class="form-control" type="phone" name="phone" id="phone" required
                                value="{{ old('phone') }}" placeholder="Nhập số điện thoại"
                                aria-label="Nhập số điện thoại">
                            <div class="invalid-feedback">
                                Số điện thoại không được để trống
                            </div>
                            @if ($errors->has('phone'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Mật khẩu</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="password" value="{{ old('password') }}" id="password"
                                    class="form-control" placeholder="Nhập mật khẩu" aria-label="Nhập mật khẩu" required>
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
                            <label for="select">Quyền</label>
                            <select name="role" class="custom-select" id="select">
                                <option selected>Chọn quyền</option>
                                @foreach ($roles as $role => $value)
                                    @if ($value !== $roles['MASTER'])
                                        <option value="{{ $value }}">{{ $role }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Quyền
                            </div>
                            @if ($errors->has('role'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('role') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Custom Switch -->
                        <div class="custom-control custom-switch mb-3">
                            <input value="1" checked type="checkbox" class="custom-control-input" id="active"
                                name="active">
                            <label class="custom-control-label" for="active">Kích hoạt</label>
                            @if ($errors->has('active'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('active') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="avatar">Avatar</label>
                            <input accept="image/*" name="avatar" id="avatar" type="file"
                                oninput="pic.src=window.URL.createObjectURL(this.files[0])">
                            <img id="pic" height="100" />
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary" type="submit"> Thêm </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function(){});
    </script>
@endpush
