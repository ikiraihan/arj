<?php $page = 'event-details'; ?>
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
                    <h4 class="mb-1">Event</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{url('index')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Event</li>
                        </ol>
                    </nav>
                </div>
                <div class="gap-2 d-flex align-items-center flex-wrap">
                    {{-- <div class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-outline-light px-2 shadow" data-bs-toggle="dropdown"><i class="ti ti-package-export me-2"></i>Export</a>
                        <div class="dropdown-menu  dropdown-menu-end">
                            <ul>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-pdf me-1"></i>Export as
                                        PDF</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="ti ti-file-type-xls me-1"></i>Export as
                                        Excel </a>
                                </li>
                            </ul>
                        </div>
                    </div> --}}
                    <a href="javascript:void(0);" class="btn btn-icon btn-outline-light shadow" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Collapse" data-bs-original-title="Collapse" id="collapse-header"><i class="ti ti-transition-top"></i></a>
                </div>
            </div>
            <!-- End Page Header -->

            <div class="row">
                <div class="col-md-12">

                    <div class="mb-3">
                        <a href="{{ route('events-list') }}"><i class="ti ti-arrow-narrow-left me-1"></i>Kembali</a>
                    </div>

                    <div class="card mb-3 event-card" data-id="{{ $eventId }}">
                        <div class="card-body event-body d-flex justify-content-center align-items-center"
                            style="min-height:120px;">

                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Contact Details -->
                <div class="col-xl-12">
                    <div class="card mb-3">
                        <div class="card-body pb-0 pt-2">
                            <ul class="nav nav-tabs nav-bordered mb-3" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#kelas"
                                        data-bs-toggle="tab"
                                        class="nav-link active border-3"
                                        role="tab">

                                        <span class="d-md-inline-block">
                                            <i class="ti ti-motorbike me-1"></i>
                                            Kelas
                                        </span>

                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a href="#pendaftar"
                                        data-bs-toggle="tab"
                                        class="nav-link border-3"
                                        role="tab">

                                        <span class="d-md-inline-block">
                                            <i class="ti ti-users-group me-1"></i>
                                            Pendaftar
                                        </span>

                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a href="#race"
                                        data-bs-toggle="tab"
                                        class="nav-link border-3"
                                        role="tab">

                                        <span class="d-md-inline-block">
                                            <i class="ti ti-helmet me-1"></i>
                                            Data Race
                                        </span>

                                    </a>
                                </li>

                                <li class="nav-item" role="race-original">
                                    <a href="#race-original"
                                        data-bs-toggle="tab"
                                        class="nav-link border-3"
                                        role="tab">

                                        <span class="d-md-inline-block">
                                            <i class="ti ti-helmet me-1"></i>
                                            Data Race (Asli)
                                        </span>

                                    </a>
                                </li>

                                <li class="nav-item" role="report">
                                    <a href="#report"
                                        data-bs-toggle="tab"
                                        class="nav-link border-3"
                                        role="tab">

                                        <span class="d-md-inline-block">
                                            <i class="ti ti-report-analytics me-1"></i>
                                            Report
                                        </span>

                                    </a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content pt-0">

                        <!-- Activities -->
                         <div class="tab-pane fade show active" id="kelas">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                                    {{-- LEFT --}}
                                    <div class="input-icon input-icon-start position-relative">
                                        <span class="input-icon-addon text-dark">
                                            <i class="ti ti-search"></i>
                                        </span>

                                        <input type="text"
                                            id="search-event-class"
                                            class="form-control"
                                            placeholder="Search">
                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="javascript:void(0);"
                                            id="refresh-event-class"
                                            class="btn btn-icon btn-outline-light shadow"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Refresh">

                                            <i class="ti ti-refresh"></i>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="btn btn-primary btn-add-class"
                                            data-event-id="{{ $eventId }}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_add_class">

                                            <i class="ti ti-square-rounded-plus-filled me-1"></i>
                                            Tambah Kelas
                                        </a>

                                    </div>

                                </div>

                                <div class="card-body">
                                    <!-- Event List -->
                                    <div class="table-responsive custom-table">
                                        <table class="table table-nowrap" id="event-class-list-table"data-event-id="{{ $eventId }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-end no-sort">Action</th>
                                                    <th>Nama</th>
                                                    <th>Harga</th>
                                                    <th>Denda</th>
                                                    {{-- <th>Kuota</th> --}}
                                                    <th>Keterangan</th>
                                                    <th>Dibuat</th>
                                                    {{-- <th>Jumlah Pendaftar</th> --}}
                                                    {{-- <th>Status</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Activities -->

                        <!-- Register -->
                            <div class="tab-pane fade" id="pendaftar">
                            <div class="card">
                                 <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                                    {{-- LEFT --}}
                                    <div class="input-icon input-icon-start position-relative">
                                        <span class="input-icon-addon text-dark">
                                            <i class="ti ti-search"></i>
                                        </span>

                                        <input type="text"
                                            id="search-event-register"
                                            class="form-control"
                                            placeholder="Search">
                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="javascript:void(0);"
                                            id="refresh-event-register"
                                            class="btn btn-icon btn-outline-light shadow"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Refresh">

                                            <i class="ti ti-refresh"></i>
                                        </a>

                                        {{-- <a href="javascript:void(0);"
                                            class="btn btn-primary btn-add-class"
                                            data-event-id="{{ $eventId }}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_add_class">

                                            <i class="ti ti-square-rounded-plus-filled me-1"></i>
                                            Tambah Kelas
                                        </a> --}}

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-nowrap" id="event-register-table" data-event-id="{{ $eventId }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-end no-sort">Action</th>
                                                    <th>No. Pendaftaran</th>
                                                    <th>Nama Pembalap</th>
                                                    <th>Tim</th>
                                                    <th>Kontak</th>
                                                    <th>Status Balap</th>
                                                    <th>Total</th>
                                                    <th>Denda</th>
                                                    <th>Metode Pembayaran</th>
                                                    <th>Status Pembayaran</th>
                                                    <th>Bukti Pembayaran</th>
                                                    <th>Tanggal Daftar</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Register -->

                        <!-- Race -->
                        <div class="tab-pane fade" id="race">
                            <div class="card">
                                 <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                                    {{-- LEFT --}}
                                    <div class="input-icon input-icon-start position-relative">
                                        <span class="input-icon-addon text-dark">
                                            <i class="ti ti-search"></i>
                                        </span>

                                        <input type="text"
                                            id="search-event-race"
                                            class="form-control"
                                            placeholder="Search">
                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="{{ route('export-race', $eventId) }}"
                                            class="btn btn-success">
                                            <i class="ti ti-file-export me-1"></i>
                                            Export
                                        </a>

                                        <a href="javascript:void(0);"
                                            id="refresh-race-register"
                                            class="btn btn-icon btn-outline-light shadow"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Refresh">

                                            <i class="ti ti-refresh"></i>
                                        </a>

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-nowrap" id="event-race-table" data-event-id="{{ $eventId }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Action</th>
                                                    <th>No. Kwitansi</th>
                                                    <th>Nama Pembalap</th>
                                                    <th>NIK</th>
                                                    <th>No. Start</th>
                                                    <th>Team</th>
                                                    <th>Kota</th>
                                                    <th>Kelas</th>
                                                    <th>Kendaraan</th>
                                                    <th>No. Rangka</th>
                                                    <th>No. Mesin</th>
                                                    <th>Foto Diri</th>
                                                    <th>KIS</th>
                                                    <th>KTA</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Race -->

                        <!-- Race -->
                        <div class="tab-pane fade" id="race-original">
                            <div class="card">
                                 <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                                    {{-- LEFT --}}
                                    <div class="input-icon input-icon-start position-relative">
                                        <span class="input-icon-addon text-dark">
                                            <i class="ti ti-search"></i>
                                        </span>

                                        <input type="text"
                                            id="search-event-race-original"
                                            class="form-control"
                                            placeholder="Search">
                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="javascript:void(0);"
                                            id="refresh-race-original-register"
                                            class="btn btn-icon btn-outline-light shadow"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Refresh">

                                            <i class="ti ti-refresh"></i>
                                        </a>

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-nowrap" id="event-race-original-table" data-event-id="{{ $eventId }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No. Kwitansi</th>
                                                    <th>Nama Pembalap</th>
                                                    <th>NIK</th>
                                                    <th>No. Start</th>
                                                    <th>Team</th>
                                                    <th>Kota</th>
                                                    <th>Kelas</th>
                                                    <th>Kendaraan</th>
                                                    <th>No. Rangka</th>
                                                    <th>No. Mesin</th>
                                                    {{-- <th>Foto Diri</th>
                                                    <th>KIS</th>
                                                    <th>KTA</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Race -->

                        <!-- Race -->
                        <div class="tab-pane fade" id="report">
                            <div class="card">
                                 <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">

                                    {{-- LEFT --}}
                                    <div class="input-icon input-icon-start position-relative">
                                        <span class="input-icon-addon text-dark">
                                            <i class="ti ti-search"></i>
                                        </span>

                                        <input type="text"
                                            id="search-event-race-original"
                                            class="form-control"
                                            placeholder="Search">
                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="d-flex align-items-center gap-2">

                                        <a href="javascript:void(0);"
                                            id="refresh-race-original-register"
                                            class="btn btn-icon btn-outline-light shadow"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Refresh">

                                            <i class="ti ti-refresh"></i>
                                        </a>

                                    </div>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive custom-table">
                                        <table class="table table-nowrap" id="event-report-income-class" data-event-id="{{ $eventId }}">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Nama Kelas</th>
                                                    <th>Peserta</th>
                                                    <th>Harga</th>
                                                    <th>Total Pendapatan</th>
                                                    {{-- <th>Foto Diri</th>
                                                    <th>KIS</th>
                                                    <th>KTA</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Race -->

                        <!-- Calls -->
                        <div class="tab-pane fade" id="tab_3">
                            <div class="card">
                                <div
                                    class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <h5 class="fw-semibold mb-0">Calls</h5>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#create_call" class="link-primary fw-medium"><i class="ti ti-circle-plus me-1"></i>Add New</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-sm-flex align-items-center justify-content-between pb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-md me-2 flex-shrink-0">
                                                        <img src="{{URL::asset('build/img/profiles/avatar-19.jpg')}}" alt="img">
                                                    </span>
                                                    <p class="mb-0"><span class="text-dark fw-medium">Darlee Robertson</span>
                                                        logged a call on 23 Jul 2023, 10:00 pm
                                                    </p>
                                                </div>
                                                <div class="d-inline-flex align-items-center mb-2">
                                                    <div class="dropdown me-2">
                                                        <a href="#" class="btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">Busy<i class="ti ti-chevron-down ms-2"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);">Busy</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">No Answer</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Unavailable</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Wrong Number</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Left Voice Message</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Moving Forward</a>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#edit_call"><i class="ti ti-edit me-1"></i>Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_call"><i class="ti ti-trash me-1"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-0">A project review evaluates the success of an initiative and
                                                identifies areas for improvement. It can also evaluate a current
                                                project to determine whether it's on the right track. Or, it can
                                                determine the success of a completed project.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-sm-flex align-items-center justify-content-between pb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-md me-2 flex-shrink-0">
                                                        <img src="{{URL::asset('build/img/profiles/avatar-20.jpg')}}" alt="img">
                                                    </span>
                                                    <p class="mb-0"><span class="text-dark fw-medium">Sharon Roy</span>
                                                        logged a call on 18 Sep 2025, 09:52AM
                                                    </p>
                                                </div>
                                                <div class="d-inline-flex align-items-center mb-2">
                                                    <div class="dropdown me-2">
                                                        <a href="#" class="btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">No Answrer<i class="ti ti-chevron-down ms-2"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);">No Answrer</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Unavailable</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Wrong Number</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Left Voice Message</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Moving Forward</a>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#edit_call"><i class="ti ti-edit me-1"></i>Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0);"  data-bs-toggle="modal" data-bs-target="#delete_call"><i class="ti ti-trash me-1"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-0">A project plan typically contains a list of the essential
                                                elements of a project, such as stakeholders, scope, timelines, estimated cost and
                                                communication methods. The project manager
                                                typically lists the information based on the assignment.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card mb-0">
                                        <div class="card-body">
                                            <div class="d-sm-flex align-items-center justify-content-between pb-2">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="avatar avatar-md me-2 flex-shrink-0">
                                                        <img src="{{URL::asset('build/img/profiles/avatar-21.jpg')}}" alt="img">
                                                    </span>
                                                    <p class="mb-0"><span class="text-dark fw-medium">Vaughan</span>
                                                        logged a call on 20 Sep 2025, 10:26 PM
                                                    </p>
                                                </div>
                                                <div class="d-inline-flex align-items-center mb-2">
                                                    <div class="dropdown me-2">
                                                        <a href="#" class="btn btn-sm btn-outline-light" data-bs-toggle="dropdown" aria-expanded="false">No Answrer<i class="ti ti-chevron-down ms-2"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);">No Answrer</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Unavailable</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Wrong Number</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Left Voice Message</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">Moving Forward</a>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#edit_call"><i class="ti ti-edit me-1"></i>Edit</a>
                                                            <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_call"><i class="ti ti-trash me-1"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mb-0">Projects play a crucial role in the success of organizations,
                                                and their importance cannot be overstated.
                                                Whether it's launching a new product, improving an existing
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Calls -->

                        <!-- Files -->
                        <div class="tab-pane fade" id="tab_4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="fw-semibold mb-0">Files</h5>
                                </div>
                                <div class="card-body">
                                    <div class="card border mb-3">
                                        <div class="card-body pb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <h6 class="mb-1">Manage Documents</h6>
                                                        <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3">
                                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_file">Create Document</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border shadow-none mb-3">
                                        <div class="card-body pb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                        <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                        <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                            <span class="avatar avatar-md me-2 flex-shrink-0">
                                                                <img src="{{URL::asset('build/img/profiles/avatar-21.jpg')}}" alt="img" class="rounded-circle">
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <p class="mb-0 me-2">Vaughan Lewis</p>
                                                                <span class="badge bg-light text-body">Owner</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3 d-inline-flex align-items-center">
                                                        <span class="badge badge-soft-danger me-1">Proposal</span>
                                                        <span class="badge bg-info me-1">Draft</span>
                                                        <div class="dropdown">
                                                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                    <i class="ti ti-trash me-1"></i>Delete
                                                                </a>
                                                                <a class="dropdown-item" href="javascript:void(0);">
                                                                    <i class="ti ti-download me-1"></i>Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border shadow-none mb-3">
                                        <div class="card-body pb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                        <p>Send customizable quotes, proposals and contracts to
                                                            close deals faster.</p>
                                                        <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                            <span class="avatar avatar-md me-2 flex-shrink-0">
                                                                <img src="{{URL::asset('build/img/profiles/avatar-01.jpg')}}" alt="img" class="rounded-circle">
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <p class="mb-0 me-2">Jessica Louise</p>
                                                                <span class="badge bg-light text-body">Owner</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3 d-inline-flex align-items-center">
                                                        <span class="badge badge-purple-light me-1">Quote</span>
                                                        <span class="badge bg-success me-1">Sent</span>
                                                        <div class="dropdown">
                                                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                    <i class="ti ti-trash me-1"></i>Delete
                                                                </a>
                                                                <a class="dropdown-item" href="javascript:void(0);">
                                                                    <i class="ti ti-download me-1"></i>Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border shadow-none mb-0">
                                        <div class="card-body pb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <h6 class="fw-semibold fs-14 mb-1">Collier-Turner Proposal</h6>
                                                        <p>Send customizable quotes, proposals and contracts to close deals faster.</p>
                                                        <div class="d-flex align-items-center flex-wrap row-gap-2">
                                                            <span class="avatar avatar-md me-2 flex-shrink-0">
                                                                <img src="{{URL::asset('build/img/profiles/avatar-22.jpg')}}" alt="img" class="rounded-circle">
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <p class="mb-0 me-2">Dawn Merhca</p>
                                                                <span class="badge bg-light text-body">Owner</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3 d-inline-flex align-items-center">
                                                        <span class="badge badge-danger-light me-1">Proposal</span>
                                                        <span class="badge bg-pending priority-badge me-1">Draft</span>
                                                        <div class="dropdown">
                                                            <a href="#" class="action-icon btn btn-icon btn-sm btn-outline-light shadow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete_file">
                                                                    <i class="ti ti-trash me-1"></i>Delete
                                                                </a>
                                                                <a class="dropdown-item" href="javascript:void(0);">
                                                                    <i class="ti ti-download me-1"></i>Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Files -->

                        <!-- Email -->
                        <div class="tab-pane fade" id="tab_5">
                            <div class="card">
                                <div
                                    class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <h5 class="mb-1">Email</h5>
                                    <div class="d-inline-flex align-items-center">
                                        <a href="javascript:void(0);" class="link-primary fw-medium" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-custom-class="tooltip-dark" data-bs-original-title="There are no email accounts configured, Please configured your email account in order to Send/ Create EMails"><i class="ti ti-circle-plus me-1"></i>Create Email</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="card border mb-0">
                                        <div class="card-body pb-0">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <h6 class="mb-1">Manage Emails</h6>
                                                        <p>You can send and reply to emails directly via this section.</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3">
                                                        <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#create_email">Connect Account</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Email -->

                    </div>
                    <!-- /Tab Content -->

                </div>
                <!-- /Contact Details -->

            </div>
            <!-- Start Footer -->

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
