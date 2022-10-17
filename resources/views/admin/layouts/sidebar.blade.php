<div class="left-side-menu">

    <!-- LOGO -->
    <a title="Quản lý bài đăng" href="{{ route('admin.posts.index') }}" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="48">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="24">
        </span>
    </a>

    <div class="h-100" id="left-side-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="metismenu side-nav">

            <li class="side-nav-title side-nav-item">Menu</li>

            {{-- Home start --}}
            <li class="side-nav-item">
                <a title="Dashboard" href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            {{-- Home end --}}

            {{-- Setting website start --}}
            @isset($user)
                @if ($user->role == $roles['MASTER'])
                    <li class="side-nav-item">
                        <a title="Cài đặt Website" href="{{ route('admin.setting-website') }}" class="side-nav-link">
                            <i class="mdi mdi-settings"></i>
                            <span> Cấu hình Website </span>
                        </a>
                    </li>
                @endif
            @endisset
            {{-- Setting website end --}}

            {{-- Manage start --}}
            <li class="side-nav-item">
                <a title="Quản lý Website" href="#" class="side-nav-link">
                    <i class="mdi mdi-cogs"></i>
                    <span> Quản lý </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    @isset($user)
                        @if ($user->role == $roles['MASTER'])
                            <li>
                                <a title="Quản lý admin" href="{{ route('admin.administrators.index') }}">
                                    <i class="uil-users-alt"></i>
                                    <span> Quản trị viên </span>
                                </a>
                            </li>
                            <li>
                                <a title="Quản lý danh mục" href="{{ route('admin.categories.index') }}">
                                    <i class="mdi mdi-format-list-bulleted-triangle"></i>
                                    <span> Danh mục </span>
                                </a>
                            </li>
                        @endif
                    @endisset
                    <li>
                        <a title="Quản lý bài đăng" href="{{ route('admin.posts.index') }}">
                            <i class="mdi mdi-post-outline"></i>
                            <span> Bài đăng </span>
                        </a>
                    </li>
                    <li>
                        <a title="Quản lý tin tức" href="{{ route('admin.news.index') }}">
                            <i class="mdi mdi-newspaper-variant-outline"></i>
                            <span> Tin tức </span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- Manage end --}}

            {{-- Trash start --}}
            <li class="side-nav-item">
                <a title="Thùng rác" href="#" class="side-nav-link">
                    <i class="mdi mdi-trash-can-outline"></i>
                    <span> Thùng rác </span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="side-nav-second-level" aria-expanded="false">
                    @isset($user)
                        @if ($user->role == $roles['MASTER'])
                            <li>
                                <a title="Quản trị viên đã xóa" href="{{ route('admin.administrators.trash') }}">
                                    <i class="uil-users-alt"></i>
                                    <span> Quản trị viên </span>
                                </a>
                            </li>
                            <li>
                                <a title="Danh mục đã xóa" href="{{ route('admin.categories.trash') }}">
                                    <i class="mdi mdi-format-list-bulleted-triangle"></i>
                                    <span> Danh mục </span>
                                </a>
                            </li>
                        @endif
                    @endisset

                    <li>
                        <a title="Bài đăng đã xóa" href="{{ route('admin.posts.trash') }}">
                            <i class="mdi mdi-post-outline"></i>
                            <span> Bài đăng </span>
                        </a>
                    </li>
                    <li>
                        <a title="Tin tức đã xóa" href="{{ route('admin.news.trash') }}">
                            <i class="mdi mdi-newspaper-variant-outline"></i>
                            <span> Tin tức </span>
                        </a>
                    </li>
                </ul>
            </li>
            {{-- Trash end --}}
            {{-- mdi mdi-settings-outline --}}
        </ul>
        {{-- ================================ --}}
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
