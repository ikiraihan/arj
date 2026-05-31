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
    function initClassTable() {

        if ($.fn.DataTable.isDataTable('#event-class-list-table')) {
            return;
        }
        if ($('#event-class-list-table').length > 0) {

            const eventId = $('#event-class-list-table').data('event-id');
            const table = $('#event-class-list-table').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: `/api/event-classes/${eventId}`,
                    type: 'GET',

                    data: function (d) {
                        d.search_event = $('#search-event-class').val();
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
                    info: "_START_ - _END_ of _TOTAL_ event-class",
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

                            return `
                                <div class="d-flex justify-content-center align-items-center">

                                    <div class="dropdown table-action">

                                        <a href="#"
                                        class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                        data-bs-toggle="dropdown">

                                            <i class="ti ti-dots-vertical"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">

                                            <a class="dropdown-item text-warning btn-edit-class"
                                                href="javascript:void(0);"
                                                data-id="${row.id}"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#offcanvas_edit_class">

                                                    <i class="ti ti-edit text-blue"></i>
                                                    Edit
                                            </a>

                                            <a class="dropdown-item text-danger btn-open-delete-class"
                                            href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete_class"
                                            data-id="${row.id}">

                                                <i class="ti ti-trash"></i>
                                                Hapus
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            `;
                        }
                    },

                    // NAME + CATEGORY (like USERS style nested info)
                    {
                        data: 'name',
                        render: function (data, type, row) {
                            return `
                                <h6 class="d-flex flex-column fs-14 fw-bold mb-0">
                                    ${row.name}
                                </h6>
                            `;
                        }
                    },
                    {
                        data: 'price_formatted',
                        render: function (data, type, row) {
                            return `
                                <h6 class="d-flex flex-column fs-14 fw-medium mb-0">
                                    ${row.price_formatted}
                                </h6>
                            `;
                        }
                    },
                    {
                        data: 'price_fine_formatted',
                        render: function (data, type, row) {
                            return `
                                <h6 class="d-flex flex-column fs-14 fw-medium mb-0">
                                    ${row.price_fine_formatted}
                                </h6>
                            `;
                        }
                    },
                    // {
                    //     data: 'quota',
                    //     render: function (data, type, row) {
                    //         return `
                    //             <h6 class="d-flex flex-column fs-14 fw-medium mb-0">
                    //                 ${row.quota ?? ''}
                    //             </h6>
                    //         `;
                    //     }
                    // },

                    {
                        data: 'notes'
                    },
                    {
                        data: 'created_at'
                    },

                ]

            });

            // SEARCH (like users)
            $(document).on('keyup', '#search-event-class', function () {
                console.log('typing:', $(this).val()); // debug
                table.ajax.reload();
            });

            // REFRESH (optional)
            $('#refresh-event-class').on('click', function () {
                table.ajax.reload(null, false);
            });

        }
    }

    function initRegisterTable() {

        if ($.fn.DataTable.isDataTable('#event-register-table')) {
            return;
        }
        if ($('#event-register-table').length > 0) {

            const eventId = $('#event-register-table').data('event-id');
            const table = $('#event-register-table').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: `/api/event/pendaftar/${eventId}`,
                    type: 'GET',

                    data: function (d) {
                        d.search_event = $('#search-event-register').val();
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
                    info: "_START_ - _END_ of _TOTAL_ event-register",
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

                            return `
                                <div class="d-flex justify-content-center align-items-center">

                                    <div class="dropdown table-action">

                                        <a href="#"
                                        class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                        data-bs-toggle="dropdown">

                                            <i class="ti ti-dots-vertical"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">

                                            ${(
                                                    (
                                                        row.payment_method === 'tunai' &&
                                                        row.status === 'unpaid'
                                                    ) ||
                                                    (
                                                        row.payment_method === 'transfer' &&
                                                        row.status === 'menunggu-approval'
                                                    )
                                                ) ? `

                                                    <a class="dropdown-item text-success btn-approval-payment"
                                                        href="javascript:void(0);"
                                                        data-id="${row.id}"
                                                        data-racer="${row.racer?.full_name ?? '-'}"
                                                        data-team="${row.team_name ?? row.user?.team_name ?? '-'}"
                                                        data-total="${row.total_price ?? 0}"
                                                        data-method="${row.payment_method ?? '-'}"
                                                        data-status="${row.status ?? 'menunggu-pembayaran'}"
                                                        data-proof="${row.payment_proof_url ?? ''}"
                                                        data-user-phone="${row.user?.phone_number ?? '-'}"
                                                        data-user-name="${row.user?.name ?? '-'}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal_approval_payment">

                                                            <i class="ti ti-credit-card"></i>
                                                            Approval Pembayaran
                                                    </a>

                                                ` : ''}

                                                ${(
                                                    (
                                                        row.payment_method === 'tunai' &&
                                                        row.status === 'unpaid'
                                                    ) ||
                                                    (
                                                        row.payment_method === 'transfer' &&
                                                        (
                                                            row.status === 'menunggu-approval' ||
                                                            row.status === 'menunggu-pembayaran' ||
                                                            row.status === 'rejected'
                                                        )
                                                    ) && row.race_status !== 'rejected'
                                                ) ? `

                                                    <a class="dropdown-item text-info btn-open-approval-race"
                                                        href="javascript:void(0);"
                                                        data-id="${row.id}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal_approval_race_status">

                                                            <i class="ti ti-clipboard-check"></i>
                                                            Ubah Status Balap
                                                    </a>

                                                ` : ''}

                                            <a class="dropdown-item text-warning btn-open-fine-register"
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_change_fine_status"
                                                data-id="${row.id}"
                                                data-is-fined="${row.is_fined}">

                                                <i class="ti ti-alert-circle"></i>
                                                Ubah Status Denda
                                            </a>

                                            ${( row.status === 'paid' ) ? `

                                                    <a class="dropdown-item text-success btn-open-invoice-register"
                                                        href="javascript:void(0);"
                                                        data-id="${row.id}"
                                                        data-classes="${encodeURIComponent(JSON.stringify(row.classes))}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modal_invoice_race">

                                                            <i class="ti ti-printer"></i>
                                                            Cetak Kwitansi
                                                    </a>

                                                ` : ''}

                                            <a class="dropdown-item text-danger btn-open-delete-register"
                                            href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete_register"
                                            data-id="${row.id}">

                                                <i class="ti ti-trash"></i>
                                                Hapus
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            `;
                        }
                    },
                    {
                        data: 'registration_number',

                        render: function (data, type, row) {

                            return `
                                <div class="d-flex flex-column">

                                    <h6 class="fs-14 fw-medium mb-0">
                                        ${row.registration_number ?? '-'}
                                    </h6>

                                </div>
                            `;
                        }
                    },
                    // NAMA PEMBALAP
                    {
                        data: 'racer.full_name', // Menentukan pencarian & sorting default berdasarkan nama lengkap

                        render: function (data, type, row) {
                            // Fallback tanggal lahir jika ingin diformat di sisi client (opsional)
                            let birthDateFormatted = row.racer?.birth_date ?? '-';
                            if (row.racer?.birth_date) {
                                birthDateFormatted = new Date(row.racer.birth_date).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }

                            return `
                                <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                    <a href="javascript:void(0);"
                                    class="d-flex flex-column fw-medium text-dark text-decoration-none btn-racer-detail event-hover"

                                    data-bs-toggle="modal"
                                    data-bs-target="#racer-detail"

                                    data-nik="${row.racer?.nik ?? '-'}"
                                    data-full_name="${row.racer?.full_name ?? '-'}"
                                    data-short_name="${row.racer?.short_name ?? '-'}"
                                    data-phone_number="${row.racer?.phone_number ?? '-'}"
                                    data-description="${row.racer?.description ?? '-'}"

                                    data-birth_location="${row.racer?.birth_location ?? '-'}"
                                    data-birth_date="${birthDateFormatted}"
                                    data-hometown="${row.racer?.hometown ?? '-'}"
                                    data-photo="${row.racer?.photo ?? '-'}"
                                    data-kta="${row.racer?.kta ?? '-'}"
                                    data-kis="${row.racer?.kis ?? '-'}"

                                    data-user-name="${row.name_register ?? '-'}"
                                    data-user-phone_number="${row.phone_number_register ?? ''}">

                                        <span class="event-title">
                                            ${row.racer?.full_name ?? '-'}
                                        </span>

                                        <small class="text-muted">
                                            ${row.racer?.short_name ?? '-'}
                                        </small>
                                    </a>
                                </h6>
                            `;
                        }
                    },

                    // TIM
                    {
                        data: 'team_name',

                        render: function (data, type, row) {

                            return `
                                <h6 class="fs-14 fw-medium mb-0">
                                    ${row.team_name ?? row.user?.team_name ?? '-'}
                                </h6>
                            `;
                        }
                    },

                    // KONTAK
                    {
                        data: 'racer.phone_number',

                        render: function (data, type, row) {

                            return `
                                <div class="d-flex flex-column">

                                    <span class="fw-medium text-dark">
                                        ${row.phone_number_register ?? '-'}
                                    </span>

                                    <small class="text-dark">
                                        ${row.name_register ?? '-'}
                                    </small>

                                </div>
                            `;
                        }
                    },

                    // RACE STATUS
                    {
                        data: 'race_status',

                        render: function (data, type, row) {

                            let badgeClass = 'bg-warning';
                            let label = 'Pending';

                            // APPROVED
                            if (row.race_status === 'approved') {
                                badgeClass = 'bg-success';
                                label = 'Disetujui';
                            }

                            // REJECTED
                            else if (row.race_status === 'rejected') {
                                badgeClass = 'bg-danger';
                                label = 'Ditolak';
                            }

                            // PENDING
                            else if (row.race_status === 'pending') {
                                badgeClass = 'bg-warning';
                                label = 'Pending';
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

                    // TIM
                    {
                        data: 'is_fined',

                       render: function (data, type, row) {

                            return row.is_fined == 1
                                ? `<span class="badge bg-danger">Ya</span>`
                                : `<span class="badge bg-secondary">Tidak</span>`;
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

                    // BUKTI PEMBAYARAN
                    {
                        data: 'payment_proof_url',

                        orderable: false,
                        searchable: false,

                        render: function (data, type, row) {

                            // BELUM ADA BUKTI
                            if (!row.payment_proof) {

                                return `
                                    -
                                `;
                            }

                            // ADA BUKTI
                            return `
                                <a href="${row.payment_proof_url}"
                                    target="_blank"
                                    class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">

                                    <i class="ti ti-photo"></i>

                                    Lihat Bukti

                                </a>
                            `;
                        }
                    },

                    // CREATED
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

                ]

            });

            // SEARCH (like users)
            $(document).on('keyup', '#search-event-register', function () {
                console.log('typing:', $(this).val()); // debug
                table.ajax.reload();
            });

            // REFRESH (optional)
            $('#refresh-event-register').on('click', function () {
                table.ajax.reload(null, false);
            });

        }
    }

    function initRaceTable() {

        if ($.fn.DataTable.isDataTable('#event-race-table')) {
            return;
        }

        if ($('#event-race-table').length > 0) {

            const eventId = $('#event-race-table').data('event-id');

            const table = $('#event-race-table').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: `/api/registration-classes/${eventId}`,
                    type: 'GET',

                    data: function (d) {
                        d.search_event = $('#search-event-race').val();
                    }
                },

                bFilter: false,
                bInfo: false,
                ordering: true,
                autoWidth: false,

                language: {
                    search: ' ',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search racer",
                    info: "_START_ - _END_ of _TOTAL_ data",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        next: '<i class="ti ti-chevron-right"></i>',
                        previous: '<i class="ti ti-chevron-left"></i>'
                    },
                },

                initComplete: () => {

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

                            return `
                                <div class="d-flex justify-content-center align-items-center">

                                    <div class="dropdown table-action">

                                        <a href="#"
                                        class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                        data-bs-toggle="dropdown">

                                            <i class="ti ti-dots-vertical"></i>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-end">

                                            <a class="dropdown-item text-warning btn-open-edit-race"
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_edit_race"
                                                data-id="${row.id}"
                                                data-racer-number="${row.registration?.racer_number}"
                                                data-vehicle="${row.vehicle}"
                                                data-rangka-number="${row.rangka_number}"
                                                data-vehicle-number="${row.vehicle_number}"
                                                >

                                                <i class="ti ti-clipboard-check"></i>
                                                Ubah Data
                                            </a>

                                            <a class="dropdown-item text-danger btn-open-delete-register"
                                            href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_delete_race"
                                            data-id="${row.id}">

                                                <i class="ti ti-trash"></i>
                                                Hapus
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            `;
                        }
                    },
                    // NO KWITANSI
                    {
                        data: 'invoice_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-semibold text-dark">
                                    ${row.invoice_number ?? '-'}
                                </span>
                            `;
                        }
                    },

                    {
                        data: 'racer.full_name', // Menentukan pencarian & sorting default berdasarkan nama lengkap

                        render: function (data, type, row) {
                            // Fallback tanggal lahir jika ingin diformat di sisi client (opsional)
                            let birthDateFormatted = row.racer?.birth_date ?? '-';
                            if (row.racer?.birth_date) {
                                birthDateFormatted = new Date(row.racer.birth_date).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }

                            return `
                                <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                    <a href="javascript:void(0);"
                                    class="d-flex flex-column fw-medium text-dark text-decoration-none btn-racer-detail event-hover"

                                    data-bs-toggle="modal"
                                    data-bs-target="#racer-detail"

                                    data-nik="${row.racer?.nik ?? '-'}"
                                    data-full_name="${row.racer?.full_name ?? '-'}"
                                    data-short_name="${row.racer?.short_name ?? '-'}"
                                    data-phone_number="${row.racer?.phone_number ?? '-'}"
                                    data-description="${row.racer?.description ?? '-'}"

                                    data-birth_location="${row.racer?.birth_location ?? '-'}"
                                    data-birth_date="${birthDateFormatted}"
                                    data-hometown="${row.racer?.hometown ?? '-'}"
                                    data-photo="${row.racer?.photo ?? '-'}"
                                    data-kta="${row.racer?.kta ?? '-'}"
                                    data-kis="${row.racer?.kis ?? '-'}"

                                    data-user-name="${row.name_register ?? '-'}"
                                    data-user-phone_number="${row.phone_number_register ?? ''}">

                                        <span class="event-title">
                                            ${row.racer?.full_name ?? '-'}
                                        </span>

                                        <small class="text-muted">
                                            ${row.racer?.short_name ?? '-'}
                                        </small>
                                    </a>
                                </h6>
                            `;
                        }
                    },

                    // NIK
                    {
                        data: 'racer.nik',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.racer?.nik ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // NO START
                    {
                        data: 'registration.racer_number',

                        render: function (data, type, row) {
                            // Ambil data nomor start dari objek registration
                            const racerNumber = row.registration?.racer_number;

                            // Jika data nomor start kosong, null, atau strip, tampilkan tanda strip biasa tanpa badge
                            if (!racerNumber || racerNumber === '-') {
                                return `<span class="text-muted">-</span>`;
                            }

                            // Jika nomor duplikat (flag true dari backend)
                            if (row.is_racer_number_duplicate) {
                                return `
                                    <span class="badge bg-danger text-white d-inline-flex align-items-center gap-1"
                                        style="font-size: 13px;"
                                        title="Nomor ini juga digunakan oleh pembalap lain!">
                                        <i class="ti ti-alert-triangle"></i> ${racerNumber} (Duplikat)
                                    </span>
                                `;
                            }

                            // Jika nomor aman / unik
                            return `
                                <span class="badge bg-success text-white d-inline-flex align-items-center gap-1"
                                    style="font-size: 13px;"
                                    title="Nomor start aman & unik">
                                    <i class="ti ti-check"></i> ${racerNumber}
                                </span>
                            `;
                        }
                    },

                    // TEAM
                    {
                        data: 'registration.team_name',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.registration?.team_name ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // KOTA
                    {
                        data: 'racer.hometown',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.racer?.hometown ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // TEAM
                    {
                        data: 'event_class.name',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.event_class?.name ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // KELAS KENDARAAN
                    {
                        data: 'vehicle',

                        render: function (data, type, row) {

                            return `
                                <div class="d-flex flex-column">

                                    <span class="fw-medium text-dark">
                                        ${row.vehicle ?? '-'}
                                    </span>
                                </div>
                            `;
                        }
                    },

                    // NO RANGKA
                    {
                        data: 'rangka_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.rangka_number ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // NO MESIN
                    {
                        data: 'mesin_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                   ${row.vehicle_number ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // TIM
                    {
                        data: 'is_photo',

                       render: function (data, type, row) {

                            return row.racer?.is_photo == 1
                                ? `<span class="badge bg-success">Ada</span>`
                                : `<span class="badge bg-danger">Tidak</span>`;
                        }
                    },

                    // TIM
                    {
                        data: 'is_kis',

                       render: function (data, type, row) {

                            return row.racer?.is_kis == 1
                                ? `<span class="badge bg-success">Ada</span>`
                                : `<span class="badge bg-danger">Tidak</span>`;
                        }
                    },

                    // TIM
                    {
                        data: 'kta',

                       render: function (data, type, row) {

                            return row.racer?.is_kta == 1
                                ? `<span class="badge bg-success">Ada</span>`
                                : `<span class="badge bg-danger">Tidak</span>`;
                        }
                    },

                ]

            });

            // SEARCH
            $(document).on('keyup', '#search-event-race', function () {

                table.ajax.reload();

            });

            // REFRESH
            $('#refresh-race-register').on('click', function () {

                table.ajax.reload(null, false);

            });

        }
    }

    function initRaceOriginalTable() {

        if ($.fn.DataTable.isDataTable('#event-race-original-table')) {
            return;
        }

        if ($('#event-race-original-table').length > 0) {

            const eventId = $('#event-race-original-table').data('event-id');

            const table = $('#event-race-original-table').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: `/api/registration-original/${eventId}`,
                    type: 'GET',

                    data: function (d) {
                        d.search_event = $('#search-event-race-original').val();
                    }
                },

                bFilter: false,
                bInfo: false,
                ordering: true,
                autoWidth: false,

                language: {
                    search: ' ',
                    sLengthMenu: '_MENU_',
                    searchPlaceholder: "Search racer",
                    info: "_START_ - _END_ of _TOTAL_ data",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        next: '<i class="ti ti-chevron-right"></i>',
                        previous: '<i class="ti ti-chevron-left"></i>'
                    },
                },

                initComplete: () => {

                    $('.dataTables_paginate').appendTo('.datatable-paginate');
                    $('.dataTables_length').appendTo('.datatable-length');

                },

                columns: [

                    // NO KWITANSI
                    {
                        data: 'invoice_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-semibold text-dark">
                                    ${row.invoice_number ?? '-'}
                                </span>
                            `;
                        }
                    },

                    {
                        data: 'racer.full_name', // Menentukan pencarian & sorting default berdasarkan nama lengkap

                        render: function (data, type, row) {
                            // Fallback tanggal lahir jika ingin diformat di sisi client (opsional)
                            let birthDateFormatted = row.racer?.birth_date ?? '-';
                            if (row.racer?.birth_date) {
                                birthDateFormatted = new Date(row.racer.birth_date).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric'
                                });
                            }

                            return `
                                <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">
                                    <a href="javascript:void(0);"
                                    class="d-flex flex-column fw-medium text-dark text-decoration-none btn-racer-detail event-hover"

                                    data-bs-toggle="modal"
                                    data-bs-target="#racer-detail"

                                    data-nik="${row.racer?.nik ?? '-'}"
                                    data-full_name="${row.racer?.full_name ?? '-'}"
                                    data-short_name="${row.racer?.short_name ?? '-'}"
                                    data-phone_number="${row.racer?.phone_number ?? '-'}"
                                    data-description="${row.racer?.description ?? '-'}"

                                    data-birth_location="${row.racer?.birth_location ?? '-'}"
                                    data-birth_date="${birthDateFormatted}"
                                    data-hometown="${row.racer?.hometown ?? '-'}"
                                    data-photo="${row.racer?.photo ?? '-'}"
                                    data-kta="${row.racer?.kta ?? '-'}"
                                    data-kis="${row.racer?.kis ?? '-'}"

                                    data-user-name="${row.name_register ?? '-'}"
                                    data-user-phone_number="${row.phone_number_register ?? ''}">

                                        <span class="event-title">
                                            ${row.racer?.full_name ?? '-'}
                                        </span>

                                        <small class="text-muted">
                                            ${row.racer?.short_name ?? '-'}
                                        </small>
                                    </a>
                                </h6>
                            `;
                        }
                    },

                    // NIK
                    {
                        data: 'racer.nik',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.racer?.nik ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // NO START
                    {
                        data: 'registration.racer_number',

                        render: function (data, type, row) {
                            // Ambil data nomor start dari objek registration
                            const racerNumber = row.racer_number;

                            // Jika data nomor start kosong, null, atau strip, tampilkan tanda strip biasa tanpa badge
                            if (!racerNumber || racerNumber === '-') {
                                return `<span class="text-muted">-</span>`;
                            }

                            // Jika nomor duplikat (flag true dari backend)
                            if (row.is_racer_number_duplicate) {
                                return `
                                    <span class="badge bg-danger text-white d-inline-flex align-items-center gap-1"
                                        style="font-size: 13px;"
                                        title="Nomor ini juga digunakan oleh pembalap lain!">
                                        <i class="ti ti-alert-triangle"></i> ${racerNumber} (Duplikat)
                                    </span>
                                `;
                            }

                            // Jika nomor aman / unik
                            return `
                                <span class="badge bg-success text-white d-inline-flex align-items-center gap-1"
                                    style="font-size: 13px;"
                                    title="Nomor start aman & unik">
                                    <i class="ti ti-check"></i> ${racerNumber}
                                </span>
                            `;
                        }
                    },

                    // TEAM
                    {
                        data: 'team_name',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.team_name ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // KOTA
                    {
                        data: 'racer.hometown',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.racer?.hometown ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // TEAM
                    {
                        data: 'event_class.name',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.event_class?.name ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // KELAS KENDARAAN
                    {
                        data: 'vehicle',

                        render: function (data, type, row) {

                            return `
                                <div class="d-flex flex-column">

                                    <span class="fw-medium text-dark">
                                        ${row.vehicle ?? '-'}
                                    </span>
                                </div>
                            `;
                        }
                    },

                    // NO RANGKA
                    {
                        data: 'rangka_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                    ${row.rangka_number ?? '-'}
                                </span>
                            `;
                        }
                    },

                    // NO MESIN
                    {
                        data: 'mesin_number',

                        render: function (data, type, row) {

                            return `
                                <span class="fw-medium text-dark">
                                   ${row.vehicle_number ?? '-'}
                                </span>
                            `;
                        }
                    },
                ]

            });

            // SEARCH
            $(document).on('keyup', '#search-event-race-original', function () {

                table.ajax.reload();

            });

            // REFRESH
            $('#refresh-race-original-register').on('click', function () {

                table.ajax.reload(null, false);

            });

        }
    }

    $(document).ready(function () {

        const hash = window.location.hash;

        // FIRST LOAD
        if (hash === '#pendaftar') {

            $('.nav-tabs a[href="#pendaftar"]').tab('show');

            initRegisterTable();

        } else if (hash === '#race') {

            $('.nav-tabs a[href="#race"]').tab('show');

            initRaceTable();

        } else if (hash === '#race-original') {

            $('.nav-tabs a[href="#race-original"]').tab('show');

            initRaceOriginalTable();

        } else {

            initClassTable();
        }

        // TAB CHANGE
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {

            const target = $(e.target).attr('href');

            history.replaceState(null, null, target);

            // KELAS
            if (target === '#kelas') {

                setTimeout(() => {
                    initClassTable();
                }, 100);
            }

            // PENDAFTAR
            if (target === '#pendaftar') {

                setTimeout(() => {
                    initRegisterTable();
                }, 100);
            }

            // RACE
            if (target === '#race') {

                setTimeout(() => {
                    initRaceTable();
                }, 100);
            }

            if (target === '#race-original') {

                setTimeout(() => {
                    initRaceOriginalTable();
                }, 100);
            }

        });

    });

    $(document).on('click', '.btn-add-class', function () {

        $('#event_id').val($(this).data('event-id'));

    });

    $('#form-add-class').on('submit', function (e) {

        e.preventDefault();

        const form = $(this);
        const button = form.find('.btn-submit');

        $.ajax({

            url: '/api/event-classes',
            type: 'POST',

            data: {
                event_id: $('input[name="event_id"]').val(),
                name: form.find('[name="name"]').val(),
                price: form.find('[name="price"]').val(),
                price_fine: form.find('[name="price_fine"]').val(),
                quota: form.find('[name="quota"]').val(),
                notes: form.find('[name="notes"]').val(),
            },

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {

                button.prop('disabled', true);
                button.text('Saving...');

            },

            success: function (response) {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                });

                form[0].reset();

                $('.offcanvas').offcanvas('hide');

                $('#event-class-list-table').DataTable()
                    .ajax.reload(null, false);

            },

            error: function (xhr) {

                let message = 'Terjadi kesalahan';

                if (xhr.status === 422) {

                    const errors = xhr.responseJSON.errors;

                    message = Object.values(errors)
                        .map(err => err[0])
                        .join('<br>');

                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: message
                });

            },

            complete: function () {

                button.prop('disabled', false);
                button.text('Create');

            }

        });

    });

    /**
     * OPEN EDIT CLASS
     */
    $(document).on('click', '.btn-edit-class', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/api/event-classes/${id}/show`,
            type: 'GET',

            success: function (res) {

                const data = res.data;

                $('#edit-id').val(data.id);
                $('#edit-event-id').val(data.event_id);

                $('#edit-name-class').val(data.name);
                $('#edit-price').val(data.price);
                $('#edit-price_fine').val(data.price);
                $('#edit-quota').val(data.quota);
                $('#edit-notes').val(data.notes);

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak bisa mengambil data kelas'
                });

            }
        });

    });


    /**
     * UPDATE CLASS
     */
    $('#form-edit-class').on('submit', function (e) {

        e.preventDefault();

        const id = $('#edit-id').val();

        $.ajax({

            url: `/api/event-classes/${id}`,
            type: 'POST',

            data: $('#form-edit-class').serialize() + '&_method=PUT',

            success: function (res) {

                $('#offcanvas_edit_class').offcanvas('hide');

                $('#event-class-list-table').DataTable()
                    .ajax.reload(null, false);


                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Kelas berhasil diupdate',
                    timer: 2000,
                    showConfirmButton: false,
                    showCloseButton: true
                });

            },

            error: function (xhr) {

                let message = 'Terjadi kesalahan';

                if (xhr.status === 422) {

                    if (xhr.responseJSON.errors) {

                        message = Object.values(xhr.responseJSON.errors)
                            .map(err => err[0])
                            .join('<br>');

                    } else {

                        message = xhr.responseJSON.message;

                    }
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: message,
                    timer: 2500,
                    showConfirmButton: false,
                    showCloseButton: true
                });

            }

        });

    });

    $(document).on('click', '.btn-racer-detail', function () {
        // 1. Data teks biasa (Ini tetap menggunakan .text)
        $('#detail_racer_nik').text($(this).data('nik'));
        $('#detail_racer_full_name').text($(this).data('full_name'));
        $('#detail_racer_short_name').text($(this).data('short_name'));
        $('#detail_racer_phone_number').text($(this).data('phone_number'));
        $('#detail_racer_description').text($(this).data('description'));
        $('#detail_racer_birth_location').text($(this).data('birth_location'));
        $('#detail_racer_birth_date').text($(this).data('birth_date'));
        $('#detail_racer_hometown').text($(this).data('hometown'));
        $('#detail_cp_name').text($(this).data('user-name'));
        $('#detail_cp_phone').text($(this).data('user-phone_number'));

        // 2. Data Gambar/Tombol (Gunakan .attr('href', ...) saja, JANGAN pakai .text)
        function bindImageButton(elementId, url) {
            const $el = $(elementId);
            if (url && url !== '-' && url !== 'null' && url !== 'undefined') {
                // Mengubah link tujuan dan mengaktifkan tombol
                $el.attr('href', url).removeClass('disabled').css('pointer-events', 'auto');
            } else {
                // Jika link kosong, kunci tombolnya
                $el.attr('href', 'javascript:void(0);').addClass('disabled').css('pointer-events', 'none');
            }
        }

        // Eksekusi pemetaan link ke tombol HTML
        bindImageButton('#detail_racer_kta', $(this).data('kta'));
        bindImageButton('#detail_racer_kis', $(this).data('kis'));
        bindImageButton('#detail_racer_photo', $(this).data('photo'));

    // WhatsApp Contact
    const phone = $(this).data('user-phone_number');
    $('#detail_cp_whatsapp').attr('href', `https://wa.me/${phone}`);
});

    $(document).on('click', '.btn-open-delete-class', function () {
        deleteEventId = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete-class', function () {

        if (!deleteEventId) return;

        $.ajax({
            url: `/api/event-classes/${deleteEventId}`,
            type: 'DELETE',

            success: function (res) {

                $('#event-class-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Kelas berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteEventId = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus kelas'
                });

            }

        });
    });

    $(document).on('click', '.btn-open-delete-register', function () {
        deleteEventId = $(this).data('id');
    });

    $(document).on('click', '.btn-open-fine-register', function () {
        changeFinedEventId = $(this).data('id');
    });

    $(document).on('click', '.btn-open-edit-race', function () {
        // Mengambil ID utama
        $('#registration_class_id').val($(this).data('id'));

        // Mengambil detail data langsung dari element tombol (this)
        $('#edit_racer_number').val($(this).data('racer-number'));
        $('#edit_vehicle').val($(this).data('vehicle'));
        $('#edit_rangka_number').val($(this).data('rangka-number'));
        $('#edit_vehicle_number').val($(this).data('vehicle-number'));
    });

     $(document).on('click', '.btn-open-approval-race', function () {
        id = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete-register', function () {

        if (!deleteEventId) return;

        $.ajax({
            url: `/api/registration/delete/${deleteEventId}`,
            type: 'DELETE',

            success: function (res) {

                $('#event-race-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Registrasi berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteEventId = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus registrasi'
                });

            }

        });
    });

    $(document).on('click', '.btn-confirm-change-fined', function () {

        if (!changeFinedEventId) return;

        $.ajax({
            url: `/api/registration/change-fine/${changeFinedEventId}`,
            type: 'PUT',

            success: function (res) {
                $('#modal_change_fine_status').modal('hide');

                $('#event-register-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Status Denda berhasil diubah',
                    timer: 2000,
                    showConfirmButton: false
                });

                changeFinedEventId = null;

            },

            error: function () {
                $('#modal_change_fine_status').modal('hide');

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa mengubah status'
                });

            }

        });
    });

    $(document).on('click', '.btn-approval-payment', function () {

        const id = $(this).data('id');
        const racer = $(this).data('racer');
        const team = $(this).data('team');
        const total = $(this).data('total');
        const method = $(this).data('method');
        const status = $(this).data('status');
        const proof = $(this).data('proof');
        const phone = $(this).data('user-phone');
        const user_name = $(this).data('user-name');

        $('#approval_registration_id').val(id);

        $('#approval_racer_name').val(racer);

        $('#approval_team_name').val(team);

        $('#approval_total_price').val(
            'Rp ' + Number(total).toLocaleString('id-ID')
        );

        $('#approval_payment_method').val(method);

            // $('#approval_payment_status').val(status);

        $('#approval_payment_proof').attr(
            'src',
            proof || '/assets/img/placeholder.jpg'
        );

        $('#approval_payment_proof_link').attr(
            'href',
            proof || '#'
        );

        $('#approval_user_name').text(user_name);

        $('#approval_user_phone').text(phone);

        $('#approval_user_phone_click').attr(
            'href',
            `https://wa.me/${phone}`
        );

    });

    $(document).on('click', '.btn-open-approval-race', function () {

        const id = $(this).data('id');

        $('#approval_race_id').val(id);

    });

    $('#form-approval-payment').on('submit', function (e) {

        e.preventDefault();

        const registrationId = $('#approval_registration_id').val();

        let formData = new FormData(this);

        $.ajax({

            url: `/api/event/approval-payment/${registrationId}`,
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

                $('#event-register-table').DataTable().ajax.reload(null, false);

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

    $('#form-approval-race-status').on('submit', function (e) {

        e.preventDefault();

        const registrationId = $('#approval_race_id').val();

        let formData = new FormData(this);

        $.ajax({

            url: `/api/event/approval-race/${registrationId}`,
            type: 'POST',
            data: formData,

            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

                $('#modal_approval_race_status').modal('hide');

                $('#event-register-table').DataTable().ajax.reload(null, false);

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

            // complete: function () {

            //     $('.btn-upload-payment').prop('disabled', false);

            // }

        });
    });

    $('#form-edit-race').on('submit', function (e) {

        e.preventDefault();

        const registrationClassId = $('#registration_class_id').val();

        let formData = new FormData(this);

        formData.append('_method', 'PUT');

        $.ajax({

            url: `/api/registration-classes/${registrationClassId}/edit`,
            type: 'POST',
            data: formData,

            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

                $('#modal_edit_race').modal('hide');

                $('#event-race-table').DataTable().ajax.reload(null, false);

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

            // complete: function () {

            //     $('.btn-upload-payment').prop('disabled', false);

            // }

        });
    });
});
