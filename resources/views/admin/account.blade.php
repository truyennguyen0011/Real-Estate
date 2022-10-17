@extends('admin.layouts.master')
@section('content')
    <div class="container-fluid">

        <div class="row pt-3">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">

                        <img id="my-avatar" role="button"
                            src="{{ asset(config('app.image_avatar_direction') . ($user->avatar ? $user->avatar : 'no-avatar.png')) }}"
                            alt="My avatar" title="My avatar" height="100px" class="rounded-circle avatar-lg img-thumbnail" />

                        <h4 class="mb-0 mt-2">{{ $user->name ?? 'Admin' }}</h4>
                        <p class="text-muted font-14">{{ $user->role_name ?? 'ADMIN' }}</p>

                        <label for="avatar" id="btn-change-avatar" class="btn btn-success btn-sm mb-2">Đổi avatar</label>
                        <a href="{{ route('admin.change-password') }}" class="btn btn-danger btn-sm mb-2">Đổi mật khẩu</a>

                        <form id="form-change-avatar" action="{{ route('api.change-avatar') }}"
                            enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                        </form>

                        <div class="row d-flex align-items-center justify-content-center">
                            <div id="loadingImages" class="spinner-border m-3 text-warning" role="status"
                                style="display: none">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="text-left mt-3">
                            <h4 class="font-13 text-uppercase">Giới thiệu :</h4>
                            <p class="text-muted font-13 mb-3">
                                {!! nl2br($user->about_me) ?? 'Chưa có thông tin giới thiệu.' !!}
                            </p>
                            <p class="text-muted mb-2 font-13"><strong>Họ tên :</strong> <span
                                    class="ml-2">{{ $user->name ?? 'Admin' }}</span></p>

                            <p class="text-muted mb-2 font-13"><strong>SDT :</strong><span
                                    class="ml-2">{{ $user->phone ?? '' }}</span></p>

                            <p class="text-muted mb-2 font-13"><strong>Email :</strong> <span
                                    class="ml-2 ">{{ $user->email ?? '' }}</span></p>

                            <p class="text-muted mb-2 font-13"><strong>Ngày tạo :</strong> <span
                                    class="ml-2 ">{{ $user->created_at_vn ?? '' }}</span></p>
                        </div>

                        <ul class="social-list list-inline mt-3 mb-0">
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i
                                        class="mdi mdi-facebook"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i
                                        class="mdi mdi-google"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-info text-info"><i
                                        class="mdi mdi-twitter"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i
                                        class="mdi mdi-github-circle"></i></a>
                            </li>
                        </ul>
                    </div> <!-- end card-body -->
                </div> <!-- end card -->

            </div> <!-- end col-->

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div id="settings">
                            <form class="form-horizontal needs-validation" novalidate
                                action="{{ route('admin.account-edit', $user) }}" method="post" spellcheck="false">
                                @csrf
                                @method('PATCH')

                                <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> Chỉnh sửa thông
                                    tin </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Họ tên</label>
                                            <input name="name" type="text" class="form-control" id="name"
                                                placeholder="Nhập họ tên" value="{{ $user->name ?? '' }}" required>
                                            <div class="invalid-feedback">
                                                Họ tên không được để trống
                                            </div>
                                            @if ($errors->has('name'))
                                                <div class="alert alert-danger mt-1">
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại</label>
                                            <input name="phone" type="text" class="form-control" id="phone"
                                                placeholder="Nhập số điện thoại" value="{{ $user->phone ?? '' }}" required>
                                            <div class="invalid-feedback">
                                                Số điện thoại không được để trống
                                            </div>
                                            @if ($errors->has('phone'))
                                                <div class="alert alert-danger mt-1">
                                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="userbio">Giới thiệu bản thân</label>
                                            <textarea name="about_me" class="form-control" id="userbio" rows="4" placeholder="Viết gì đó...">{!! nl2br($user->about_me) ?? '' !!}</textarea>
                                            @if ($errors->has('about_me'))
                                                <div class="alert alert-danger mt-1">
                                                    <span class="text-danger">{{ $errors->first('about_me') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input name="email" type="email" class="form-control" id="email"
                                                placeholder="Nhập email" value="{{ $user->email ?? '' }}" required>
                                            <div class="invalid-feedback">
                                                Email không được để trống
                                            </div>
                                            @if ($errors->has('email'))
                                                <div class="alert alert-danger mt-1">
                                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Mật khẩu</label>
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
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div> <!-- end col -->
                                </div> <!-- end row -->

                                <div class="text-right">
                                    <button type="submit" class="btn btn-success mt-2"><i
                                            class="mdi mdi-content-save"></i> Lưu </button>
                                </div>
                            </form>
                        </div>
                        <!-- end settings content-->
                    </div> <!-- end card body -->
                </div> <!-- end card -->
            </div> <!-- end col -->
        </div>
        <!-- end row-->

    </div>

    <!-- container -->
@endsection

@push('js')
    <script>
        $(function() {
            $("#loadingImages").hide();

            $('#my-avatar').on('click', function() {
                $("#avatar").click();
            });

            $("#avatar").change(function(event) {
                const obj = $("#form-change-avatar");
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
                        $("#loadingImages").show();
                        $("#btn-change-avatar").attr('disable', true);
                    },
                    success: function(response) {
                        $("#loadingImages").hide();
                        notifySuccess(response.message);
                        imgUrl = response.data;
                        if (imgUrl == '') {
                            imgUrl = "no-avatar.png"
                        }

                        dir = '{{ asset(config('app.image_avatar_direction')) }}/';

                        $('#my-avatar').attr('src', dir + imgUrl);
                        $('#my-avatar-sm').attr('src', dir + imgUrl);
                        $("#btn-change-avatar").attr('disable', false);
                        $("#avatar").val('');
                    },
                    error: function(response) {
                        $("#loadingImages").hide();
                        $("#btn-change-avatar").attr('disable', false);
                        $("#avatar").val('');
                        notifyError(response.responseJSON.message);
                    }
                });
            });
        })
    </script>
@endpush
