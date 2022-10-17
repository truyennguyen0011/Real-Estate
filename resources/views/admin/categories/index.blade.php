@extends('admin.layouts.master')
@push('css')
    <style>
        .btn-edit {
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modal-category-create">Thêm
                        danh mục</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover table-centered mb-0" id="categories-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Active</th>
                                    <th>Create At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal create category start --}}
    <div id="modal-category-create" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="edit-text">Thêm</span> danh mục</h4>
                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="form-create-category" class="form-horizontal" action="{{ route("admin.$table.store") }}"
                        method="post">
                        @csrf

                        <!-- Title start-->
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input class="form-control" type="text" id="title" autofocus="1" name="title"
                                value="" placeholder="Nhập tiêu đề" required>
                            <div class="invalid-feedback err-title">
                                Tiêu đề không được để trống
                            </div>
                            @if ($errors->has('title'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>
                            @endif
                        </div>
                        <!-- Title end-->

                        <!-- Slug start-->
                        <div class="form-group">
                            <label for="slug">Slug</label>

                            <div class="input-group mb-3">
                                <input class="form-control" type="text" id="slug" autofocus="1" name="slug"
                                    value="" placeholder="Nhập slug" required>
                                <div class="input-group-append">
                                    <button onclick="generateSlug('title', 'slug')" class="btn btn-outline-secondary"
                                        type="button">Tạo
                                        slug</button>
                                </div>
                                <div class="invalid-feedback err-slug">
                                    Slug không được để trống
                                </div>
                            </div>
                            @if ($errors->has('slug'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                                </div>
                            @endif
                        </div>
                        <!-- Slug end-->

                        <!-- Active start-->
                        <div class="form-group">
                            <label for="active">Trạng thái</label>
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
                        </div>
                        <!-- Active end-->

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-submit-create"><span
                            class="edit-text">Thêm</span></button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal create category end --}}

    {{-- Modal edit category start --}}
    <div id="modal-category-edit" class="modal fade" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sửa danh mục</h4>
                    <button type="button" class="close float-right" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="form-edit-category" class="form-horizontal" action="{{ route("admin.$table.update") }}"
                        method="post">
                        @csrf
                        @method('PATCH')
                        {{-- id --}}
                        <input type="hidden" name="id" id="id2">
                        <!-- Title start-->
                        <div class="form-group">
                            <label for="title">Tiêu đề</label>
                            <input class="form-control" type="text" id="title2" autofocus="1" name="title"
                                placeholder="Nhập tiêu đề" required>
                            <div class="invalid-feedback err-title2">
                                Tiêu đề không được để trống
                            </div>
                            @if ($errors->has('title'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>
                            @endif
                        </div>
                        <!-- Title end-->

                        <!-- Slug start-->
                        <div class="form-group">
                            <label for="slug">Slug</label>

                            <div class="input-group mb-3">
                                <input class="form-control" type="text" id="slug2" autofocus="1" name="slug"
                                    placeholder="Nhập slug" required>
                                <div class="input-group-append">
                                    <button onclick="generateSlug('title2', 'slug2')" class="btn btn-outline-secondary"
                                        type="button">Tạo
                                        slug</button>
                                </div>
                                <div class="invalid-feedback err-slug2">
                                    Slug không được để trống
                                </div>
                            </div>
                            @if ($errors->has('slug'))
                                <div class="alert alert-danger mt-1">
                                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                                </div>
                            @endif
                        </div>
                        <!-- Slug end-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btn-submit-edit">Sửa</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal edit category end --}}
@endsection

@push('js')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script> --}}
    <script>
        // Generate Slug if admin added title
        function generateSlug(t, s) {
            title = ($('#' + t).val()).trim();

            if (title == '') {
                notifyWarning('Vui lòng nhập tiêu đề !');
            } else {
                $.ajax({
                    url: '{{ route("api.$table.slug.generate") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        title
                    },
                    success: function(response) {
                        $("#" + s).val(response.data);
                        $("#" + s).trigger("change");
                    },
                    error: function(response) {
                        notifyError(response.responseJSON.message);
                    }
                });
            }
        }

        $(function() {
            // Datatables start //
            let table = $('#categories-table').DataTable({
                order: [[ 0, "desc" ]],
                processing: true,
                serverSide: true,
                ajax: '{!! route("api.$table") !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title',
                    },
                    {
                        data: 'slug',
                        name: 'slug',
                    },
                    {
                        data: 'active',
                        name: 'active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            object = JSON.parse(data);
                            return `<div>
                                <input class="chbox-active" type="checkbox" id="${object.id}" data-switch="success" ${object.active == 1 ? 'checked' : ''} />
                                    <label for="${object.id}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                            </div>`;
                        }
                    },
                    {
                        orderable: false,
                        searchable: false,
                        data: 'created_at',
                        name: 'created at',
                    },
                    {
                        class: 'table-action',
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="d-flex"><a title="Sửa" data-title="${data.category.title}" data-slug="${data.category.slug}" data-id="${data.category.id}" class="action-icon btn-edit mr-1"> <i class="mdi mdi-pencil"></i></a>
                                <form class="d-inline-block form-delete-admin" action="${data.routeDelete}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button style="outline: none; background-color: transparent; border: none"
                                        type="button" title="Xóa"
                                        class="action-icon text-danger btn-delete-admin">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                            </form></div>`;
                        }
                    },
                ]
            });
            // Datatables end // 

            // Change active start //
            $(document).on('change', '.chbox-active', function(e) {
                e.preventDefault();

                $('.chbox-active').attr('disabled', true);
                setTimeout(function() {
                    $('.chbox-active').attr('disabled', false);
                }, 2000);

                status = this.checked ? 1 : 0;
                id = this.id;
                $.ajax({
                    type: "POST",
                    url: '{{ route("admin.$table.change-active") }}',
                    data: {
                        '_token': $('input[name=_token]').val(),
                        'id': id,
                        'status': status
                    },
                    success: function(response) {
                        notifySuccess(response.message);
                    },
                    error: function(response) {
                        notifyError(response.responseJSON.message);
                        table.draw();
                    },
                });
            })
            // Change active end //

            $(document).on('click', '.btn-delete-admin', function() {
                let form = $(this).parents('form');

                if (confirm('Bạn có chắc muốn xóa danh mục này không?')) {
                    $.ajax({
                        url: form.attr('action'),
                        type: "POST",
                        data: form.serialize(),
                        dataType: "json",
                        success: function(response) {
                            notifySuccess(response.message);
                            table.draw();
                        },
                        error: function(response) {
                            notifyError(response.responseJSON.message);
                        },
                    });
                }
            });

            // Button submit create start //
            $(document).on('click', '#btn-submit-create', function() {
                const obj = $("#form-create-category");
                const formData = new FormData(obj[0]);
                let title = $('#title').val();
                let slug = $('#slug').val();
                if (title == '') {
                    $('.err-title').show();
                } else {
                    $('.err-title').hide();
                }
                if (slug == '') {
                    $('.err-slug').show();
                } else {
                    $('.err-slug').hide();
                }

                if (title != '' && slug != '') {
                    $.ajax({
                        url: obj.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        async: false,
                        cache: false,
                        success: function(response) {
                            $("#modal-category-create").modal("hide");
                            notifySuccess(response.message);
                            table.draw();
                            $('#title').val('');
                            $('#slug').val('');
                        },
                        error: function(response) {
                            notifyError(response.responseJSON.message);
                        }
                    });
                }
            });
            // Button submit create end //

            // Button submit edit start //
            $(document).on('click', '#btn-submit-edit', function() {
                const obj = $("#form-edit-category");
                const formData = new FormData(obj[0]);
                let title = $('#title2').val();
                let slug = $('#slug2').val();
                let id = $('#id2').val();
                if (title == '') {
                    $('.err-title2').show();
                } else {
                    $('.err-title2').hide();
                }
                if (slug == '') {
                    $('.err-slug2').show();
                } else {
                    $('.err-slug2').hide();
                }

                if (title != '' && slug != '') {
                    $.ajax({
                        url: obj.attr('action'),
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        async: false,
                        cache: false,
                        success: function(response) {
                            $("#modal-category-edit").modal("hide");
                            notifySuccess(response.message);
                            table.draw();
                            $('#title2').val('');
                            $('#slug2').val('');
                            $('#id2').val('');
                        },
                        error: function(response) {
                            notifyError(response.responseJSON.message);
                        }
                    });
                }
            });
            // Button submit edit end //

            // Buton edit category start //
            $(document).on('click', '.btn-edit', function(e) {
                let id = $(this).data('id');
                let title = $(this).data('title');
                let slug = $(this).data('slug');

                $('#id2').val(id);
                $('#title2').val(title);
                $('#slug2').val(slug);
                $("#modal-category-edit").modal("show");
            });

            // Buton edit category end //
        });

        $(document).ready(async function() {
            $("#slug").change(function() {
                $("#btn-submit-create").attr('disabled', true);
                $.ajax({
                    url: '{{ route("api.$table.slug.check") }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        slug: $(this).val()
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#btn-submit-create").attr('disabled', false);
                            notifyInfo(response.message);
                        }
                    },
                    error: function(response) {
                        notifyError(response.responseJSON.message);
                    }
                });
            });
            $(".close").click(function (e) { 
                e.preventDefault();
                $(".modal input:text").val("");
                $('.invalid-feedback').hide();
                $('#id2').val("");
            });
        });
    </script>
@endpush
