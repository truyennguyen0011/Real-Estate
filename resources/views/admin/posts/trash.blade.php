@extends('admin.layouts.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        @if ($data->count() == 0)
                            <a class="btn btn-primary" href="{{ route("admin.$table.index") }}">Quản lý bài đăng</a>
                            <h2 class="mt-3 text-center">Không có dữ liệu</h2>
                        @else
                            <form class="d-inline-block" action="{{ route("admin.$table.restore-all") }}" method="post">
                                @csrf
                                <button class="btn btn-primary mb-3" type="submit">Khôi phục tất cả</button>
                            </form>
                            <nav class="float-right">
                                <ul class="pagination pagination-rounded mb-0" id="pagination">
                                    {{ $data->links() }}
                                </ul>
                            </nav>
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th width="40%">Tiêu đề</th>
                                        <th>Ngày xóa</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $each)
                                        <tr>
                                            <td>
                                                @isset($each->thumb_image)
                                                    @if ($each->thumb_image == '')
                                                        <img width="100" height="100"
                                                            src="{{ asset('images/no-image.png') }}" alt="No image"
                                                            title="No image">
                                                    @else
                                                        <div class="position-relative">
                                                            <div class="position-absolute img-id">{{ $each->id }}</div>
                                                            <img width="100" height="100"
                                                                src="{{ asset(config('app.image_post_thumb_direction')) . '/' . $each->thumb_image }}"
                                                                alt="{{ $each->title }}" title="{{ $each->title }}">
                                                            <div class="position-absolute img-created-at">
                                                                {{ $each->date_and_hour_vn }}</div>
                                                        </div>
                                                    @endif
                                                @endisset
                                            </td>
                                            <td>
                                                {{ $each->title }}
                                            </td>
                                            <td>
                                                {{ $each->deleted_at_vn }}
                                            </td>
                                            {{-- optional() --}}
                                            <td class="table-action">
                                                <div class="d-flex">
                                                    <form class="d-inline-block mb-1 mr-1"
                                                        action="{{ route("admin.$table.restore", $each) }}" method="post">
                                                        @csrf
                                                        <button title="Khôi phục" type="submit" class="btn btn-success"> <i
                                                                class="mdi mdi-backup-restore"></i>
                                                            <span class="d-none d-md-inline-block">Khôi phục</span>
                                                        </button>
                                                    </form>
                                                    <form class="d-inline-block"
                                                        action="{{ route("admin.$table.force-delete", $each) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn bài đăng này không?')"
                                                            type="submit" title="Xóa vĩnh viễn" class="btn btn-danger">
                                                            <i class="mdi mdi-delete-forever"></i>
                                                            <span class="d-none d-md-inline-block">Xóa vĩnh viễn</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination pagination-rounded mb-0">
                                    {{ $data->links() }}
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection