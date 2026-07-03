<?php $page = 'report'; ?>
@extends('layout.mainlayout')

@section('content')

<div class="page-wrapper">

    <div class="content">

        <div class="content min-vh-75">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Export Laporan</h4>
                        </div>

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">
                                    Tipe Laporan
                                </label>

                                <select id="report_type" class="form-control">
                                    {{-- <option value="">Pilih Tipe</option> --}}
                                    <option value="race">Race</option>
                                    {{-- <option value="pendaftar">Pendaftar</option> --}}
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Event
                                </label>

                                <select id="event_id" class="form-control">
                                    <option value="">Pilih Event</option>

                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">
                                            {{ $event->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button
                                type="button"
                                id="btn_export"
                                class="btn btn-primary">
                                <i class="ti ti-download me-1"></i>
                                Export
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        @component('components.footer')
        @endcomponent

    </div>

</div>

@endsection

@push('scripts')
<script>
$(function () {

    $('#btn_export').on('click', function () {

        const type = $('#report_type').val();
        const eventId = $('#event_id').val();

        if (!type) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih tipe laporan'
            });
            return;
        }

        if (!eventId) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih event'
            });
            return;
        }

        let url = '';

        if (type === 'race') {
            url = `/race/${eventId}/export`;
        } else if (type === 'pendaftar') {
            url = `/pendaftar/${eventId}/export`;
        }

        window.location.href = url;
    });

});
</script>
@endpush
