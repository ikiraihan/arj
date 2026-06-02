<?php $page = 'companies-list'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content pb-0">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1">Pembayaran</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Event</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    <a href="javascript:void(0);"
                    id="refresh-events"
                    class="btn btn-icon btn-outline-light shadow"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    aria-label="Refresh"
                    data-bs-original-title="Refresh">
                    <i class="ti ti-refresh"></i></a>
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- card start -->
            <div class="card border-0 rounded-0">
                <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                    <div class="input-icon input-icon-start position-relative">
                        <span class="input-icon-addon text-dark"><i class="ti ti-search"></i></span>
                        <input type="text" id="search-payments" class="form-control" placeholder="Search">
                    </div>
                </div>
                <div class="card-body">

                    <!-- table header -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        {{-- <div class="d-flex align-items-center gap-2 flex-wrap">
                            <div class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-sort-ascending-2 me-2"></i>Sort By</a>
                                <div class="dropdown-menu">
                                    <ul>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">Newest</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">Oldest</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div class="dropdown">
                                <a href="javascript:void(0);" class="btn btn-outline-light shadow px-2" data-bs-toggle="dropdown" data-bs-auto-close="outside"><i class="ti ti-filter me-2"></i>Filter<i class="ti ti-chevron-down ms-2"></i></a>
                                <div class="filter-dropdown-menu dropdown-menu dropdown-menu-lg p-0">
                                    <div class="filter-header d-flex align-items-center justify-content-between border-bottom">
                                        <h6 class="mb-0"><i class="ti ti-filter me-1"></i>Filter</h6>
                                        <button type="button" class="btn-close close-filter-btn" data-bs-dismiss="dropdown-menu" aria-label="Close"></button>
                                    </div>
                                    <div class="filter-set-view p-3">
                                        <div class="accordion" id="accordionExample">
                                            <div class="filter-set-content">
                                                <div class="filter-content-list bg-light rounded border p-2 shadow mt-2">
                                                    <ul>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">

                                                                <input class="form-check-input m-0 me-2 filter-status"
                                                                    type="checkbox"
                                                                    value="paid"
                                                                    {{ in_array('paid', $checked) ? 'checked' : '' }}>

                                                                Lunas

                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">

                                                                <input class="form-check-input m-0 me-2 filter-status"
                                                                    type="checkbox"
                                                                    value="unpaid"

                                                                    {{ in_array('unpaid', $checked) ? 'checked' : '' }}>

                                                                Belum Bayar

                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">

                                                                <input class="form-check-input m-0 me-2 filter-status"
                                                                    type="checkbox"
                                                                    value="menunggu-pembayaran"

                                                                    {{ in_array('menunggu-pembayaran', $checked) ? 'checked' : '' }}>

                                                                Menunggu Pembayaran

                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">

                                                                <input class="form-check-input m-0 me-2 filter-status"
                                                                    type="checkbox"
                                                                    value="menunggu-approval"

                                                                    {{ in_array('menunggu-approval', $checked) ? 'checked' : '' }}>

                                                                Menunggu Approval

                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="dropdown-item px-2 d-flex align-items-center">

                                                                <input class="form-check-input m-0 me-2 filter-status"
                                                                    type="checkbox"
                                                                    value="rejected"

                                                                    {{ in_array('rejected', $checked) ? 'checked' : '' }}>

                                                                Ditolak

                                                            </label>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="javascript:void(0);"
                                                class="btn btn-outline-light w-100 btn-reset-filter">
                                                Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- table header -->

                   <!-- Event List -->
                    <div class="table-responsive custom-table">
                        <table class="table table-nowrap" id="event-payment-table" data-user-id="{{ $userId }}">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-end no-sort">Action</th>
                                    <th>Nama Pembalap</th>
                                    <th>Transfer Ke</th>
                                    <th>Metode Pembayaran</th>
                                    <th>Status Pembayaran</th>
                                    <th>Total</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Event</th>
                                    {{-- <th>Jumlah Pendaftar</th> --}}
                                    {{-- <th>Status</th> --}}
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="datatable-length"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="datatable-paginate"></div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- card end -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
