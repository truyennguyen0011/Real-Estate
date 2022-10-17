@extends('admin.layouts.master')
@push('css')
    <style>
        .img-post {
            height: 150px;
            object-fit: cover;
            object-position: center center;
        }

        .img-close {
            width: 30px;
            height: auto;
            opacity: 0.8;
            background: rgb(211, 201, 201);
            color: rgb(0, 0, 0);
            top: 0;
            right: 0;
            cursor: pointer;
            text-align: center;
        }

        .img-close:hover {
            opacity: 1;
            background: rgb(236, 225, 225);
            color: rgb(255, 0, 0);
        }

        .btn-rm {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        textarea {
            overflow-y: scroll;
            max-height: 400px;
        }
    </style>
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
                            action="{{ route("admin.$table.update", $post) }}" method="post" id="form-edit-post"
                            spellcheck="false">
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
                                            maxlength="255" name="title" value="{{ $post->title }}"
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
                                            maxlength="255" name="slug" value="{{ $post->slug }}"
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

                            {{-- Description start --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">Mô tả:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <textarea id="description" name="description" data-toggle="maxlength" class="form-control" minlength="20"
                                            maxlength="10000" rows="auto" placeholder="Nhập mô tả" required>{!! $post->description !!}</textarea>
                                        <div class="invalid-feedback">
                                            Mô tả không được để trống
                                        </div>
                                        @if ($errors->has('description'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('description') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Description end --}}

                            {{-- Category and direction start --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="category_id">Chuyên mục:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <select name="category_id" id="category_id" class="custom-select" required>
                                            <option selected disabled value="">Chọn chuyên mục:</option>
                                            @foreach ($categories as $category)
                                                <option {{ $post->category_id == $category->id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Chuyên mục không được để trống
                                        </div>
                                        @if ($errors->has('category_id'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="direction">Hướng:</label>
                                        <input class="form-control" type="text" id="direction" name="direction"
                                            value="{{ $post->direction }}" placeholder="Nhập hướng">
                                        {{-- <div class="invalid-feedback">
                                            Hướng không được để trống
                                        </div> --}}
                                        @if ($errors->has('direction'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('direction') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Category and direction end --}}

                            {{-- Price and area start --}}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="price">Giá:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="price" name="price"
                                            value="{{ $post->price }}" placeholder="Nhập giá" required>
                                        <div class="invalid-feedback">
                                            Giá không được để trống
                                        </div>
                                        @if ($errors->has('price'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('price') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="area">Diện tích:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="area" name="area"
                                            value="{{ $post->area }}" placeholder="Nhập diện tích" required>
                                        <div class="invalid-feedback">
                                            Diện tích không được để trống
                                        </div>
                                        @if ($errors->has('area'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('area') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Price and area end --}}

                            {{-- Address start --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address">Địa chỉ:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="address" maxlength="255"
                                            name="address" value="{{ $post->address }}" placeholder="Nhập dịa chỉ"
                                            required>
                                        <div class="invalid-feedback">
                                            Địa chỉ không được để trống
                                        </div>
                                        @if ($errors->has('address'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('address') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Address end --}}

                            {{-- Address detail start --}}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city">Tỉnh/thành phố:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <select name="city_id" id="city" class="custom-select" required>
                                            <option selected disabled value="">Chọn tỉnh/thành phố:</option>
                                            @foreach ($cities as $city)
                                                <option {{ $post->city_id == $city->id ? 'selected' : '' }}
                                                    value='{!! $city->id !!}'>{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Tỉnh/thành phố không được để trống
                                        </div>
                                        @if ($errors->has('city'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="district">Quận/huyện:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <select name="district_id" id="district" class="custom-select" required>
                                            <option selected disabled value="">Chọn quận/huyện:</option>
                                            @foreach ($districts as $district)
                                                <option {{ $post->district_id == $district->id ? 'selected' : '' }}
                                                    value='{!! $district->id !!}'>{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Quận/huyện không được để trống
                                        </div>
                                        @if ($errors->has('district'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('district') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="commune">Phường/xã:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <select name="commune_id" id="commune" class="custom-select" required>
                                            <option selected disabled value="">Chọn phường/xã:</option>
                                            @foreach ($communes as $commune)
                                                <option {{ $post->commune_id == $commune->id ? 'selected' : '' }}
                                                    value='{!! $commune->id !!}'>{{ $commune->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Phường/xã không được để trống
                                        </div>
                                        @if ($errors->has('commune'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('commune') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Address detail end --}}

                            {{-- Contact start --}}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="name_seller">Tên liên hệ:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="name_seller" name="name_seller"
                                            value="{{ $post->name_seller ?? '' }}" placeholder="Nhập họ/tên" required>
                                        <div class="invalid-feedback">
                                            Tên liên hệ không được để trống
                                        </div>
                                        @if ($errors->has('name_seller'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('name_seller') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="phone_seller">Số điện thoại:
                                            <small style="color: red;">*</small>
                                        </label>
                                        <input class="form-control" type="text" id="phone_seller" name="phone_seller"
                                            value="{{ $post->phone_seller ?? '' }}" placeholder="Nhập số điện thoại"
                                            required>
                                        <div class="invalid-feedback">
                                            Số điện thoại không được để trống
                                        </div>
                                        @if ($errors->has('phone_seller'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('phone_seller') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email_seller">Email:</label>
                                        <input class="form-control" type="text" id="email_seller" name="email_seller"
                                            value="{{ $post->email_seller ?? '' }}" placeholder="Nhập email">
                                        <div class="invalid-feedback">
                                            Email không được để trống
                                        </div>
                                        @if ($errors->has('email_seller'))
                                            <div class="alert alert-danger mt-1">
                                                <span class="text-danger">{{ $errors->first('email_seller') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Contact end --}}

                            {{-- Images upload start --}}
                            <div class="row">
                                {{-- Multi images upload --}}
                                <div class="col-lg-6">

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <label for="images" class="btn btn-block btn-social btn-primary"><i
                                                        class="mdi mdi-upload-multiple"></i> Hình ảnh </label>
                                                <input type="hidden" name="list_images" id="list-images">
                                                <input type="hidden" name="list_remove_images" id="list-remove-images">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row d-flex align-items-center justify-content-center">
                                                <div id="loadingImages" class="spinner-border m-3 text-warning"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                            <div class="row" id="display_image_list">
                                                @foreach ($post->images as $image)
                                                    @php
                                                        $rdString = Str::random(4);
                                                    @endphp
                                                    <div class='col-lg-6 item-img' id='{{ $rdString }}'>
                                                        <div class='card position-relative'>
                                                            <img class="img-post" width='100%'
                                                                id='{{ $rdString }}'
                                                                src='{{ asset($image->folder . $image->url) }}'
                                                                title='{{ $image->title }}'>
                                                            <div title='Xóa' data-image-id="{{ $image->id }}"
                                                                class='position-absolute img-close'
                                                                id='{{ $rdString }}'>
                                                                <div class='btn-rm'>
                                                                    <i class='mdi mdi-delete'></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Youtube url video --}}
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label class="btn btn-block btn-social  btn-danger" data-toggle="modal"
                                                data-target="#modal-add-id-yt"><i class="mdi mdi-youtube"></i>
                                                Youtube Video</label>
                                            <input type="hidden" id="youtube_id" name="youtube_id">

                                            {{-- Image youtube video --}}
                                            @if ($post->youtube_id != '')
                                                <img id="img-youtube"
                                                    src="https://img.youtube.com/vi/{{ $post->youtube_id }}/mqdefault.jpg"
                                                    width="100%" height="auto" title="{{ $post->title }}" />
                                            @else
                                                <img id="img-youtube" style="display: none" width="100%"
                                                    height="auto" title="Image video" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Images upload end --}}

                            <div class="form-group mb-0 pt-3 text-center">
                                <div class="float-right">
                                    <button id="btn-submit-post" class="btn btn-primary mr-2" type="button"> Cập nhật
                                    </button>
                                    <a href='{{ route("admin.$table.index") }}'' class="btn btn-secondary"> Hủy </a>
                                </div>
                            </div>
                        </form>

                        <form id="form-create-images" action="{{ route('api.images.store') }}"
                            enctype='multipart/form-data'>
                            <input type="file" id="images" name="images[]" accept="image/*" multiple
                                style="display: none;">
                        </form>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->
    </div> <!-- end row-->

    {{-- Modal Youtube Link --}}
    <div id="modal-add-id-yt" class="modal fade" tabindex="-1" role="dialog"
        aria-labelledby="success-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-success">
                    <h4 class="modal-title" id="success-header-modalLabel">Nhập ID video Youtube</h4>
                    <button type="button" class="close btn-close-modal" data-dismiss="modal"
                        aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ytb_id">ID Video:
                            <small style="color: red;">*</small>
                        </label>
                        <input class="form-control" type="text" id="ytb_id" name="ytb_id"
                            placeholder="Nhập ID video Youtube" required>
                        <div class="invalid-feedback">
                            ID không được để trống
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ytb_url">Url Video:
                            <small style="color: red;">*</small>
                        </label>
                        <input class="form-control" type="text" id="ytb_url" name="ytb_url"
                            placeholder="Nhập đường dẫn video Youtube" required>
                        <div class="invalid-feedback">
                            Đường dẫn không được để trống
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-close-modal" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" id="create-id-youtube">Thêm Video</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@push('js')
    <script>
        $(function() {
            var images = $('#images');
            var arrImages = [];
            var arrRemoveImages = [];

            $('textarea').each(function() {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            }).on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            $("#loadingImages").hide();

            $("#images").change(function(event) {
                var len = images.length;
                const obj = $("#form-create-images");
                const formData = new FormData(obj[0]);
                const dir = "{{ asset(config('app.image_temp_direction')) }}" + "/";

                if (len > 0) {
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
                            $(".btn-social").attr('disable', true);
                            $('#btn-submit-post').attr('disabled', true);
                        },
                        success: function(response) {
                            $("#loadingImages").hide();
                            $("#images").val('');
                            data = response.data;
                            data.forEach(function(item) {
                                let r = (Math.random() + 1).toString(36).substring(7);

                                $('#display_image_list').append(
                                    "<div class='col-lg-6 item-img' id='" +
                                    r +
                                    "'><div class='card position-relative'><img class='img-post' width='100%' id='" +
                                    r + "' src='" + dir + item +
                                    "' title='" + item + "'><div title='" + item +
                                    "' class='position-absolute img-close' id='" +
                                    r +
                                    "'><div class='btn-rm'><i class='mdi mdi-delete'></i></div></div></div></div>"
                                );
                            })
                            if (arrImages.length == 0) {
                                arrImages = data;
                                $('#list-images').val(JSON.stringify(arrImages));
                            } else {
                                arrImages.push(...data);
                                $('#list-images').val(JSON.stringify(arrImages));
                            }
                            $(".btn-social").attr('disable', false);
                            $('#btn-submit-post').attr('disabled', false);
                        },
                        error: function(response) {
                            $("#loadingImages").hide();
                            $(".btn-social").attr('disable', false);
                            $('#btn-submit-post').attr('disabled', false);
                            notifyError(response.responseJSON.message);
                        }
                    });
                }
            });
            $(document).on('click', '.img-close', function() {
                $('#btn-submit-post').attr('disabled', true);
                $('.img-close').hide();
                $("#loadingImages").show();
                setTimeout(function() {
                    $("#loadingImages").hide();
                    $('#btn-submit-post').attr('disabled', false);
                    $('.img-close').show();
                }, 1000);

                var id = $(this).attr('id');
                var title = $(this).attr('title');
                var img_id = $(this).attr("data-image-id");

                if (img_id != undefined) {
                    arrRemoveImages.push(img_id);
                }
                arrImages = [...arrImages.filter(e => e != title)];
                $('#list-images').val(JSON.stringify(arrImages));
                $('#list-remove-images').val(JSON.stringify(arrRemoveImages));

                $('div#' + id).remove();
                $('#images').val('');
            });

            $('#city').on('change', function() {
                id = $('#city').val();
                $('#district').empty();
                $('#district').append('<option selected disabled value="">Chọn quận/huyện:</option>');
                $('#commune').empty();
                $('#commune').append('<option selected disabled value="">Chọn phường/xã:</option>');

                loadDistricts(id);
            })

            $('#district').on('change', function() {
                id = $('#district').val();
                $('#commune').empty();
                $('#commune').append('<option selected disabled value="">Chọn phường/xã:</option>');

                loadCommunes(id);
            })

            $('#btn-submit-post').on('click', function() {
                let title = $('#title').val();
                let description = $('#description').val();
                let category_id = $('#category_id').val();
                let price = $('#price').val();
                let area = $('#area').val();
                let address = $('#address').val();
                let city_id = $('#city_id').val();
                let district_id = $('#district_id').val();
                let commune_id = $('#commune_id').val();
                let name_seller = $('#name_seller').val();
                let phone_seller = $('#phone_seller').val();
                if (title == "" || description == "" || category_id == "" || price == "" || area == "" ||
                    address == "" || city_id == "" || district_id == "" || commune_id == "" || commune_id ==
                    "" || name_seller == "" || phone_seller == "") {
                    $('#btn-submit-post').submit();
                    notifyWarning('Vui lòng nhập đầy đủ thông tin !');
                } else {
                    if (arrImages.length > 0 || $('#display_image_list > div').length != 0) {
                        $('#btn-submit-post').submit();
                        $('#form-edit-post').submit();
                    } else {
                        notifyWarning('Vui lòng chọn ít nhất một ảnh !');
                    }
                }
            })

            $('#create-id-youtube').on('click', function() {
                let id = ($('#ytb_id').val()).trim();
                let url = ($('#ytb_url').val()).trim();

                if (id == '' && url == '') {
                    notifyWarning('Vui lòng nhập id hoặc đường dẫn video !');
                } else {
                    $('#modal-add-id-yt').modal('hide');
                    $(".modal input:text").val("");
                    if (id != '') {
                        validVideoId(id);
                    } else {
                        let _id = getIdFromUrl(url);
                        validVideoId(_id);
                    }
                }
            })

            $('.btn-close-modal').on('click', function() {
                $(".modal input:text").val("");
            })

            function loadDistricts(id) {
                $.ajax({
                    url: '{{ route("api.$table.load-district") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id
                    },
                    success: function(response) {
                        let str = '';
                        data = response.data;
                        data.forEach(element => {
                            $('#district').append(
                                `<option value='${element.id}'>${element.name}</option>`);
                        });
                    },
                    error: function(response) {
                        notifyError(response.responseJSON.message);
                    }
                });
            }

            function loadCommunes(id) {
                $.ajax({
                    url: '{{ route("api.$table.load-communes") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id
                    },
                    success: function(response) {
                        let str = '';
                        data = response.data;
                        data.forEach(element => {
                            $('#commune').append(
                                `<option value='${element.id}'>${element.name}</option>`);
                        });
                    },
                    error: function(response) {
                        notifyError(response.responseJSON.message);
                    }
                });
            }

            function validVideoId(id) {
                try {
                    var img = new Image();
                    img.src = "https://img.youtube.com/vi/" + id + "/mqdefault.jpg";
                    img.onload = function() {
                        if (this.width === 120) {
                            notifyError("ID không hợp lệ !");
                            $('#img-youtube').hide();
                        } else {
                            $('#youtube_id').val(id);

                            $('#img-youtube').attr('src', '');
                            $('#img-youtube').show();
                            $('#img-youtube').attr('src', 'https://img.youtube.com/vi/' + id +
                                '/mqdefault.jpg');
                        }
                    }

                } catch (error) {

                }
            }

            function getIdFromUrl(url) {
                var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
                var match = url.match(regExp);
                return (match && match[7].length == 11) ? match[7] : '';
            }
        });
    </script>
@endpush
