@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ route("admin.$table.create") }}">Thêm Admin</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-hover table-centered mb-0" id="admins-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Role</th>
                                    <th>Active</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Datatables start //
            let table = $('#admins-table').DataTable({
                order: [[ 0, "desc" ]],
                processing: true,
                serverSide: true,
                ajax: '{!! route("admin.$table.api") !!}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'contact',
                        name: 'contact',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            object = JSON.parse(data);
                            return `<a href="mailto:${object.email}">
                                            ${object.email}
                                    </a>
                                    <br>
                                    <a href="tel:${object.phone}">
                                        ${object.phone}
                                    </a>`;
                        }
                    },
                    {
                        data: 'role',
                        name: 'role',
                    },
                    {
                        data: 'active',
                        name: 'active',
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
                        class: 'table-action',
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="d-flex"><a title="Sửa" href="${data.routeEdit}" class="action-icon mr-1"> <i class="mdi mdi-pencil"></i></a>
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
                    url: "{{ route("admin.$table.change-active") }}",
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

            $(document).on('click', '.btn-delete-admin', function() {
                let form = $(this).parents('form');

                if (confirm('Bạn có chắc muốn xóa người này không?')) {
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
        });
    </script>
@endpush
