@extends('admin.layouts.master')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-6 col-lg-6">

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card widget-flat bg-success text-white">
                            <div class="card-body">
                                <div class="float-right">
                                    <i class="mdi mdi-account-multiple widget-icon bg-success-lighten text-success"></i>
                                </div>
                                <h5 class="font-weight-normal mt-0" title="Quản lý bài đăng">Bài đăng</h5>
                                <h3 class="mt-3 mb-3">{{ $totalPost ?? 0 }}</h3>
                                <a href="{{ route('admin.posts.index') }}" type="button" class="btn btn-light"><i
                                        class="mdi mdi-cogs mr-1"></i>
                                    <span>Quản lý</span> </a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-lg-6">
                        <div class="card widget-flat bg-info text-white">
                            <div class="card-body">
                                <div class="float-right">
                                    <i class="mdi mdi-cart-plus widget-icon bg-danger-lighten text-danger"></i>
                                </div>
                                <h5 class="font-weight-normal mt-0" title="Quản lý tin tức">Tin tức</h5>
                                <h3 class="mt-3 mb-3">{{ $totalNews ?? 0 }}</h3>
                                <a href="{{ route('admin.news.index') }}" type="button" class="btn btn-warning"><i class="mdi mdi-cogs mr-1"></i>
                                    <span>Quản lý</span> </a>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row -->
            </div> <!-- end col -->

            @if (isset($isMaster))
                <div class="col-xl-6  col-lg-6">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card widget-flat bg-light">
                                <div class="card-body">
                                    <div class="float-right">
                                        <i class="mdi mdi-cart-plus widget-icon bg-danger-lighten text-danger"></i>
                                    </div>
                                    <h5 class="font-weight-normal mt-0" title="Quản lý danh mục">Danh mục</h5>
                                    <h3 class="mt-3 mb-3">{{ $totalCategories ?? 0 }}</h3>
                                    <a href="{{ route('admin.categories.index') }}" type="button" class="btn btn-info"><i
                                            class="mdi mdi-cogs mr-1"></i>
                                        <span>Quản lý</span> </a>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->

                        <div class="col-lg-6">
                            <div class="card widget-flat">
                                <div class="card-body">
                                    <div class="float-right">
                                        <i class="mdi mdi-account-multiple widget-icon bg-success-lighten text-success"></i>
                                    </div>
                                    <h5 class="text-muted font-weight-normal mt-0" title="Quản lý admin">Admin
                                    </h5>
                                    <h3 class="mt-3 mb-3">{{ $totalAdmins ?? 0 }}</h3>
                                    <a href="{{ route('admin.administrators.index') }}" type="button" class="btn btn-danger"><i
                                            class="mdi mdi-cogs mr-1"></i>
                                        <span>Quản lý</span> </a>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div> <!-- end row -->
                </div> <!-- end col -->
            @else
            @endif

        </div>
        <!-- end row -->
    </div>
    <!-- container -->

    </div>
    <!-- content -->
@endsection

@push('js')
    <script></script>
@endpush
