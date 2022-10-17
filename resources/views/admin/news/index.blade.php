@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ route("admin.$table.create") }}">Đăng tin tức</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (count($news) > 0)
                            <table class="table table-hover table-centered mb-0" id="news-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th width="30%">Tiêu đề</th>
                                        @isset($user)
                                            @if ($user->role == $roles['MASTER'])
                                                <th>Người đăng</th>
                                            @endif
                                        @endisset
                                        <th>Trạng thái</th>
                                        <th width="20%">Tùy chọn</th>
                                        <th>Sửa/Xóa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($news as $new)
                                        <tr>
                                            <td>
                                                <a href="" target="_blank">
                                                    @isset($new->image_thumb)
                                                        @if ($new->image_thumb == '')
                                                            <img width="100" height="100"
                                                                src="{{ asset('images/no-image.png') }}" alt="No image"
                                                                title="No image">
                                                        @else
                                                            <div class="position-relative">
                                                                <div class="position-absolute img-id">{{ $new->id }}</div>
                                                                <img width="100" height="100"
                                                                    src="{{ asset(config('app.image_new_thumb_direction')) . '/' . $new->image_thumb }}"
                                                                    alt="{{ $new->title }}" title="{{ $new->title }}">
                                                                <div class="position-absolute img-created-at">
                                                                    {{ $new->date_and_hour_vn }}</div>
                                                            </div>
                                                        @endif
                                                    @endisset
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" target="_blank" class="text-truncate-3">
                                                    {{ $new->title }}
                                                </a>
                                            </td>
                                            @isset($user)
                                                @if ($user->role == $roles['MASTER'])
                                                    <td>{{ optional($new->administrator)->name }}</td>
                                                @endif
                                            @endisset
                                            <td>
                                                <div>
                                                    <input class="chbox-active" type="checkbox" id="{{ $new->id }}"
                                                        data-switch="success" {{ $new->active ? 'checked' : '' }} />
                                                    <label for="{{ $new->id }}" data-on-label=" Hiện "
                                                        data-off-label=" Ẩn " class="mb-0 d-block"></label>
                                                </div>
                                            </td>

                                            <td>
                                                <button id="{{ $new->id }}" title="Làm mới" type="button"
                                                    data-date-publish="{{ $new->published_at }}"
                                                    class="btn btn-sm btn-primary refresh-new"> <i
                                                        class="mdi mdi-refresh"></i>
                                                    <span class="d-none d-md-inline-block">Làm mới</span>
                                                </button>
                                            </td>
                                            <td class="table-action">
                                                <div class="d-flex">
                                                    <a title="Sửa" href='{{ route("admin.$table.edit", $new) }}'
                                                        class="action-icon mr-1"><i class="mdi mdi-pencil"></i></a>
                                                    <form action="{{ route("admin.$table.destroy", $new) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            onclick="return confirm('Bạn có chắc muốn xóa tin này không?')"
                                                            style="outline: none; background-color: transparent; border: none"
                                                            type="submit" title="Xóa"
                                                            class="action-icon text-danger btn-delete-admin">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        @else
                            <h1 class="text-center">Không có tin tức nào</h1>
                        @endif
                        <nav>
                            <ul class="pagination pagination-rounded mb-0">
                                {{ $news->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            // Change active
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
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                });
            })

            // Refresh post
            $(document).on('click', '.refresh-new', function() {

                $('.refresh-new').attr('disabled', true);
                setTimeout(function() {
                    $('.refresh-new').attr('disabled', false);
                }, 2000);

                id = this.id;

                $.ajax({
                    type: "POST",
                    url: '{{ route("admin.$table.refresh-new") }}',
                    data: {
                        '_token': $('input[name=_token]').val(),
                        'id': id,
                    },
                    success: function(response) {
                        notifySuccess(response.message);
                    },
                    error: function(response) {
                        if (response.responseJSON.data == 'warning') {
                            notifyWarning(response.responseJSON.message);
                        } else {
                            notifyError(response.responseJSON.message);
                        }
                    },
                });

            })
        });
    </script>
@endpush
