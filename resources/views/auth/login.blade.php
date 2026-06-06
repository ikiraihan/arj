<?php $page = 'login'; ?>
@extends('layout.mainlayout')

@section('content')

<!-- ========================
    Start Page Content
========================= -->

<div class="overflow-hidden p-3 acc-vh">

    <div class="row vh-100 w-100 g-0">

        <!-- LEFT -->
        <div class="col-lg-6 vh-100 overflow-y-auto overflow-x-hidden">

            <div class="row">

                <div class="col-md-10 mx-auto">

                    <div class="mb-3">
                        <a href="{{ route('landing') }}"><i class="ti ti-arrow-narrow-left me-1"></i>Kembali ke Halaman Awal</a>
                    </div>

                    <form action="{{ route('login.post') }}" method="POST"
                        class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">

                        @csrf

                        <!-- Logo -->
                        {{-- <div class="text-center mb-4 auth-logo">
                            <img src="{{ asset('assets/landing/img/logo_arj.jpeg') }}"
                                class="img-fluid"
                                alt="Logo"
                                style="max-height: 120px;">
                        </div> --}}

                        <div>

                            <!-- Title -->
                            <div class="mb-4">
                                <h3 class="mb-2">Login</h3>
                                <p class="text-muted">Silahkan login ke akun anda</p>
                            </div>

                            <!-- Success -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Error -->
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Validation Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>

                                <div class="input-group input-group-flat">

                                    <input type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        placeholder="Masukkan email">

                                    <span class="input-group-text">
                                        <i class="ti ti-mail"></i>
                                    </span>

                                </div>

                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label">Password</label>

                                <div class="input-group input-group-flat pass-group">

                                    <input type="password"
                                        name="password"
                                        class="form-control pass-input @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password">

                                    <span class="input-group-text toggle-password">
                                        <i class="ti ti-eye-off"></i>
                                    </span>

                                </div>

                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Button -->
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    Login
                                </button>
                            </div>

                            <!-- Register -->
                            <div class="mb-3 text-center">
                                <p class="mb-0">
                                    Belum Punya Akun?
                                    <a href="{{ route('register') }}"
                                        class="link-danger fw-bold link-hover">
                                        Registrasi Akun
                                    </a>
                                </p>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="text-center pb-4">
                            <p class="text-dark mb-0">
                                Copyright &copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                - Ayah Racing Jaya
                            </p>
                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6 account-bg-01"></div>

    </div>

</div>

<!-- ========================
    End Page Content
========================= -->

@endsection
