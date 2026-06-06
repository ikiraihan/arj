    $(document).ready(function () {
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },

        headers: {
            'X-XSRF-TOKEN': decodeURIComponent(
                document.cookie
                    .split('; ')
                    .find(row => row.startsWith('XSRF-TOKEN='))
                    ?.split('=')[1]
            )
        }
    });
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        return null;
    }
    /* =========================
    FILTER STATUS
    ========================= */

    if ($('#event-payment-table').length > 0) {

        const userId = $('#event-payment-table').data('user-id');

        const table = $('#event-payment-table').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: `/api/event/payment/${userId}`,
                type: 'GET',

                data: function (d) {
                    d.search_payments = $('#search-payments').val();

                    d.status = $('.filter-status:checked')
                        .map(function () {
                            return $(this).val();
                        })
                        .get();
                }
            },

            bFilter: false,
            bInfo: false,
            ordering: true,
            autoWidth: true,

            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search event",
                info: "_START_ - _END_ of _TOTAL_ payments",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="ti ti-chevron-right"></i>',
                    previous: '<i class="ti ti-chevron-left"></i>'
                },
            },

            initComplete: (settings, json) => {

                $('.dataTables_paginate').appendTo('.datatable-paginate');
                $('.dataTables_length').appendTo('.datatable-length');

            },

            columns: [

                // ACTION
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {

                        // tampil hanya jika transfer & menunggu pembayaran
                        if (
                            row.payment_method === 'transfer' &&
                            row.status === 'menunggu-pembayaran'
                        ) {

                            return `
                                <div class="d-flex justify-content-center">

                                    <button type="button"
                                        class="btn btn-sm btn-primary btn-upload-payment d-flex align-items-center justify-content-center"

                                        data-id="${row.id}"
                                        data-racer="${row.racer?.full_name ?? '-'}"
                                        data-total="${row.total_price ?? 0}"

                                        data-bank="${row.payment_account?.bank_name ?? '-'}"
                                        data-account="${row.payment_account?.account_number ?? '-'}"
                                        data-account-name="${row.payment_account?.account_holder_name ?? '-'}"

                                        data-bs-toggle="modal"
                                        data-bs-target="#modal_approval_payment">

                                        <i class="ti ti-upload fs-16"></i>

                                    </button>

                                </div>
                            `;
                        }

                        // tampil hanya jika transfer & menunggu pembayaran
                        if (
                            row.status === 'paid'
                        ) {

                            return `
                                <div class="d-flex justify-content-center">

                                        <button type="button" class="btn btn-sm btn-primary btn-open-invoice-info d-flex align-items-center justify-content-center"
                                            href="javascript:void(0);"
                                            data-id="${row.id}"
                                            data-classes="${encodeURIComponent(JSON.stringify( row.classes ?? []))}"                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_invoice_info">

                                                <i class="ti ti-eye fs-16"></i>

                                        </button>

                                </div>
                            `;
                        }


                        return `
                            <div class="d-flex justify-content-center">
                                <span class="text-muted">-</span>
                            </div>
                        `;
                    }
                },

                // NAMA PEMBALAP
                {
                    data: 'racer.full_name',

                    render: function (data, type, row) {

                        return `
                            <div class="d-flex flex-column">

                                <span class="fw-semibold text-dark">
                                    ${row.racer?.full_name ?? '-'}
                                </span>

                                <small class="text-dark">
                                    ${row.racer?.short_name ?? '-'}
                                </small>

                            </div>
                        `;
                    }
                },

                // TRANSFER KE
                {
                    data: 'payment_account',

                    render: function (data, type, row) {

                        // CASH
                        if (
                            row.payment_method === 'cash' ||
                            row.payment_method === 'tunai'
                        ) {

                            return `
                                <span class="badge badge-soft-info border border-info fw-medium">
                                    Bayar di Venue
                                </span>
                            `;
                        }

                        // TRANSFER
                        return `
                            <div class="d-flex flex-column">

                                <span class="fw-semibold text-dark">
                                    ${row.payment_account?.bank_name ?? '-'}
                                </span>

                                <small class="text-dark">
                                    ${row.payment_account?.account_number ?? '-'}
                                </small>

                                <small class="text-dark">
                                    ${row.payment_account?.account_holder_name ?? '-'}
                                </small>

                            </div>
                        `;
                    }
                },

                // METODE PEMBAYARAN
                {
                    data: 'payment_method',

                    render: function (data, type, row) {

                        let badgeClass = 'bg-secondary';
                        let label = '-';
                        let icon = 'ti ti-wallet';

                        // TRANSFER
                        if (row.payment_method === 'transfer') {
                            badgeClass = 'badge-soft-secondary border border-secondary';
                            label = 'Transfer';
                            icon = 'ti ti-building-bank';
                        }

                        // CASH
                        else if (
                            row.payment_method === 'cash' ||
                            row.payment_method === 'tunai'
                        ) {
                            badgeClass = 'badge-soft-success border border-success';
                            label = 'Tunai';
                            icon = 'ti ti-cash';
                        }

                        return `
                            <span class="badge ${badgeClass} fw-medium d-inline-flex align-items-center gap-1">

                                <i class="${icon}"></i>

                                ${label}

                            </span>
                        `;
                    }
                },

                // STATUS PEMBAYARAN
                {
                    data: 'status',

                    render: function (data, type, row) {

                        let badgeClass = 'badge-soft-warning border border-warning';
                        let label = 'Pending';

                        // PAID
                        if (row.status === 'paid') {
                            badgeClass = 'bg-success';
                            label = 'Lunas';
                        }

                        // UNPAID
                        else if (row.status === 'unpaid') {
                            badgeClass = 'bg-danger';
                            label = 'Belum Bayar';
                        }

                        // WAITING VERIFICATION
                        else if (row.status === 'menunggu-pembayaran') {
                            badgeClass = 'bg-danger';
                            label = 'Menunggu Pembayaran';
                        }

                        else if (row.status === 'menunggu-approval') {
                            badgeClass = 'bg-secondary';
                            label = 'Menunggu Approval';
                        }

                        // REJECTED
                        else if (row.status === 'rejected') {
                            badgeClass = 'bg-danger';
                            label = 'Ditolak';
                        }

                        return `
                            <span class="badge ${badgeClass} fw-medium">
                                ${label}
                            </span>
                        `;
                    }
                },

                // TOTAL HARGA
                {
                    data: 'total_price',

                    render: function (data, type, row) {

                        return `
                            <div class="d-flex flex-column">

                                <span class="fw-bold text-dark fs-14">
                                    Rp ${Number(row.total_price ?? 0).toLocaleString('id-ID')}
                                </span>

                                <small class="text-muted">
                                    ${row.count_class ?? 0} Kelas
                                </small>

                            </div>
                        `;
                    }
                },

                {
                        data: 'created_at',

                        render: function (data, type, row) {

                            return `
                                <span>
                                    ${moment(row.created_at).format('DD MMM YYYY HH:mm')}
                                </span>
                            `;
                        }
                    },

                // NAMA EVENT
                {
                    data: 'event.name',

                    render: function (data, type, row) {

                        return `
                            <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">

                                <a href="javascript:void(0);"
                                    class="d-flex flex-column fw-medium text-dark text-decoration-none btn-event-detail event-hover"

                                    data-bs-toggle="modal"
                                    data-bs-target="#event-detail"

                                    data-name="${row.event?.name ?? '-'}"
                                    data-description="${row.event?.description ?? '-'}"
                                    data-location="${row.event?.venue ?? '-'}"
                                    data-link-maps="${row.event?.link_maps ?? '-'}"
                                    data-registration="${new Date(row.event?.registration_start_date).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })} - ${new Date(row.event?.registration_end_date).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })}"

                                    data-eventdate="${new Date(row.event?.start_date).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })} - ${new Date(row.event?.end_date).toLocaleDateString('id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    })}"

                                    data-bank="${row.event?.payment_account?.bank_name ?? '-'}"
                                    data-account="${row.event?.payment_account?.account_number ?? '-'}"
                                    data-holder="${row.event?.payment_account?.account_holder_name ?? '-'}"

                                    data-contact-persons='${encodeURIComponent(
                                        JSON.stringify(row.event?.contact_persons ?? [])
                                    )}'
                                    >

                                    <span class="event-title">
                                        ${row.event?.name ?? '-'}
                                    </span>

                                    <small class="text-muted">
                                        ${row.event?.kota ?? '-'}
                                    </small>

                                </a>

                            </h6>
                        `;
                    }
                },

            ]
        });

        // SEARCH (like users)
        $(document).on('keyup', '#search-payments', function () {
            console.log('typing:', $(this).val()); // debug
            table.ajax.reload();
        });

        // REFRESH (optional)
        $('#refresh-events').on('click', function () {
            table.ajax.reload(null, false);
        });

        $(document).on('change', '.filter-status', function () {

            table.ajax.reload();

        });

        /* =========================
        RESET FILTER
        ========================= */
        $(document).on('click', '.btn-reset-filter', function () {

            $('.filter-status').prop('checked', false);

            table.ajax.reload();

        });

    }

    $(document).on('click', '.btn-upload-payment', function () {

        $('#approval_registration_id').val($(this).data('id'));

        $('#approval_racer_name').val($(this).data('racer'));

        $('#approval_total_price').val(
            'Rp ' + Number($(this).data('total')).toLocaleString('id-ID')
        );

        $('#approval_bank_name').text($(this).data('bank'));

        $('#approval_account_number').text($(this).data('account'));

        $('#approval_account_name').text($(this).data('account-name'));

    });

    $(document).on('click', '.btn-event-detail', function () {

        const contactPersons = JSON.parse(
            decodeURIComponent(
                $(this).attr('data-contact-persons') || '[]'
            )
        );

        $('#detail_event_name').text($(this).data('name'));
        $('#detail_event_location').text($(this).data('location'));
        $('#detail_event_link_maps').attr('href', $(this).data('link-maps'));
        $('#detail_event_description').text($(this).data('description'));

        $('#detail_registration_date').text($(this).data('registration'));
        $('#detail_event_date').text($(this).data('eventdate'));

        $('#detail_bank_name').text($(this).data('bank'));
        $('#detail_account_number').text($(this).data('account'));
        $('#detail_account_holder').text($(this).data('holder'));

        let cpHtml = '';

        if (contactPersons.length) {

            contactPersons.forEach(cp => {

                cpHtml += `
                    <div class="border rounded p-2 mb-2">

                        <div>
                            <small class="text-muted d-block">
                                Nama
                            </small>

                            <span class="fw-semibold">
                                ${cp.name ?? '-'}
                            </span>
                        </div>

                        <div class="mt-2">
                            <small class="text-muted d-block">
                                Nomor HP
                            </small>

                            <span class="fw-semibold">
                                ${cp.phone_number ?? '-'}
                            </span>
                        </div>

                        <a href="https://wa.me/${cp.phone_number ?? ''}"
                            target="_blank"
                            class="btn btn-success btn-sm mt-2">

                            <i class="ti ti-brand-whatsapp me-1"></i>
                            Hubungi

                        </a>

                    </div>
                `;

            });

        } else {

            cpHtml = `
                <div class="text-muted">
                    Contact person tidak tersedia
                </div>
            `;

        }

        $('#contact-person-list').html(cpHtml);

    });

    /* =========================
    SINGLE IMAGE PREVIEW
    ========================= */
    document.addEventListener('change', function (e) {

        if (e.target.classList.contains('file-preview-input')) {

            const input = e.target;
            const file = input.files[0];

            const previewId = input.getAttribute('data-preview');
            const previewContainer = document.getElementById(previewId);

            previewContainer.innerHTML = '';

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (ev) {

                const div = document.createElement('div');

                div.classList.add('single-preview-box');

                div.innerHTML = `
                    <img src="${ev.target.result}" alt="Preview">

                    <button type="button"
                        class="single-preview-remove">
                        ×
                    </button>
                `;

                previewContainer.appendChild(div);

                // REMOVE
                div.querySelector('.single-preview-remove')
                    .addEventListener('click', function () {

                        input.value = '';
                        previewContainer.innerHTML = '';

                    });
            };

            reader.readAsDataURL(file);
        }
    });

    function resetImagePreview() {

        // reset input file
        $('.file-preview-input').val('');

        // reset semua preview container
        $('.single-preview-container').html('');
    }

    $('#modal_approval_payment').on('hidden.bs.modal', function () {

        resetImagePreview();

    });

    $('#modal_approval_payment').on('show.bs.modal', function () {

        resetImagePreview();

    });

    $('#form-approval-payment').on('submit', function (e) {

        e.preventDefault();

        const registrationId = $('#approval_registration_id').val();

        let formData = new FormData(this);

        $.ajax({

            url: `/api/event/payment/upload/${registrationId}`,
            type: 'POST',
            data: formData,

            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {

                $('.btn-upload-payment').prop('disabled', true);

            },

            success: function (res) {

                 Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false,
                    showCloseButton: true
                });

                $('#modal_approval_payment').modal('hide');

                $('#event-payment-table').DataTable().ajax.reload(null, false);

            },

            error: function (xhr) {

                let message = 'Terjadi kesalahan';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                    timer: 3500,
                    showCloseButton: true
                });

            },

            complete: function () {

                $('.btn-upload-payment').prop('disabled', false);

            }

        });

    });

    $(document).on('click', '.btn-open-invoice-info', function () {

        const classes = JSON.parse(
            decodeURIComponent($(this).data('classes'))
        );

        const container = $('#invoice-class-list');

        container.empty();

        if (!classes.length) {

            container.html(`
                <div class="alert alert-light border mb-0">
                    Tidak ada data kelas.
                </div>
            `);

            return;
        }

        classes.forEach(item => {

            container.append(`
                <div class="border rounded p-3 mb-2">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="fw-semibold">
                                ${item.class_name ?? '-'}
                            </div>
                        </div>

                        <span class="badge bg-info">
                            ${item.invoice_number ?? '-'}
                        </span>

                    </div>

                </div>
            `);

        });

    });

});
