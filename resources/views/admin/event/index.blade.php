<?php $page = 'companies'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Events</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Event</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">

                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Event Grid -->
            <div class="row" id="event-grid"></div>

            <div id="load-more-wrapper" class="text-center mt-4">
                <button id="load-more-btn" class="btn btn-primary">
                    <i class="ti ti-loader-2 me-1"></i>
                    Lihat Lainnya
                </button>
            </div>

            <!-- Loading -->
            {{-- <div id="event-loading" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2 text-muted">Loading events...</div>
            </div> --}}

            <!-- Sentinel (trigger scroll) -->
            <div id="scroll-sentinel"></div>

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
