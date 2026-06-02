    <!-- Search Modal -->
    <div class="modal fade" id="searchModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent">
                <div class="card shadow-none mb-0">
                    <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                        <i class="ti ti-search fs-22"></i>
                        <input type="search" class="form-control border-0" placeholder="Search">
                        <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-22"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidenav Menu Start -->
    <div class="sidebar" id="sidebar">

        <!-- Start Logo -->
        <div class="sidebar-logo">
            <div>
                <!-- Logo Normal -->
                <a href="{{ url('/') }}" class="logo logo-normal">
                    <img src="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}" alt="Logo" style="height: 50px;">
                    Ayah Racing Jaya
                </a>

                <!-- Logo Small -->
                <a href="{{url('/')}}" class="logo-small">
                     <img src="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}" alt="Logo" style="height: 50px;">
                     Ayah Racing Jaya
                </a>

                <!-- Logo Dark -->
                <a href="{{url('/')}}" class="dark-logo">
                     <img src="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}" alt="Logo" style="height: 50px;">
                     Ayah Racing Jaya
                </a>
            </div>
            <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn">
                <i class="ti ti-arrow-bar-to-left"></i>
            </button>

            <!-- Sidebar Menu Close -->
            <button class="sidebar-close">
                <i class="ti ti-x align-middle"></i>
            </button>
        </div>
        <!-- End Logo -->

        <!-- Sidenav Menu -->
        <div class="sidebar-inner" data-simplebar>
            <div id="sidebar-menu" class="sidebar-menu">

                <ul>
                    {{-- <li class="menu-title"><span>Menu</span></li>
                    <li>
                        <ul>
                            <li class=" {{ Request::is('contacts', 'contacts-list', 'contact-details') ? 'active' : '' }}">
                                <a href="{{ route('dashboard') }}" ><i class="ti ti-dashboard"></i><span>Dashboard</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-title"><span>Data Management</span></li> --}}
                    <li>
                        <ul>

                            {{-- Hanya untuk superadmin & admin --}}
                            @if(in_array(auth()->user()->role, ['superadmin', 'admin']))
                                <li class="{{ Request::is('events-list', 'event-details') ? 'active' : '' }}">
                                    <a href="{{ route('events-list') }}">
                                        <i class="ti ti-calendar"></i>
                                        <span>Events</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('manage-users') ? 'active' : '' }}">
                                    <a href="{{ route('manage-users') }}">
                                        <i class="ti ti-users"></i>
                                        <span>Users</span>
                                    </a>
                                </li>
                                <li class="{{ Request::is('regulations') ? 'active' : '' }}">
                                    <a href="{{ route('regulations') }}">
                                        <i class="ti ti-hammer"></i>
                                        <span>Regulasi</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Hanya untuk user --}}
                            @if(auth()->user()->role == 'user')
                                <li class="{{ Request::is('events') ? 'active' : '' }}">
                                    <a href="{{ route('events') }}">
                                        <i class="ti ti-calendar"></i>
                                        <span>Pendaftaran Event</span>
                                    </a>
                                </li>

                                <li class="{{ Request::routeIs('events-payment') && request('type') == 'pending' ? 'active' : '' }}">
                                    <a href="{{ route('events-payment', ['type' => 'pending']) }}">
                                        <i class="ti ti-cash-banknote"></i>
                                        <span>Pembayaran Pending</span>
                                    </a>
                                </li>

                                <li class="{{ Request::routeIs('events-payment') && request('type') == 'lunas' ? 'active' : '' }}">
                                    <a href="{{ route('events-payment', ['type' => 'lunas']) }}">
                                        <i class="ti ti-cash-banknote"></i>
                                        <span>Pembayaran Lunas</span>
                                    </a>
                                </li>

                                <li class="{{ Request::is('racers') ? 'active' : '' }}">
                                    <a href="{{ route('racers', auth()->id()) }}">
                                        <i class="ti ti-helmet"></i>
                                        <span>Data Pembalap</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </li>

                </ul>
            </div>
        </div>

    </div>
    <!-- Sidenav Menu End -->
