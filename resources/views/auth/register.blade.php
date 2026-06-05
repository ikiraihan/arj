<?php $page = 'login'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

        <div class="overflow-hidden p-3 acc-vh">

            <!-- start row -->
            <div class="row vh-100 w-100 g-0">

                <div class="col-lg-6 vh-100  overflow-y-auto overflow-x-hidden">

                    <!-- start row -->
                    <div class="row">

                        <div class="col-md-10 mx-auto">
                            <form action="{{ route('register.post') }}" method="POST" class="vh-100 d-flex justify-content-between flex-column p-4 pb-0">
                                {{-- <div class="text-center mb-3 auth-logo">
                                    <img src="{{ asset('assets/landing/img/logo_arj.jpeg') }}" class="img-fluid" alt="Logo">
                                </div> --}}
                                @csrf
                                <div>
                                    <div class="mb-3">
                                        <h3 class="mb-2">Registrasi</h3>
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
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="input-group input-group-flat">
                                            <input type="text"
                                                name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}"
                                                placeholder="Masukkan nama"
                                                required
                                                >
                                            <span class="input-group-text">
                                                <i class="ti ti-user"></i>
                                            </span>
                                        </div>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>

                                        <div class="input-group input-group-flat">

                                            <input type="email"
                                                name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}"
                                                placeholder="Masukkan email"
                                                required
                                                >

                                            <span class="input-group-text">
                                                <i class="ti ti-mail"></i>
                                            </span>

                                        </div>

                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No. WA</label>
                                        <div class="input-group input-group-flat">
                                            <input type="text"
                                                name="phone_number"
                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                value="{{ old('phone_number') }}"
                                                placeholder="Masukkan nomor WhatsApp"
                                                required>
                                            <span class="input-group-text">
                                                <i class="ti ti-user"></i>
                                            </span>
                                        </div>
                                        @error('phone_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password"
                                                name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Masukkan password">

                                            <span class="input-group-text toggle-password ">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <div class="input-group input-group-flat pass-group">
                                            <input type="password"
                                                name="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Konfirmasi password">
                                            <span class="input-group-text toggle-password">
                                                <i class="ti ti-eye-off"></i>
                                            </span>
                                        </div>
                                        @error('password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary w-100">Registrasi</button>
                                    </div>
                                    <div class="mb-3">
                                        <p class="mb-0">Sudah Punya Akun? <a href="{{url('login')}}" class="link-danger fw-bold link-hover"> Login</a></p>
                                    </div>
                                </div>
                                <div class="text-center pb-4">
                                    <p class="text-dark mb-0">Copyright &copy; <script>document.write(new Date().getFullYear())</script> - Ayah Racing Jaya</p>
                                </div>
                            </form>
                        </div> <!-- end col -->

                    </div>
                    <!-- end row -->

                </div> <!-- end col -->

                <div class="col-lg-6 account-bg-01"></div> <!-- end col -->

            </div>
            <!-- end row -->

        </div>

    <!-- ========================
        End Page Content
    ========================= -->

@endsection
