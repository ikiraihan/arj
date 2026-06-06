<?php $page = 'companies'; ?>
@extends('layout.mainlayout')
@section('content')

    <!-- ========================
        Start Page Content
    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-10">

                    <div class="mb-3">
                        <a href="{{ route('events') }}"><i class="ti ti-arrow-narrow-left me-1"></i>Kembali</a>
                    </div>
                    <div class="card shadow-sm border-0">

                        <div class="card-header py-3 text-center">
                            <p class="text-muted mb-0">
                                {{ $event->name }}
                            </p>
                            <h4 class="card-title mb-1">
                                    Lengkapi Form Registrasi dibawah untuk mengikuti event
                            </h4>
                        </div>

                        <div class="card-body">

                            <form id="form-register-event">
                                <input type="hidden" name="event_id" value="{{ $eventId }}">
                                {{-- Racer --}}
                                <div class="row mb-3">

                                    <label class="col-lg-3 form-label">
                                        Pilih Pembalap
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-9">

                                        <select
                                            name="racer_id"
                                            id="racer_id"
                                            class="form-select select2-racer"
                                            data-user-id="{{ auth()->id() }}"
                                            style="width:100%">

                                        </select>
                                        <div class="invalid-feedback d-block"></div>
                                        <small class="text-dark d-block mt-2">
                                            *Jika nama pembalap belum tersedia pada daftar, silahkan pilih
                                            <strong class="text-dark fw-bold">“+ Tambah Pembalap Baru”</strong>
                                            untuk menambahkan data pembalap baru.
                                        </small>

                                    </div>

                                </div>

                                {{-- FORM PEMBALAP BARU --}}
                                <div id="new-racer-form" style="display:none;">

                                    <hr>

                                    <h5 class="mb-3">
                                        Tambah Pembalap Baru
                                    </h5>

                                    <div class="row">

                                        {{-- Full Name --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Lengkap (Asli)
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                name="racer_full_name"
                                                class="form-control">
                                        </div>

                                        {{-- Short Name --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nama Alias
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                name="racer_short_name"
                                                class="form-control">
                                        </div>

                                        {{-- NIK --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">NIK
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                name="racer_nik"
                                                class="form-control">
                                        </div>

                                        {{-- Phone --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No. HP / WA Pembalap</label>

                                            <input type="number"
                                                name="racer_phone_number"
                                                class="form-control">
                                        </div>

                                        {{-- Hometown --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Asal Kota
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                name="racer_hometown"
                                                class="form-control">
                                        </div>

                                        @if ($event->type == 'race')
                                        {{-- No. Start --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">No. Start
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="number"
                                                name="racer_number"
                                                class="form-control">
                                        </div>
                                        @endif

                                        {{-- Birth Location --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tempat Lahir
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="text"
                                                name="racer_birth_location"
                                                class="form-control">
                                        </div>

                                        {{-- Birth Date --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tanggal Lahir
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="date"
                                                name="racer_birth_date"
                                                class="form-control">
                                        </div>

                                        {{-- Photo --}}
                                        <div class="col-md-4 mb-3">

                                            <label class="form-label">
                                                Foto Diri
                                                <span class="text-danger">*</span>
                                            </label>

                                            <input type="file"
                                                name="racer_photo"
                                                class="form-control file-preview-input"
                                                data-preview="preview-racer-photo"
                                                accept="image/*">

                                            <div class="invalid-feedback"></div>

                                            <!-- PREVIEW -->
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
                                                name="racer_kis"
                                                class="form-control file-preview-input"
                                                data-preview="preview-racer-kis"
                                                accept="image/*">

                                            <div class="invalid-feedback"></div>

                                            <!-- PREVIEW -->
                                            <div id="preview-racer-kis"
                                                class="single-preview-container mt-2"></div>

                                        </div>

                                        {{-- KTA --}}
                                        <div class="col-md-4 mb-3">

                                            <label class="form-label">
                                                KTA
                                            </label>

                                            <input type="file"
                                                name="racer_kta"
                                                class="form-control file-preview-input"
                                                data-preview="preview-racer-kta"
                                                accept="image/*">

                                            <div class="invalid-feedback"></div>

                                            <!-- PREVIEW -->
                                            <div id="preview-racer-kta"
                                                class="single-preview-container mt-2"></div>

                                        </div>

                                        <small class="text-dark d-block mt-2">
                                            *Foto diri, KIS dan KTA wajib diisi dengan maksimal besar file
                                            <strong class="text-dar fw-bold"> 10MB</strong>
                                        </small>
                                    </div>
                                    <hr>

                                </div>

                               <div class="row mb-3">
                                    <label class="col-lg-3 form-label">
                                        Nama Team
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-9">
                                        <input type="text"
                                            name="team_name"
                                            value="{{ auth()->user()->team_name ?? '' }}"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">

                                    <label class="col-lg-3 form-label">
                                        Nama Penanggung Jawab
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-9">
                                        <input type="text"
                                            name="name_register"
                                            class="form-control"
                                            value="{{ auth()->user()->name ?? '' }}"
                                            placeholder="Masukkan Nama Penanggung Jawab"
                                            >
                                    </div>

                                </div>

                                {{-- No. HP --}}
                                <div class="row mb-3">

                                    <label class="col-lg-3 form-label">
                                        No. HP / WA Penanggung Jawab
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-9">
                                        <input type="number"
                                            name="phone_number_register"
                                            class="form-control"
                                            value="{{ auth()->user()->phone_number ?? '' }}"
                                            placeholder="Masukkan No. HP atau WA yang bisa dihubungi"
                                            >
                                    </div>

                                </div>


                                {{-- Kelas --}}
                                <div class="row mb-3">

                                    <label class="col-lg-3 form-label">
                                        Kelas Event
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-9">
                                        @foreach ($class as $item)

                                            <div class="border rounded p-3 mb-3">

                                                <div class="form-check ps-0 d-flex align-items-start">
                                                    <input
                                                        type="checkbox"
                                                        class="form-check-input class-checkbox me-3 mt-1"
                                                        id="event_class_{{ $item->id }}"
                                                        name="event_class_id[]"
                                                        value="{{ $item->id }}">

                                                    <label
                                                        class="form-check-label w-100"
                                                        for="event_class_{{ $item->id }}">

                                                        <div class="d-flex justify-content-between align-items-start">

                                                            <div>

                                                                <span class="fw-medium text-dark d-block">
                                                                    {{ $item->name }}
                                                                </span>

                                                                @if($item->notes)
                                                                    <small class="text-muted d-block mt-1">
                                                                        *{{ $item->notes }}
                                                                    </small>
                                                                @endif

                                                            </div>

                                                            <div class="text-end ms-4">

                                                                <span class="fw-semibold text-primary">
                                                                    Rp. {{ number_format($item->total_price, 0, ',', '.') }}
                                                                </span>

                                                            </div>

                                                        </div>

                                                    </label>

                                                </div>

                                                {{-- FORM DETAIL CLASS --}}
                                                <div
                                                    class="class-detail-form mt-3 ms-4"
                                                    style="display:none;">

                                                    <div class="row g-2">

                                                        <div class="col-md-4">

                                                            <label class="form-label small">
                                                                Kendaraan
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <input type="text"
                                                                name="class_detail[{{ $item->id }}][vehicle]"
                                                                class="form-control form-control-sm"
                                                                placeholder="Kendaraan">
                                                            <div class="invalid-feedback d-block"></div>
                                                        </div>

                                                        <div class="col-md-4">

                                                            <label class="form-label small">
                                                                No Mesin
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <input type="text"
                                                                name="class_detail[{{ $item->id }}][engine_number]"
                                                                class="form-control form-control-sm"
                                                                placeholder="4 Angka Terakhir"
                                                                maxlength="4"
                                                                inputmode="numeric"
                                                                pattern="[0-9]{4}"
                                                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)">
                                                            <div class="invalid-feedback d-block"></div>
                                                        </div>

                                                        <div class="col-md-4">

                                                            <label class="form-label small">
                                                                No Rangka
                                                                <span class="text-danger">*</span>
                                                            </label>

                                                            <input type="text"
                                                                name="class_detail[{{ $item->id }}][frame_number]"
                                                                class="form-control form-control-sm"
                                                                placeholder="4 Angka Terakhir"
                                                                maxlength="4"
                                                                inputmode="numeric"
                                                                pattern="[0-9]{4}"
                                                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4)">
                                                            <div class="invalid-feedback d-block"></div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        @endforeach

                                        <div class="invalid-feedback d-block"></div>
                                    </div>

                                </div>

                                {{-- Motor --}}
                                <div class="row mb-3">

                                    <label class="col-lg-3 form-label">
                                        Metode Pembayaran
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-9">
                                        <select  name="payment_method" class="form-control select2" data-toggle="select2">
											<option value="transfer">Transfer</option>
											<option value="tunai">Tunai</option>
									    </select>
                                        <div class="invalid-feedback d-block"></div>
                                        @if($rekening)
                                            <small class="text-muted d-block mt-2">
                                                *Jika memilih pembayaran dengan metode transfer, silahkan lakukan pembayaran ke rekening berikut:
                                                <strong class="text-dark fw-bold">{{ $rekening->bank_name }} - {{ $rekening->account_number }} A/N {{ $rekening->account_holder_name }}</strong>.
                                                lalu lakukan konfirmasi pembayaran yang terdapat pada menu <strong class="text-dark fw-bold">Registrasi Saya</strong>.
                                            </small>
                                        @endif
                                    </div>

                                </div>

                                {{-- Submit --}}
                                <div class="text-end">

                                    <button type="submit"
                                        class="btn btn-primary px-4">

                                        <i class="ti ti-send me-1"></i>
                                        Submit

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

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
