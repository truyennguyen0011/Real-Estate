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
                            <a class="btn btn-primary" href="{{ route("admin.$table.index") }}">Quản lý admin</a>
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
                                        <th>#</th>
                                        <th>Avatar</th>
                                        <th>Info</th>
                                        <th>Role</th>
                                        <th>Deleted At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $each)
                                        <tr>
                                            <td>
                                                <a href="">
                                                    {{ $each->id }}
                                                </a>
                                            </td>
                                            <td>
                                                <img src="{{ asset(config('app.image_avatar_direction') . ($each->avatar ? $each->avatar : 'no-avatar.png')) }}"
                                                    height="100">
                                            </td>
                                            <td>
                                                {{ $each->name }}
                                                <br>
                                                <a href="mailto:{{ $each->email }}">
                                                    {{ $each->email }}
                                                </a>
                                                <br>
                                                <a href="tel:{{ $each->phone }}">
                                                    {{ $each->phone }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $each->role_name }}
                                            </td>
                                            <td>
                                                <!-- Switch-->
                                                <div>
                                                    {{ $each->deleted_at_vn }}
                                                </div>
                                            </td>
                                            {{-- optional() --}}
                                            <td class="table-action">
                                                <div class="d-flex">
                                                    <form class="mb-1 mr-1"
                                                        action="{{ route("admin.$table.restore", $each) }}" method="post">
                                                        @csrf
                                                        <button title="Khôi phục" type="submit" class="btn btn-success"> <i
                                                                class="mdi mdi-backup-restore"></i>
                                                            <span class="d-none d-md-inline-block">Khôi phục</span>
                                                        </button>
                                                    </form>
                                                    <form class=""
                                                        action="{{ route("admin.$table.force-delete", $each) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            onclick="return confirm('Bạn có chắc muốn xóa vĩnh viễn người này không?')"
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

@push('js')
    <script></script>
@endpush
