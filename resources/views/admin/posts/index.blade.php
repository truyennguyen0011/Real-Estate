@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-primary" href="{{ route("admin.$table.create") }}">Đăng tin</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if (count($posts) > 0)
                        <table class="table table-hover table-centered mb-0" id="posts-table">
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
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('post', $post->slug) }}" target="_blank">
                                                @isset($post->thumb_image)
                                                    @if ($post->thumb_image == '')
                                                        <img width="100" height="100"
                                                            src="{{ asset('images/no-image.png') }}" alt="No image"
                                                            title="No image">
                                                    @else
                                                        <div class="position-relative">
                                                            <div class="position-absolute img-id">{{ $post->id }}</div>
                                                            <img width="100" height="100"
                                                                src="{{ asset(config('app.image_post_thumb_direction')) . '/' . $post->thumb_image }}"
                                                                alt="{{ $post->title }}" title="{{ $post->title }}">
                                                            <div class="position-absolute img-created-at">
                                                                {{ $post->date_and_hour_vn }}</div>
                                                        </div>
                                                    @endif
                                                @endisset
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('post', $post->slug) }}" target="_blank" class="text-truncate-3">
                                                {{ $post->title }}
                                            </a>
                                        </td>
                                        @isset($user)
                                            @if ($user->role == $roles['MASTER'])
                                                <td>{{ optional($post->administrator)->name }}</td>
                                            @endif
                                        @endisset
                                        <td>
                                            <div>
                                                <input class="chbox-active" type="checkbox" id="{{ $post->id }}"
                                                    data-switch="success" {{ $post->active ? 'checked' : '' }} />
                                                <label for="{{ $post->id }}" data-on-label=" Hiện "
                                                    data-off-label=" Ẩn " class="mb-0 d-block"></label>
                                            </div>
                                        </td>

                                        <td>
                                            <button id="{{ $post->id }}" title="Làm mới" type="button"
                                                data-date-publish="{{ $post->published_at }}"
                                                class="btn btn-sm btn-primary refresh-post"> <i class="mdi mdi-refresh"></i>
                                                <span class="d-none d-md-inline-block">Làm mới</span>
                                            </button>
                                        </td>
                                        <td class="table-action">
                                            <div class="d-flex">
                                                <a title="Sửa" href='{{ route("admin.$table.edit", $post) }}'
                                                    class="action-icon mr-1"><i class="mdi mdi-pencil"></i></a>
                                                <form action="{{ route("admin.$table.destroy", $post) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        onclick="return confirm('Bạn có chắc muốn xóa người này không?')"
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
                            <h1 class="text-center">Không có bài viết nào</h1>
                        @endif
                        
                        <nav>
                            <ul class="pagination pagination-rounded mb-0">
                                {{ $posts->links() }}
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
            $(document).on('click', '.refresh-post', function() {

                $('.refresh-post').attr('disabled', true);
                setTimeout(function() {
                    $('.refresh-post').attr('disabled', false);
                }, 2000);

                id = this.id;

                $.ajax({
                    type: "POST",
                    url: '{{ route("admin.$table.refresh-post") }}',
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
