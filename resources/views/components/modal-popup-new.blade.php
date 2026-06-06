@if (Route::is(['manage-users']))
    <!-- Add User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Tambah User</h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="row">

                    {{-- Nama --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Nama <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>

                                <div class="form-check form-switch form-check-reverse">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="switchCheckReverse">
                                </div>
                            </div>

                            <input type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>

                            <select name="role"
                                class="form-select @error('role') is-invalid @enderror">

                                <option value="">Pilih Role</option>

                                <option value="superadmin"
                                    {{ old('role') == 'superadmin' ? 'selected' : '' }}>
                                    Superadmin
                                </option>

                                {{-- <option value="admin"
                                    {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option> --}}

                                <option value="user"
                                    {{ old('role') == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                No. Telepon <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror">

                            @error('phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>

                            <div class="input-group input-group-flat pass-group">

                                <input type="password"
                                    name="password"
                                    class="form-control pass-input @error('password') is-invalid @enderror">

                                <span class="input-group-text toggle-password">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Repeat Password --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Ulangi Password <span class="text-danger">*</span>
                            </label>

                            <div class="input-group input-group-flat pass-group">

                                <input type="password"
                                    name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror">

                                <span class="input-group-text toggle-password">
                                    <i class="ti ti-eye-off"></i>
                                </span>
                            </div>

                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-end">
                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add User -->

    <!-- Edit User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Edit User</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form id="form-edit-user">
                <input type="hidden" name="id" id="edit-id">

                <div class="row">

                    <!-- Nama -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="edit-name" class="form-control">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" id="edit-email" class="form-control">
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Role <span class="text-danger">*</span>
                            </label>

                            <select name="role" id="edit-role" class="form-select">
                                <option value="">Pilih Role</option>
                                <option value="superadmin">Super Admin</option>
                                {{-- <option value="admin">Admin</option> --}}
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                No. Telepon <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" id="edit-phone" class="form-control">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Password
                            </label>
                            <input type="password" name="password" id="edit-password" class="form-control">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Ulangi Password
                            </label>
                            <input type="password" name="password_confirmation" id="edit-password-confirm" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>

    </div>
    <!-- /Edit User -->

    <!-- delete modal -->
    <div class="modal fade" id="delete_contact">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal -->
@endif

@if (Route::is(['events-list']))

    <!-- Add User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Tambah Event</h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    {{-- @if ($errors->any())

                        <div class="alert alert-danger alert-dismissible fade show" role="alert">

                            <div class="d-flex align-items-start">

                                <div class="me-2">
                                    <i class="ti ti-alert-circle fs-18"></i>
                                </div>

                                <div>

                                    <h6 class="mb-1">
                                        Terdapat kesalahan pada form
                                    </h6>

                                    <ul class="mb-0 ps-3">

                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach

                                    </ul>

                                    @if ($errors->has('images') || $errors->has('images.*'))
                                        <div class="mt-2 small">
                                            Foto event perlu dipilih ulang karena browser tidak mengizinkan restore file upload.
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                            </button>

                        </div>

                    @endif --}}
                    <div class="mb-3">
                        <label class="form-label">
                            Foto (Poster)
                        </label>
                        <input class="form-control @error('images.*') is-invalid @enderror"
                            type="file"
                            id="formFileMultiple"
                            name="images[]"
                            accept="image/*"
                            {{-- multiple --}}
                            >

                        {{-- <small class="text-muted">
                            Bisa upload lebih dari satu foto
                        </small> --}}

                        <div id="image-preview" class="d-flex flex-wrap gap-2 mt-3"></div>

                        @error('images.*')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Event Name --}}
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Nama Event <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Deskripsi
                            </label>

                            <textarea name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">

                            <label class="form-label mb-2">
                                Tipe
                            </label>

                            <select class="form-select"

                                name="type">

                                <option value="race" selected>
                                    Race
                                </option>

                                <option value="drag">
                                    Drag
                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- Venue --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Venue <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="venue"
                                value="{{ old('venue') }}"
                                class="form-control @error('venue') is-invalid @enderror">

                            @error('venue')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Link Maps --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Link Google Maps Venue
                            </label>

                            <input type="url"
                                name="link_maps"
                                value="{{ old('link_maps') }}"
                                class="form-control @error('link_maps') is-invalid @enderror"
                                placeholder="https://maps.google.com/...">

                            @error('link_maps')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Provinsi --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Provinsi
                            </label>

                            <input type="text"
                                name="provinsi"
                                value="{{ old('provinsi') }}"
                                class="form-control @error('provinsi') is-invalid @enderror">

                            @error('provinsi')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Kota --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Kota
                            </label>

                            <input type="text"
                                name="kota"
                                value="{{ old('kota') }}"
                                class="form-control @error('kota') is-invalid @enderror">

                            @error('kota')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Registration Date --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Tanggal Registrasi Mulai <span class="text-danger">*</span>
                            </label>

                            <input type="datetime-local"
                                name="registration_start_date"
                                value="{{ old('registration_start_date') }}"
                                class="form-control @error('registration_start_date') is-invalid @enderror">

                            @error('registration_start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Tanggal Registrasi Selesai <span class="text-danger">*</span>
                            </label>

                            <input type="datetime-local"
                                name="registration_end_date"
                                value="{{ old('registration_end_date') }}"
                                class="form-control @error('registration_end_date') is-invalid @enderror">

                            @error('registration_end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Event Date --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Tanggal Event Mulai <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                class="form-control @error('start_date') is-invalid @enderror">

                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Tanggal Event Selesai <span class="text-danger">*</span>
                            </label>

                            <input type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                class="form-control @error('end_date') is-invalid @enderror">

                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Status Event
                            </label>

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                    type="checkbox"
                                    name="is_active"
                                    value=true>

                                <label class="form-check-label">
                                    Tampilkan Event
                                </label>

                            </div>

                        </div>
                    </div>

                    {{-- =========================
                        CONTACT PERSONS
                    ========================= --}}
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">
                                Contact Person
                            </label>

                            <button type="button"
                                class="btn btn-sm btn-primary"
                                id="btn-add-contact">
                                <i class="ti ti-plus"></i>
                                Tambah Contact
                            </button>
                        </div>

                        <div id="contact-person-wrapper"
                            data-contact-count="{{ count(old('contacts', [['name' => '', 'phone_number' => '']])) }}">

                            @php
                                $contacts = old('contacts', [['name' => '', 'phone_number' => '']]);
                            @endphp

                            @foreach ($contacts as $index => $contact)

                                <div class="card border mb-3 contact-item">
                                    <div class="card-body">

                                        <div class="row">

                                            {{-- Name --}}
                                            <div class="col-md-6">
                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Nama Kontak
                                                    </label>

                                                    <input type="text"
                                                        name="contacts[{{ $index }}][name]"
                                                        value="{{ old("contacts.$index.name", $contact['name'] ?? '') }}"
                                                        class="form-control @error("contacts.$index.name") is-invalid @enderror"
                                                        placeholder="Nama contact person">

                                                    @error("contacts.$index.name")
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>

                                            {{-- Phone --}}
                                            <div class="col-md-5">
                                                <div class="mb-3">

                                                    <label class="form-label">
                                                        Nomor Telepon
                                                    </label>

                                                    <input type="text"
                                                        name="contacts[{{ $index }}][phone_number]"
                                                        value="{{ old("contacts.$index.phone_number", $contact['phone_number'] ?? '') }}"
                                                        class="form-control @error("contacts.$index.phone_number") is-invalid @enderror"
                                                        placeholder="08xxxxxxxxxx">

                                                    @error("contacts.$index.phone_number")
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>
                                            </div>

                                            {{-- Delete --}}
                                            <div class="col-md-1">
                                                <div class="mb-3">

                                                    <label class="form-label d-block">&nbsp;</label>

                                                    <button type="button"
                                                        class="btn btn-danger btn-remove-contact w-100">
                                                        <i class="ti ti-trash"></i>
                                                    </button>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            @endforeach

                        </div>
                    </div>

                    {{-- =========================
                        PAYMENT ACCOUNT
                    ========================= --}}
                    <div class="col-12 mt-3">
                        <div class="card border">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    Informasi Rekening Pembayaran
                                </h6>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    {{-- Bank Name --}}
                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Bank
                                            </label>

                                            <input type="text"
                                                name="payment_account[bank_name]"
                                                value="{{ old('payment_account.bank_name') }}"
                                                class="form-control @error('payment_account.bank_name') is-invalid @enderror"
                                                placeholder="Contoh: BCA">

                                            @error('payment_account.bank_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>

                                    {{-- Account Number --}}
                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nomor Rekening
                                            </label>

                                            <input type="text"
                                                name="payment_account[account_number]"
                                                value="{{ old('payment_account.account_number') }}"
                                                class="form-control @error('payment_account.account_number') is-invalid @enderror"
                                                placeholder="1234567890">

                                            @error('payment_account.account_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>

                                    {{-- Account Holder --}}
                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Pemilik Rekening
                                            </label>

                                            <input type="text"
                                                name="payment_account[account_holder_name]"
                                                value="{{ old('payment_account.account_holder_name') }}"
                                                class="form-control @error('payment_account.account_holder_name') is-invalid @enderror"
                                                placeholder="Nama pemilik rekening">

                                            @error('payment_account.account_holder_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Link Dokumentasi --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">
                                Link Dokumentasi
                            </label>

                            <input type="url"
                                name="link_documentation"
                                value="{{ old('link_documentation') }}"
                                class="form-control @error('link_documentation') is-invalid @enderror"
                                placeholder="https://example.com/documentasi">

                            @error('link_documentation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Status Dokumentasi
                            </label>

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                    type="checkbox"
                                    name="is_active"
                                    value=true>

                                <label class="form-check-label">
                                    Tampilkan Dokumentasi di Landing Page jika link aktif
                                </label>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-end">
                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add User -->

    <!-- Edit User -->
    <div class="offcanvas offcanvas-end offcanvas-large"
        tabindex="-1"
        id="offcanvas_edit">

        <div class="offcanvas-header border-bottom">

            <h5 class="fw-semibold">
                Edit Event
            </h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas"
                aria-label="Close">

                <i class="ti ti-x"></i>

            </button>

        </div>

        <div class="offcanvas-body">

            <form id="form-edit-event" enctype="multipart/form-data">

                <input type="hidden" name="id" id="edit-id">

                <div class="row">

                    {{-- PHOTOS --}}
                    <div class="col-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Foto (Poster)
                            </label>

                            <input class="form-control"
                                type="file"
                                id="edit-images"
                                name="images[]"
                                accept="image/*"
                                {{-- multiple --}}
                                >
                        {{--
                            <small class="text-muted">
                                Bisa upload lebih dari satu foto
                            </small> --}}

                            {{-- Preview existing + new images --}}
                            <div id="edit-image-preview"
                                class="d-flex flex-wrap gap-2 mt-3">
                            </div>

                        </div>
                    </div>

                    {{-- EVENT NAME --}}
                    <div class="col-md-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Event
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                id="edit-name"
                                class="form-control">
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Deskripsi Event
                            </label>

                            <textarea name="description"
                                id="edit-description"
                                rows="5"
                                class="form-control"></textarea>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">

                            <label class="form-label mb-2">
                                Tipe
                            </label>

                            <select class="form-select"
                                id="edit-type"
                                name="type">

                                <option value="race" selected>
                                    Race
                                </option>

                                <option value="drag">
                                    Drag
                                </option>

                            </select>

                        </div>

                    </div>

                    {{-- VENUE --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Venue
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="venue"
                                id="edit-venue"
                                class="form-control">

                        </div>
                    </div>

                    {{-- LINK MAPS --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Link Maps
                            </label>

                            <input type="url"
                                name="link_maps"
                                id="edit-link-maps"
                                class="form-control">

                        </div>
                    </div>

                    {{-- PROVINSI --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Provinsi
                            </label>

                            <input type="text"
                                name="provinsi"
                                id="edit-provinsi"
                                class="form-control">

                        </div>
                    </div>

                    {{-- KOTA --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Kota
                            </label>

                            <input type="text"
                                name="kota"
                                id="edit-kota"
                                class="form-control">

                        </div>
                    </div>

                    {{-- REGISTRATION DATE --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Mulai Registrasi
                            </label>

                            <input type="datetime-local"
                                name="registration_start_date"
                                id="edit-registration-start-date"
                                class="form-control">

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Akhir Registrasi
                            </label>

                            <input type="datetime-local"
                                name="registration_end_date"
                                id="edit-registration-end-date"
                                class="form-control">

                        </div>
                    </div>

                    {{-- EVENT DATE --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Mulai Event
                            </label>

                            <input type="date"
                                name="start_date"
                                id="edit-start-date"
                                class="form-control">

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal Akhir Event
                            </label>

                            <input type="date"
                                name="end_date"
                                id="edit-end-date"
                                class="form-control">

                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Status Event
                            </label>

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                    type="checkbox"
                                    id="edit-is-active"
                                    name="is_active"
                                    value="1">

                                <label class="form-check-label">
                                    Tampilkan Event
                                </label>

                            </div>

                        </div>
                    </div>

                    {{-- CONTACT PERSON --}}
                    <div class="col-12 mt-3">

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <label class="form-label mb-0">
                                Contact Person
                            </label>

                            <button type="button"
                                class="btn btn-sm btn-primary"
                                id="btn-add-edit-contact">

                                <i class="ti ti-plus"></i>
                                Tambah Contact

                            </button>

                        </div>

                        <div id="edit-contact-person-wrapper"></div>

                    </div>

                    {{-- PAYMENT ACCOUNT --}}
                    <div class="col-12 mt-3">

                        <div class="card border">

                            <div class="card-header">
                                <h6 class="mb-0">
                                    Informasi Rekening Pembayaran
                                </h6>
                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Bank
                                            </label>

                                            <input type="text"
                                                name="payment_account[bank_name]"
                                                id="edit-bank-name"
                                                class="form-control">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nomor Rekening
                                            </label>

                                            <input type="text"
                                                name="payment_account[account_number]"
                                                id="edit-account-number"
                                                class="form-control">

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Pemilik Rekening
                                            </label>

                                            <input type="text"
                                                name="payment_account[account_holder_name]"
                                                id="edit-account-holder-name"
                                                class="form-control">

                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- LINK MAPS --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Link Dokumentasi
                            </label>

                            <input type="url"
                                name="link_documentation"
                                id="edit-link-documentation"
                                class="form-control">

                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label d-block">
                                Status Dokumentasi
                            </label>

                            <div class="form-check form-switch">

                                <input class="form-check-input"
                                    type="checkbox"
                                    id="edit-link-documentation-active"
                                    name="link_documentation_active"
                                    value="1">

                                <label class="form-check-label">
                                    Tampilkan Dokumentasi di Landing Page jika link aktif
                                </label>

                            </div>

                        </div>
                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-end mt-4">

                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">

                        Batal

                    </a>

                    <button type="submit" class="btn btn-primary">
                        Update Event
                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- /Edit User -->

    <!-- delete modal -->
    <div class="modal fade" id="delete_contact">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete_payment">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Hapus semua bukti pembayaran</h5>
                    <p class="mb-3">File dan data bukti pembayaran akan dihapus permanen.</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete-payment"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal -->
@endif

@if (Route::is(['event-details']))
    <!-- Add Class -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add_class">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Tambah Kelas</h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">

            <form id="form-add-class">

                <input type="hidden" name="event_id" id="event_id">

                <div class="row">

                    {{-- Nama --}}
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">
                                Nama Kelas <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                class="form-control"
                                placeholder="Masukkan nama kelas"
                                required>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                name="price"
                                class="form-control"
                                placeholder="Masukkan harga"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Denda
                            </label>

                            <input type="number"
                                name="price_fine"
                                class="form-control"
                                placeholder="Masukkan denda"
                                required>
                        </div>
                    </div>

                    {{-- Quota --}}
                    {{-- <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Kuota Peserta
                            </label>

                            <input type="number"
                                name="quota"
                                class="form-control"
                                placeholder="Masukkan kuota">
                        </div>
                    </div> --}}

                    {{-- Notes --}}
                    <div class="col-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea name="notes"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan catatan"></textarea>

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary btn-submit">
                        Tambah
                    </button>

                </div>

            </form>

        </div>
    </div>
    <!-- /Add Class -->

    <!-- Edit Class -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_edit_class">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Edit Kelas</h5>
            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>
        <div class="offcanvas-body">

            <form id="form-edit-class">

                <input type="hidden" name="id" id="edit-id">
                <input type="hidden" name="event_id" id="edit-event-id">

                <div class="row">

                    {{-- Nama --}}
                    <div class="col-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Kelas <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="name"
                                id="edit-name-class"
                                class="form-control"
                                placeholder="Masukkan nama kelas"
                                required>

                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Harga <span class="text-danger">*</span>
                            </label>

                            <input type="number"
                                name="price"
                                id="edit-price"
                                class="form-control"
                                placeholder="Masukkan harga"
                                required>

                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Denda
                            </label>

                            <input type="number"
                                name="price_fine"
                                id="edit-price_fine"
                                class="form-control"
                                placeholder="Masukkan denda"
                                required>

                        </div>
                    </div>

                    {{-- Quota --}}
                    {{-- <div class="col-md-6">
                        <div class="mb-3">

                            <label class="form-label">
                                Kuota Peserta
                            </label>

                            <input type="number"
                                name="quota"
                                id="edit-quota"
                                class="form-control"
                                placeholder="Masukkan kuota">

                        </div>
                    </div> --}}

                    {{-- Notes --}}
                    <div class="col-12">
                        <div class="mb-3">

                            <label class="form-label">
                                Notes
                            </label>

                            <textarea name="notes"
                                id="edit-notes"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan catatan"></textarea>

                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end gap-2">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary btn-submit">
                        Update
                    </button>

                </div>

            </form>

        </div>

    </div>
    <!-- /Edit Class -->

    <!-- delete modal -->
    <div class="modal fade" id="delete_class">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete-class"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal -->

    <!-- APPROVAL PEMBAYARAN -->
    <div class="modal fade" id="modal_approval_payment" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">

                    <h5 class="modal-title">
                        Approval Pembayaran
                    </h5>

                    <button type="button"
                        class="btn-close custom-btn-close border p-1 me-0 text-dark"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>

                <!-- FORM -->
                <form id="form-approval-payment">

                    <input type="hidden" id="approval_registration_id">

                    <div class="modal-body">

                        <div class="row">

                            <!-- LEFT -->
                            <div class="col-lg-5">

                                <div class="border rounded p-2 bg-light text-center">

                                    <img id="approval_payment_proof"
                                        src=""
                                        alt="Bukti Pembayaran"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-height: 450px; object-fit: contain;">

                                </div>

                                <div class="mt-2 text-center">

                                    <a href="#"
                                        target="_blank"
                                        id="approval_payment_proof_link"
                                        class="btn btn-outline-primary btn-sm">

                                        <i class="ti ti-download me-1"></i>
                                        Lihat Full

                                    </a>

                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-lg-7">

                                {{-- <!-- CONTACT PERSON -->
                                <div class="border rounded p-3 bg-light">

                                    <h6 class="fw-bold mb-3">
                                        Contact Person
                                    </h6>

                                    <!-- NAMA -->
                                    <div class="mb-2">

                                        <small class="text-muted d-block">
                                            Nama
                                        </small>

                                        <span id="approval_user_name"
                                            class="fw-semibold">
                                            -
                                        </span>

                                    </div>

                                    <!-- NOMOR HP -->
                                    <div class="mb-2">

                                        <small class="text-muted d-block">
                                            Nomor HP
                                        </small>

                                        <span id="approval_user_phone"
                                            class="fw-semibold">
                                            -
                                        </span>

                                    </div>

                                    <!-- WHATSAPP -->
                                    <div>

                                        <small class="text-muted d-block">
                                            Whatsapp
                                        </small>

                                        <a href="#"
                                            target="_blank"
                                            id="approval_user_phone_click"
                                            class="btn btn-success btn-sm mt-1">

                                            <i class="ti ti-brand-whatsapp me-1"></i>
                                            Hubungi

                                        </a>

                                    </div>

                                </div> --}}

                                <!-- PEMBALAP -->
                                <div class="mb-3">

                                    <label class="form-label bg-light fw-semibold">
                                        Nama Pembalap
                                    </label>

                                    <input type="text"
                                        id="approval_racer_name"
                                        class="form-control bg-light"
                                        readonly>

                                </div>

                                <!-- PEMBALAP -->
                                <div class="mb-3">

                                    <label class="form-label bg-light fw-semibold">
                                        Tim
                                    </label>

                                    <input type="text"
                                        id="approval_team_name"
                                        class="form-control bg-light"
                                        readonly>

                                </div>

                                <!-- TOTAL -->
                                <div class="mb-3">

                                    <label class="form-label bg-light fw-semibold">
                                        Total Tagihan
                                    </label>

                                    <input type="text"
                                        id="approval_total_price"
                                        class="form-control bg-light fw-semibold"
                                        readonly>

                                </div>

                                <!-- PAYMENT METHOD -->
                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Metode Pembayaran
                                    </label>

                                    <input type="text"
                                        id="approval_payment_method"
                                        class="form-control bg-light"
                                        readonly>

                                </div>

                                <!-- STATUS -->
                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Status Pembayaran
                                    </label>

                                    <select class="form-select"
                                        id="approval_payment_status"
                                        name="status">

                                        <option value="paid" selected>
                                            Lunas
                                        </option>

                                        <option value="unpaid">
                                            Tolak
                                        </option>

                                    </select>

                                </div>

                                <!-- CATATAN -->
                                {{-- <div class="mb-0">

                                    <label class="form-label fw-semibold">
                                        Catatan Admin
                                    </label>

                                    <textarea class="form-control"
                                        id="approval_notes"
                                        rows="4"
                                        placeholder="Tambahkan catatan jika diperlukan..."></textarea>

                                </div> --}}

                            </div>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit"
                            class="btn btn-primary">

                            <i class="ti ti-check me-1"></i>
                            Submit

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
    <!-- DELETE MODAL -->
    <div class="modal fade" id="delete_register">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete-register"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- DELETE MODAL -->

    <!-- CHANGE FINE MODAL -->
    <div class="modal fade" id="modal_change_fine_status">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">

                <div class="modal-body p-4 text-center position-relative">

                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-warning border-0 text-warning rounded-circle">
                            <i class="ti ti-alert-circle fs-24"></i>
                        </span>
                    </div>

                    <h5 class="mb-1">
                        Ubah Status Denda
                    </h5>

                    <p class="mb-3">
                        Apakah anda yakin ingin mengubah status denda registrasi ini?
                    </p>

                    <div class="d-flex justify-content-center">

                        <a href="#"
                            class="btn btn-light position-relative z-1 me-2 w-100"
                            data-bs-dismiss="modal">
                            Batal
                        </a>

                        <button type="button"
                            class="btn btn-primary position-relative z-1 w-100 btn-confirm-change-fined">
                            Ubah
                        </button>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- CHANGE FINE MODAL -->
    <!-- EDIT STATUS BALAP -->
    <div class="modal fade"
        id="modal_approval_race_status"
        tabindex="-1"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-md">

            <div class="modal-content border-0 shadow-sm rounded-3">

                <!-- HEADER -->
                <div class="modal-header py-3 px-4 border-bottom">

                    <div>
                        <h5 class="modal-title fw-semibold mb-0">
                            Ubah Status Balap
                        </h5>
                    </div>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <!-- FORM -->
                <form id="form-approval-race-status">

                    <input type="hidden" id="approval_race_id">

                    <div class="modal-body px-4 py-3">

                        <!-- STATUS -->
                        <div class="mb-0">

                            <label class="form-label fw-semibold mb-2">
                                Status Race
                            </label>

                            <select class="form-select"
                                id="approval_race_status"
                                name="race_status">

                                <option value="approved" selected>
                                    Setujui
                                </option>

                                <option value="rejected">
                                    Tolak
                                </option>

                            </select>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer py-3 px-4 border-top">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_delete_race" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">

            <div class="modal-content border-0 shadow-sm rounded-3">

                <!-- HEADER -->
                <div class="modal-header py-3 px-4 border-bottom">

                    <div>
                        <h5 class="modal-title fw-semibold mb-0 text-danger">
                            Hapus Data Race
                        </h5>

                        {{-- <small class="text-muted">
                            Tindakan ini tidak dapat dibatalkan
                        </small> --}}
                    </div>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>

                <!-- FORM -->
                <form id="form-delete-race">

                    <input type="hidden" id="delete_race_id">

                    <div class="modal-body px-4 py-3">

                        <!-- ALASAN -->
                        <div class="mb-0">

                            <label class="form-label fw-semibold mb-2">
                                Alasan Hapus
                            </label>

                            <textarea class="form-control"
                                id="delete_reason"
                                name="delete_reason"
                                rows="4"
                                placeholder="Masukkan alasan penghapusan data..."></textarea>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer py-3 px-4 border-top">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit"
                            class="btn btn-danger">

                            <i class="ti ti-trash me-1"></i>
                            Hapus

                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

    <div id="racer-detail"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="racer-detailLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="racer-detailLabel">
                        Detail Pembalap
                    </h4>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-lg-7">
                            <div class="mb-3">
                                <h3 class="fw-bold mb-1" id="detail_racer_full_name">-</h3>
                                <p class="text-muted mb-0">
                                    <i class="ti ti-user me-1"></i>
                                    Alias: <span id="detail_racer_short_name" class="fw-medium">-</span>
                                </p>
                            </div>

                            <div class="border rounded p-3 mb-3 bg-light">
                                <h6 class="fw-semibold mb-3 text-primary">Biodata Pembalap</h6>

                                <div class="mb-2">
                                    <small class="text-muted d-block">NIK</small>
                                    <span id="detail_racer_nik" class="fw-semibold">-</span>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Tempat Lahir</small>
                                        <span id="detail_racer_birth_location">-</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Tanggal Lahir</small>
                                        <span id="detail_racer_birth_date">-</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted d-block">No. Telepon</small>
                                        <span id="detail_racer_phone_number">-</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">Kota Asal (Hometown)</small>
                                        <span id="detail_racer_hometown">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">

                            <div class="border rounded p-3 mb-3">
                                <h6 class="fw-bold mb-3 text-danger">Dokumen</h6>

                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                        <span class="text-muted small">KTA Pembalap</span>
                                        <a href="#" target="_blank" id="detail_racer_kta" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                            <i class="ti ti-photo"></i> Lihat
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-2">
                                        <span class="text-muted small">KIS Pembalap</span>
                                        <a href="#" target="_blank" id="detail_racer_kis" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                            <i class="ti ti-photo"></i> Lihat
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between pt-1">
                                        <span class="text-muted small">Foto Diri</span>
                                        <a href="#" target="_blank" id="detail_racer_photo" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                            <i class="ti ti-photo"></i> Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded p-3">
                                <h6 class="fw-bold mb-3">Pendaftar / Penanggung Jawab</h6>

                                <div class="mb-2">
                                    <small class="text-muted d-block">Nama</small>
                                    <span id="detail_cp_name" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted d-block">Nomor HP</small>
                                    <span id="detail_cp_phone" class="fw-semibold">-</span>
                                </div>

                                <div>
                                    <small class="text-muted d-block">WhatsApp</small>
                                    <a href="#"
                                    target="_blank"
                                    id="detail_cp_whatsapp"
                                    class="btn btn-success btn-sm mt-1 d-inline-flex align-items-center">
                                        <i class="ti ti-brand-whatsapp me-1"></i> Hubungi
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade"
     id="modal_edit_race"
     tabindex="-1"
     aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-md">

            <div class="modal-content border-0 shadow-sm rounded-3">

                <div class="modal-header py-3 px-4 border-bottom">
                    <div>
                        <h5 class="modal-title fw-semibold mb-0">
                            Ubah Data Race
                        </h5>
                    </div>
                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>
                </div>

                <form id="form-edit-race">

                    <input type="hidden" id="registration_class_id">

                    <div class="modal-body px-4 py-3">

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">
                                Nomor Start (Racer Number)
                            </label>
                            <input type="text"
                                class="form-control"
                                id="edit_racer_number"
                                name="racer_number"
                                placeholder="Contoh: 99">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">
                                Kendaraan / Kelas Kendaraan
                            </label>
                            <input type="text"
                                class="form-control"
                                id="edit_vehicle"
                                name="vehicle"
                                placeholder="Contoh: Honda CRF 150">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">
                                Nomor Rangka
                            </label>
                            <input type="text"
                                class="form-control"
                                id="edit_rangka_number"
                                name="rangka_number"
                                placeholder="Masukkan nomor rangka">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold mb-2">
                                Nomor Mesin
                            </label>
                            <input type="text"
                                class="form-control"
                                id="edit_vehicle_number"
                                name="vehicle_number"
                                placeholder="Masukkan nomor mesin">
                        </div>

                    </div>

                    <div class="modal-footer py-3 px-4 border-top">
                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit"
                            class="btn btn-primary">
                            {{-- <i class="ti ti-check me-1"></i> --}}
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_filter_pendaftar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Filter Pendaftar
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Pendaftaran</label>
                            <input type="text"
                                id="pendaftar_filter_registration_number"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pembalap</label>
                            <input type="text"
                                id="pendaftar_filter_racer_name"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tim</label>
                            <input type="text"
                                id="pendaftar_filter_team_name"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Balap</label>
                            <select id="pendaftar_filter_race_status" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status Pembayaran</label>
                            <select id="pendaftar_filter_payment_status" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Lunas</option>
                                <option value="unpaid">Belum Bayar</option>
                                <option value="menunggu-pembayaran">Menunggu Pembayaran</option>
                                <option value="menunggu-approval">Menunggu Approval</option>
                                <option value="rejected">Ditolak</option>

                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select id="pendaftar_filter_payment_method" class="form-select">
                                <option value="">Semua</option>
                                <option value="transfer">Transfer</option>
                                <option value="tunai">Tunai</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date"
                                id="pendaftar_filter_start_date"
                                class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date"
                                id="pendaftar_filter_end_date"
                                class="form-control">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        id="reset-filter-pendaftar"
                        class="btn btn-light">
                        Reset
                    </button>

                    <button type="button"
                        id="apply-filter-pendaftar"
                        class="btn btn-primary">
                        Terapkan
                    </button>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_filter_race" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Filter Data Race
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Kwitansi</label>
                            <input type="text"
                                id="race_filter_receipt_number"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Pembalap</label>
                            <input type="text"
                                id="race_filter_racer_name"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text"
                                id="race_filter_nik"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Start</label>
                            <input type="text"
                                id="race_filter_racer_number"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status No. Start</label>
                            <select id="race_filter_racer_number_duplicate"
                                class="form-select">

                                <option value="">Semua Data</option>
                                <option value="duplicate">Duplikat</option>
                                <option value="unique">Tidak Duplikat</option>

                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Team</label>
                            <input type="text"
                                id="race_filter_team_name"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota</label>
                            <input type="text"
                                id="race_filter_city"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text"
                                id="race_filter_class_name"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kendaraan</label>
                            <input type="text"
                                id="race_filter_vehicle"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Rangka</label>
                            <input type="text"
                                id="race_filter_chassis_number"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Mesin</label>
                            <input type="text"
                                id="race_filter_engine_number"
                                class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Foto Diri</label>
                            <select id="race_filter_has_photo"
                                class="form-select">
                                <option value="">Semua</option>
                                <option value="1">Ada</option>
                                <option value="0">Tidak Ada</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">KIS</label>
                            <select id="race_filter_has_kis"
                                class="form-select">
                                <option value="">Semua</option>
                                <option value="1">Ada</option>
                                <option value="0">Tidak Ada</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">KTA</label>
                            <select id="race_filter_has_kta"
                                class="form-select">
                                <option value="">Semua</option>
                                <option value="1">Ada</option>
                                <option value="0">Tidak Ada</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        id="reset-filter-race"
                        class="btn btn-light">

                        Reset

                    </button>

                    <button type="button"
                        id="apply-filter-race"
                        class="btn btn-primary">

                        Terapkan

                    </button>

                </div>

            </div>

        </div>

    </div>
@endif
@if (Route::is(['events-payment']))
    <div class="modal fade" id="modal_approval_payment" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">

                    <h5 class="modal-title">
                        Upload Bukti Pembayaran
                    </h5>

                    <button type="button"
                        class="btn-close custom-btn-close border p-1 me-0 text-dark"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>

                <!-- FORM -->
                <form id="form-approval-payment" enctype="multipart/form-data">

                    <input type="hidden" id="approval_registration_id">

                    <div class="modal-body">

                        <!-- TOTAL -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Jumlah Pembayaran
                            </label>

                            <input type="text"
                                id="approval_total_price"
                                class="form-control fw-semibold bg-light"
                                readonly>

                        </div>

                        <!-- REKENING -->
                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Transfer Ke
                            </label>

                            <div class="border rounded p-3 bg-light">

                                <div class="fw-semibold" id="approval_bank_name">
                                    BCA
                                </div>

                                <div id="approval_account_number">
                                    1234567890
                                </div>

                                <small class="text-muted"
                                    id="approval_account_name">

                                    A/N Raihan

                                </small>

                            </div>

                        </div>

                        <!-- UPLOAD -->
                        <div class="mb-0">

                            <label class="form-label fw-semibold">
                                Upload Bukti Pembayaran
                            </label>

                            <input type="file"
                                class="form-control file-preview-input"
                                data-preview="preview-payment-proof"
                                id="approval_payment_file"
                                name="payment_proof"
                                accept="image/*">
                            <div id="preview-payment-proof" class="single-preview-container mt-2"></div>

                            <small class="text-dark d-block mt-2">
                                *Maksimal besar file
                                <strong class="text-dar fw-bold"> 2MB</strong>
                            </small>

                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit"
                            class="btn btn-primary">

                            <i class="ti ti-upload me-1"></i>
                            Upload

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
    <div id="event-detail"
        class="modal fade"
        tabindex="-1"
        role="dialog"
        aria-labelledby="event-detailLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">

                    <h4 class="modal-title" id="event-detailLabel">
                        Detail Event
                    </h4>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row">

                        <!-- LEFT -->
                        <div class="col-lg-8">

                            <div class="mb-3">

                                <h3 class="fw-bold mb-1" id="detail_event_name">
                                    -
                                </h3>

                                <p class="text-muted mb-0">
                                    <i class="ti ti-map-pin me-1"></i>

                                    <span id="detail_event_location">
                                        -
                                    </span>
                                </p>

                            </div>

                            <div class="border rounded p-3 mb-3 bg-light">

                                <h6 class="fw-semibold mb-2">
                                    Deskripsi Event
                                </h6>

                                <div id="detail_event_description"
                                    class="text-muted">

                                    -

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="border rounded p-3 mb-3">

                                        <h6 class="fw-semibold mb-2">
                                            Tanggal Registrasi
                                        </h6>

                                        <div class="text-muted">

                                            <div>
                                                <i class="ti ti-calendar me-1"></i>

                                                <span id="detail_registration_date">
                                                    -
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="border rounded p-3 mb-3">

                                        <h6 class="fw-semibold mb-2">
                                            Tanggal Event
                                        </h6>

                                        <div class="text-muted">

                                            <div>
                                                <i class="ti ti-flag-3 me-1"></i>

                                                <span id="detail_event_date">
                                                    -
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-lg-4">

                            <!-- PAYMENT -->
                            <div class="border rounded p-3 mb-3">

                                <h6 class="fw-bold mb-3">
                                    Rekening Pembayaran
                                </h6>

                                <div class="mb-2">

                                    <small class="text-muted d-block">
                                        Bank
                                    </small>

                                    <span id="detail_bank_name"
                                        class="fw-semibold">
                                        -
                                    </span>

                                </div>

                                <div class="mb-2">

                                    <small class="text-muted d-block">
                                        Nomor Rekening
                                    </small>

                                    <span id="detail_account_number"
                                        class="fw-semibold">
                                        -
                                    </span>

                                </div>

                                <div>

                                    <small class="text-muted d-block">
                                        Atas Nama
                                    </small>

                                    <span id="detail_account_holder"
                                        class="fw-semibold">
                                        -
                                    </span>

                                </div>

                            </div>

                            <!-- CONTACT PERSON -->
                            <div class="border rounded p-3">

                                <h6 class="fw-bold mb-3">
                                    Contact Person
                                </h6>

                                <div class="mb-2">

                                    <small class="text-muted d-block">
                                        Nama
                                    </small>

                                    <span id="detail_cp_name"
                                        class="fw-semibold">
                                        -
                                    </span>

                                </div>

                                <div class="mb-2">

                                    <small class="text-muted d-block">
                                        Nomor HP
                                    </small>

                                    <span id="detail_cp_phone"
                                        class="fw-semibold">
                                        -
                                    </span>

                                </div>

                                <div>

                                    <small class="text-muted d-block">
                                        Whatsapp
                                    </small>

                                    <a href="#"
                                        target="_blank"
                                        id="detail_cp_whatsapp"
                                        class="btn btn-success btn-sm mt-1">

                                        <i class="ti ti-brand-whatsapp me-1"></i>
                                        Hubungi

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>
@endif
@if (Route::is(['events-payment', 'event-details']))
<div class="modal fade" id="modal_invoice_race" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 shadow-sm rounded-3">

            <div class="modal-header py-3 px-4 border-bottom">
                <div>
                    <h5 class="modal-title fw-semibold mb-0">Cetak Kwitansi</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form-print-invoice">
                <div class="modal-body px-4 py-3">

                    <div class="mb-0">
                        <label class="form-label fw-semibold mb-2">Pilih Kelas Balap</label>
                        <select class="form-select" id="select_invoice_class" name="registration_class_id" required>
                            <option value="">-- Pilih Kelas --</option>
                        </select>
                        <div class="form-text text-muted mt-1">
                            Silakan pilih salah satu kelas untuk mencetak formulir, kwitansi, dan lembar scrutineering.
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-3 px-4 border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-printer me-1"></i> Cetak
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endif

@if (Route::is(['racers']))
    <!-- Add User -->
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">
            <h5 class="fw-semibold">Tambah Pembalap</h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('racers.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nama Lengkap (Asli)
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="full_name"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name') }}">

                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nama Alias
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="short_name"
                            class="form-control @error('short_name') is-invalid @enderror"
                            value="{{ old('short_name') }}">

                        @error('short_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            NIK
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="nik"
                            class="form-control @error('nik') is-invalid @enderror"
                            value="{{ old('nik') }}">

                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            No. HP / WA Pembalap
                        </label>

                        <input type="text"
                            name="phone_number"
                            class="form-control @error('phone_number') is-invalid @enderror"
                            value="{{ old('phone_number') }}">

                        @error('phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Asal Kota
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="hometown"
                            class="form-control @error('hometown') is-invalid @enderror"
                            value="{{ old('hometown') }}">

                        @error('hometown')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            No. Start
                            <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                            name="racer_number"
                            class="form-control @error('racer_number') is-invalid @enderror"
                            value="{{ old('racer_number') }}">

                        @error('racer_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Tempat Lahir
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="birth_location"
                            class="form-control @error('birth_location') is-invalid @enderror"
                            value="{{ old('birth_location') }}">

                        @error('birth_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Tanggal Lahir
                            <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                            name="birth_date"
                            class="form-control @error('birth_date') is-invalid @enderror"
                            value="{{ old('birth_date') }}">

                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FOTO --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Foto Diri
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="photo"
                            class="form-control file-preview-input @error('photo') is-invalid @enderror"
                            data-preview="preview-racer-photo"
                            accept="image/*">

                        @error('photo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div id="preview-racer-photo"
                            class="single-preview-container mt-2"></div>
                    </div>

                    {{-- KIS --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            KIS
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="kis"
                            class="form-control file-preview-input @error('kis') is-invalid @enderror"
                            data-preview="preview-racer-kis"
                            accept="image/*">

                        @error('kis')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div id="preview-racer-kis"
                            class="single-preview-container mt-2"></div>
                    </div>

                    {{-- KTA --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            KTA
                        </label>

                        <input type="file"
                            name="kta"
                            class="form-control file-preview-input @error('kta') is-invalid @enderror"
                            data-preview="preview-racer-kta"
                            accept="image/*">

                        @error('kta')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div id="preview-racer-kta"
                            class="single-preview-container mt-2"></div>
                    </div>

                    <div class="col-12">
                        <small class="text-dark">
                            * Foto diri, KIS dan KTA maksimal ukuran file
                            <strong>10 MB</strong>
                        </small>
                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">
                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add User -->

    <!-- Edit User -->
    {{-- OFFCANVAS EDIT --}}
    <div class="offcanvas offcanvas-end offcanvas-large"
        tabindex="-1"
        id="offcanvas_edit">

        <div class="offcanvas-header border-bottom">

            <h5 class="fw-semibold">
                Edit Pembalap
            </h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas">

                <i class="ti ti-x"></i>
            </button>
        </div>

        <div class="offcanvas-body">

            <form id="form-edit-racer"
                enctype="multipart/form-data">

                @csrf

                {{-- ID --}}
                <input type="hidden"
                    id="edit_id"
                    name="id">

                <div class="row">

                    {{-- FULL NAME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Nama Lengkap
                        </label>

                        <input type="text"
                            id="edit_full_name"
                            name="full_name"
                            class="form-control">

                        <div class="invalid-feedback error-full_name"></div>
                    </div>

                    {{-- SHORT NAME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Nama Alias
                        </label>

                        <input type="text"
                            id="edit_short_name"
                            name="short_name"
                            class="form-control">

                        <div class="invalid-feedback error-short_name"></div>
                    </div>

                    {{-- NIK --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            NIK
                        </label>

                        <input type="text"
                            id="edit_nik"
                            name="nik"
                            class="form-control">

                        <div class="invalid-feedback error-nik"></div>
                    </div>

                    {{-- PHONE --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            No HP
                        </label>

                        <input type="text"
                            id="edit_phone_number"
                            name="phone_number"
                            class="form-control">

                        <div class="invalid-feedback error-phone_number"></div>
                    </div>

                    {{-- HOMETOWN --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Asal Kota
                        </label>

                        <input type="text"
                            id="edit_hometown"
                            name="hometown"
                            class="form-control">

                        <div class="invalid-feedback error-hometown"></div>
                    </div>

                    {{-- RACER NUMBER --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            No Start
                        </label>

                        <input type="number"
                            id="edit_racer_number"
                            name="racer_number"
                            class="form-control">

                        <div class="invalid-feedback error-racer_number"></div>
                    </div>

                    {{-- BIRTH LOCATION --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Tempat Lahir
                        </label>

                        <input type="text"
                            id="edit_birth_location"
                            name="birth_location"
                            class="form-control">

                        <div class="invalid-feedback error-birth_location"></div>
                    </div>

                    {{-- BIRTH DATE --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Tanggal Lahir
                        </label>

                        <input type="date"
                            id="edit_birth_date"
                            name="birth_date"
                            class="form-control">

                        <div class="invalid-feedback error-birth_date"></div>
                    </div>

                    {{-- PHOTO --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Foto
                        </label>

                        <input type="file"
                            id="edit_photo"
                            name="photo"
                            class="form-control file-preview-input"
                            data-preview="preview_photo">

                        <div class="invalid-feedback error-photo"></div>

                        <div id="preview_photo"
                            class="mt-2"></div>
                    </div>

                    {{-- KIS --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            KIS
                        </label>

                        <input type="file"
                            id="edit_kis"
                            name="kis"
                            class="form-control file-preview-input"
                            data-preview="preview_kis">

                        <div class="invalid-feedback error-kis"></div>

                        <div id="preview_kis"
                            class="mt-2"></div>
                    </div>

                    {{-- KTA --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            KTA
                        </label>

                        <input type="file"
                            id="edit_kta"
                            name="kta"
                            class="form-control file-preview-input"
                            data-preview="preview_kta">

                        <div class="invalid-feedback error-kta"></div>

                        <div id="preview_kta"
                            class="mt-2"></div>
                    </div>

                </div>

                <div class="d-flex justify-content-end mt-3">

                    <button type="button"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">

                        Batal
                    </button>

                    <button type="submit"
                        class="btn btn-primary btn-submit-edit">

                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>
    <!-- /Edit User -->

    <!-- delete modal -->
    <div class="modal fade" id="delete_racer">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete-racer"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal -->
@endif
@if (Route::is(['regulations']))
    <div class="offcanvas offcanvas-end offcanvas-large" tabindex="-1" id="offcanvas_add">
        <div class="offcanvas-header border-bottom">

            <h5 class="fw-semibold">
                Tambah Regulasi
            </h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas"
                aria-label="Close">

                <i class="ti ti-x"></i>

            </button>

        </div>

        <div class="offcanvas-body">

            <form action="{{ route('regulations.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="row">

                    {{-- TITLE --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Judul Regulasi
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}">

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            name="description"
                            rows="5"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- FILE --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            File Regulasi (PDF)
                            <span class="text-danger">*</span>
                        </label>

                        <input type="file"
                            name="file"
                            accept=".pdf"
                            class="form-control @error('file') is-invalid @enderror">

                        @error('file')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- STATUS --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <div class="form-check form-switch">

                            <input class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active') ? 'checked' : '' }}>

                            <label class="form-check-label">
                                Aktif
                            </label>

                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-dark">
                            * File regulasi harus berformat
                            <strong>PDF</strong>
                            dengan ukuran maksimal
                            <strong>10 MB</strong>.
                        </small>

                    </div>

                </div>

                <div class="d-flex align-items-center justify-content-end mt-3">

                    <a href="#"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>
    </div>

    <div class="offcanvas offcanvas-end offcanvas-large"
        tabindex="-1"
        id="offcanvas_edit">

        <div class="offcanvas-header border-bottom">

            <h5 class="fw-semibold">
                Edit Regulasi
            </h5>

            <button type="button"
                class="btn-close custom-btn-close border p-1 me-0 d-flex align-items-center justify-content-center rounded-circle"
                data-bs-dismiss="offcanvas">

                <i class="ti ti-x"></i>
            </button>

        </div>

        <div class="offcanvas-body">

            <form id="form-edit-regulation"
                enctype="multipart/form-data">

                @csrf

                <input type="hidden"
                    id="edit_id"
                    name="id">

                <div class="row">

                    {{-- JUDUL --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Judul Regulasi
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            id="edit_title"
                            name="title"
                            class="form-control">

                        <div class="invalid-feedback error-title"></div>

                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea
                            id="edit_description"
                            name="description"
                            rows="5"
                            class="form-control"></textarea>

                        <div class="invalid-feedback error-description"></div>

                    </div>

                    {{-- FILE --}}
                    <div class="col-12 mb-3">

                        <label class="form-label">
                            File Regulasi (PDF)
                        </label>

                        <input type="file"
                            id="edit_file"
                            name="file"
                            accept=".pdf"
                            class="form-control">

                        <div class="invalid-feedback error-file"></div>

                        <div id="current-file" class="mt-2"></div>

                    </div>

                    {{-- STATUS --}}
                    <div class="col-12 mb-3">

                        <label class="form-label d-block">
                            Status
                        </label>

                        <div class="form-check form-switch">

                            <input class="form-check-input"
                                type="checkbox"
                                id="edit_is_active"
                                name="is_active"
                                value="1">

                            <label class="form-check-label"
                                for="edit_is_active">

                                Aktif

                            </label>

                        </div>

                    </div>

                </div>

                <div class="d-flex justify-content-end mt-4">

                    <button type="button"
                        class="btn btn-light me-2"
                        data-bs-dismiss="offcanvas">

                        Batal

                    </button>

                    <button type="submit"
                        class="btn btn-primary">

                        Update

                    </button>

                </div>

            </form>

        </div>
    </div>
    <div class="modal fade" id="delete_regulation">
        <div class="modal-dialog modal-dialog-centered modal-sm rounded-0">
            <div class="modal-content rounded-0">
                <div class="modal-body p-4 text-center position-relative">
                    <div class="mb-3 position-relative z-1">
                        <span class="avatar avatar-xl badge-soft-danger border-0 text-danger rounded-circle"><i class="ti ti-trash fs-24"></i></span>
                    </div>
                    <h5 class="mb-1">Konfirmasi Hapus</h5>
                    <p class="mb-3">Apakah anda yakin ingin menghapus data?</p>
                    <div class="d-flex justify-content-center">
                        <a href="#" class="btn btn-light position-relative z-1 me-2 w-100" data-bs-dismiss="modal">Batal</a>
                        <a href="#"
                        class="btn btn-primary position-relative z-1 w-100 btn-confirm-delete-regulation"
                        data-bs-dismiss="modal">
                            Ya, Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        @if ($errors->any() || session('open_offcanvas'))
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_add'));
            offcanvas.show();
        @endif

    });
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        @if ($errors->any() || session('open_offcanvas'))
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('offcanvas_add'));
            offcanvas.show();
        @endif

    });
</script>
@endpush
