@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <a class="btn btn-success mt-3" href="{{ route('admin.account') }}">
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
                        <h4 class="text-light text-center mt-0 font-weight-bold">Đổi mật khẩu</h4>
                    </div>
                </div>

                <div class="card-body">

                    <form class="form-horizontal needs-validation" novalidate action="{{ route('admin.update-password') }}"
                        method="post" id="form-update-password" enctype='multipart/form-data'>
                        @csrf
                        @method('PATCH')

                        <div class="form-group mb-3">
                            <label for="password">Mật khẩu cũ</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="old_password" value="{{ old('old_password') }}" id="old-password" class="form-control" placeholder="Nhập mật khẩu cũ"
                                    aria-label="Nhập mật khẩu cũ" required>
                                <div class="input-group-append" data-password="false">
                                    <div class="input-group-text">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Mật khẩu cũ không được để trống
                                </div>
                            </div>
                            @if ($errors->has('old_password'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('old_password') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Mật khẩu mới</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="new_password" value="{{ old('new_password') }}" id="new-password" class="form-control" placeholder="Nhập mật khẩu mới"
                                    aria-label="Nhập mật khẩu mới" required>
                                <div class="input-group-append" data-password="false">
                                    <div class="input-group-text">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Mật khẩu mới không được để trống
                                </div>
                            </div>
                            @if ($errors->has('new_password'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('new_password') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Xác nhận mật khẩu mới</label>
                            <div class="input-group input-group-merge">
                                <input type="password" name="confirm_new_password" value="{{ old('confirm_new_password') }}"  id="confirm-new-password" class="form-control" placeholder="Vui lòng xác nhận mật khẩu mới" aria-label="Vui lòng xác nhận mật khẩu mới" required>
                                <div class="input-group-append" data-password="false">
                                    <div class="input-group-text">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <div class="invalid-feedback">
                                    Vui lòng xác nhận mật khẩu mới
                                </div>
                            </div>
                            @if ($errors->has('confirm_new_password'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('confirm_new_password') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary" id="btn-submit-password" type="button"> Cập nhật </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $('#btn-submit-password').on('click', function() {
                let oldPass = $('#old-password').val();
                let newPass = $('#new-password').val();
                let confirmPass = $('#confirm-new-password').val();

                if (newPass !== confirmPass) {
                    notifyWarning('Mật khẩu xác nhận không khớp. Vui lòng thử lại !');
                } else {
                    $('#form-update-password').submit();
                }
            })
        });
    </script>
@endpush
