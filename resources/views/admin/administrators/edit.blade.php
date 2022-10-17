@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <a class="btn btn-success mt-3" href="{{ route("admin.$table.index") }}">
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
                        <h4 class="text-light text-center mt-0 font-weight-bold">Chỉnh sửa</h4>
                    </div>
                </div>

                <div class="card-body">

                    <form class="form-horizontal needs-validation" novalidate
                        action="{{ route("admin.$table.update", $admin) }}" method="post" id="form-update-admin"
                        enctype='multipart/form-data'>
                        @csrf
                        @method('PATCH')

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Họ Tên</label>
                            <input class="form-control" type="text" id="name" autofocus="1" name="name" value="{{ $admin->name }}"
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

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" required
                                value="{{ $admin->email }}" placeholder="Nhập email" aria-label="Nhập email">
                            <div class="invalid-feedback">
                                Email không được để trống
                            </div>
                            @if ($errors->has('email'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Phone --}}
                        <div class="form-group mb-3">
                            <label for="phone">Số điện thoại</label>
                            <input class="form-control" type="phone" name="phone" id="phone" required
                                value="{{ $admin->phone }}" placeholder="Nhập số điện thoại"
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

                        {{-- Role --}}
                        <div class="form-group mb-3">
                            <label for="select">Quyền</label>
                            <select name="role" class="custom-select" id="select">
                                <option selected>Chọn quyền</option>
                                @foreach ($roles as $role => $value)
                                    @if ($value !== $roles['MASTER'])
                                        <option value="{{ $value }}" {{ $admin->role === $value ? 'selected' : '' }}>
                                            {{ $role }}
                                        </option>
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

                        {{-- Avatar --}}
                        <div class="form-group mb-3">
                            <label for="avatar">Avatar</label>
                            <input accept="image/*" name="avatar" id="avatar" type=file
                                oninput="pic.src=window.URL.createObjectURL(this.files[0])">
                            <img id="pic" height="100" src="{{ asset(config('app.image_avatar_direction') . ($admin->avatar ? $admin->avatar : 'no-avatar.png')) }}" />
                            @if ($errors->has('avatar'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('avatar') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button class="btn btn-primary" id="btn-update-admin" type="button"> Cập nhật </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function(){
            let admin = {!! $admin !!};

            // Check form change data
            $('#btn-update-admin').click(function () { 
                let name = $('#name').val();
                let email = $('#email').val();
                let phone = $('#phone').val();
                let role = $('#select').val();
                let avatar = $('#avatar').val();

                if (name === admin.name && email === admin.email && phone === admin.phone && role == admin.role && avatar == '') {
                    notifyWarning("Bạn chưa thay đổi gì !");
                } else {
                    $('#form-update-admin').submit();
                } 
            });
        });
    </script>
@endpush
