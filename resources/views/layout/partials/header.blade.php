<!-- Topbar Start -->
<header class="navbar-header">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">

            <!-- Logo -->
            <a href="{{url('/')}}" class="logo">

                <!-- Logo Normal -->
                <span class="logo-light">
                    <span class="logo-lg"><img src="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}" alt="logo"></span>
                    <span class="logo-sm"><img src="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}" alt="small logo"></span>
                </span>

                <!-- Logo Dark -->
                <span class="logo-dark">
                    <span class="logo-lg"><img src="{{URL::asset('build/img/logo-white.svg')}}" alt="dark logo"></span>
                </span>
            </a>

            <!-- Sidebar Mobile Button -->
            <a id="mobile_btn" class="mobile-btn" href="#sidebar">
                <i class="ti ti-menu-deep fs-24"></i>
            </a>

            @if (!Route::is(['layout-hidden']))
            <button class="sidenav-toggle-btn btn border-0 p-0" id="toggle_btn2">
                <i class="ti ti-arrow-bar-to-right"></i>
            </button>
            @endif

            @if (Route::is(['layout-hidden']))
            <button class="sidenav-toggle-btn btn border-0 p-0" id="toggle_btn">
                <i class="ti ti-arrow-bar-to-right"></i>
            </button>
            @endif

            <!-- Search -->
            {{-- <div class="me-auto d-flex align-items-center header-search d-lg-flex d-none">
                <!-- Search -->
                <div class="input-icon position-relative me-2">
                    <input type="text" class="form-control" placeholder="Search Keyword">
                    <span class="input-icon-addon d-inline-flex p-0 header-search-icon"><i class="ti ti-command"></i></span>
                </div>
                <!-- /Search -->
            </div> --}}

        </div>

        <div class="d-flex align-items-center">

            <!-- Beranda -->
            <div class="header-item me-2">
                <a href="{{ route('landing') }}"
                    class="btn btn-outline-primary px-3 py-2 d-flex align-items-center">

                    <i class="ti ti-home me-2"></i>
                    <span class="fw-semibold">
                        Kembali ke Halaman Awal
                    </span>

                </a>
            </div>

            <!-- Minimize -->
            <div class="header-item">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="btn topbar-link btnFullscreen"><i class="ti ti-maximize"></i></a>
                </div>
            </div>
            <!-- Minimize -->

            @if (!Route::is(['layout-mini', 'layout-hoverview', 'layout-hidden', 'layout-fullwidth', 'layout-rtl', 'layout-dark']))
            <!-- Light/Dark Mode Button -->
            <div class="header-item d-none d-sm-flex me-2">
                <button class="topbar-link btn topbar-link" id="light-dark-mode" type="button">
                    <i class="ti ti-moon fs-16"></i>
                </button>
            </div>
            @endif

            <div class="header-line"></div>

            <!-- User Dropdown -->
            <div class="dropdown profile-dropdown d-flex align-items-center justify-content-center">
                <a href="javascript:void(0);" class="topbar-link dropdown-toggle drop-arrow-none position-relative"
                data-bs-toggle="dropdown" data-bs-offset="0,22">

                    <i class="ti ti-user-circle fs-1"></i>

                    <span class="online text-success">
                        <i class="ti ti-circle-filled d-flex bg-white rounded-circle border border-1 border-white"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">

                    <div class="d-flex align-items-center bg-light rounded-3 p-2 mb-2">
                        {{-- <img src="{{URL::asset('build/img/users/user-40.jpg')}}" class="rounded-circle" width="42" height="42" alt=""> --}}
                        <div class="ms-2">
                            <p class="fw-medium text-dark mb-0">{{ Auth::user()->name }}</p>
                            {{-- <span class="d-block fs-13">Installer</span> --}}
                        </div>
                    </div>

                    <!-- Item-->
                    <div class="pt-2 mt-2 border-top">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">
                                <i class="ti ti-logout me-1 fs-17 align-middle"></i>
                                <span class="align-middle">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
<!-- Topbar End -->
