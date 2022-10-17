<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Home' }} - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ config('app.description') }}" name="description" />
    <meta content="{{ config('app.name') }}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/app-creative.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Datatable -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fc-4.1.0/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sl-1.4.0/datatables.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('css')
</head>

<body class="">
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('admin.layouts.topbar')
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">
                    @if ($errors->has('error-serious'))
                        <!-- Danger Alert Modal -->
                        <button hidden type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#danger-alert-modal" id="btn-showerror">Danger Alert</button>

                        <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content modal-filled bg-danger">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="dripicons-wrong h1"></i>
                                            <h4 class="mt-2">Oh không!</h4>
                                            <p class="mt-3">{{ $errors->first('error-serious') }}</p>
                                            <button type="button" class="btn btn-light my-2"
                                                data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    @endif
                    <!--<nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-light-lighten p-2 mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                        class="uil-home-alt"></i>Dashboard</a></li>

                            @foreach ($segments = request()->segments() as $index => $segment)
                                <li class="breadcrumb-item {{ $index == count($segments) - 1 ? 'active' : '' }}">
                                    @if ($index != count($segments) - 1)
                                        <a href="{{ url(implode(array_slice($segments, 0, $index + 1), '/')) }}">
                                            {{ ucfirst($segment) }}
                                        </a>
                                    @else
                                        {{ ucfirst($segment) }}
                                    @endif

                                </li>
                            @endforeach
                            {{-- <li class="breadcrumb-item active" aria-current="page">Data</li> --}}
                        </ol>
                    </nav>-->
                    @yield('content')
                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            @include('admin.layouts.footer')
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- bundle -->
    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/helper.js') }}"></script>

    <!-- Datatable script -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/date-1.1.2/fc-4.1.0/fh-3.2.4/r-2.3.0/rg-1.2.0/sc-2.0.7/sb-1.3.4/sl-1.4.0/datatables.min.js">
    </script>


    @if ($errors->has('error-serious'))
        <script type="text/javascript">
            document.getElementById("btn-showerror").click();
        </script>
    @endif
    @if ($errors->has('my-error'))
        <script type="text/javascript">
            notifyError('{{ $errors->first('my-error') }}');
        </script>
    @endif
    @if (Session::has('my-success'))
        <script type="text/javascript">
            notifySuccess('{{ Session::get('my-success') }}');
        </script>
    @endif
    @stack('js')

</body>

</html>
