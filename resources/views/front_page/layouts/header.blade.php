<header class="header">
    <!-- Navbar here -->
    <nav class="navbar navbar-default {{ $navTransparent ?? '' }} navbar-fixed-top {{ $navColorScrollOnTop ?? '' }}"
        color-on-scroll=" " id="sectionsNav">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img height="36px" src="{{ asset(config('app.logo_image')) }}" alt="{{ config('app.name') }}">
                </a>
            </div>

            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('home') . '/' . $category->slug }}">
                                {{ $category->title }}
                            </a>
                        </li>
                    @endforeach

                    <!-- Contact -->
                    <li>
                        <a href="{{ route('contact') }}">
                            Liên hệ
                        </a>
                    </li>
                    <!-- Contact end -->

                    <!-- News -->
                    <li>
                        <a href="{{ route('news') }}">
                            Tin tức
                        </a>
                    </li>
                    <!-- News end -->

                    <!-- Search modal -->
                    <li class="button-container" id="btn-search" style="margin-right: 5px;">
                        <a title="Tìm kiếm" class="btn btn-fab btn-fab-mini" data-toggle="modal"
                            data-target="#searchModal">
                            <i class="material-icons">search</i>
                        </a>
                    </li>
                    <!-- Search modal end -->

                    @if (isset($user))
                        <li class="button-container">
                            <a href="{{ route('admin.posts.create') }}" class="btn btn-rose btn-round">
                                <i class="material-icons">edit</i> Đăng tin
                            </a>
                        </li>
                    @else
                        <li class="button-container">
                            <a href="{{ route('admin.login') }}" class="btn btn-rose btn-round">
                                <i class="material-icons">login</i> Đăng nhập
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navbar -->
    @stack('header_page')

</header>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
                <h4 class="modal-title text-center">Bạn muốn tìm gì ?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-form-horizontal" style="margin-top: 10px">
                            <div class="card-content">
                                <form method="GET" action="{{ route('search') }}">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="material-icons">search</i>
                                                </span>
                                                <div class="form-group is-empty">
                                                    <input id="input-search" name="q" type="text" placeholder="Nhập thông tin cần tìm..." class="form-control">
                                                    <span class="material-input"></span>
                                                    <span class="material-input"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <button type="submit" class="btn btn-rose btn-block">
                                                Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!--  End Modal -->