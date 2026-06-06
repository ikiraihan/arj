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

        if ($('#racer-list-table').length > 0) {

        const userId = $('#racer-list-table').data('user-id');
        const table = $('#racer-list-table').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: `/api/racers/${userId}`,
                type: 'GET',

                data: function (d) {
                    d.search_racer = $('#search-racers').val();
                }
            },

            bFilter: false,
            bInfo: false,
            ordering: true,
            autoWidth: true,

            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search racer",
                info: "_START_ - _END_ of _TOTAL_ racers",
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

                                        <a class="dropdown-item text-warning btn-edit-racer"
                                            href="javascript:void(0);"
                                            data-id="${row.id}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_edit">

                                                <i class="ti ti-edit text-blue"></i>
                                                Edit
                                        </a>

                                        <a class="dropdown-item text-danger btn-open-delete-racer"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_racer"
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
                               <div class="d-flex flex-column">

                                    <span class="fw-medium text-dark">
                                        ${row.full_name ?? '-'}
                                    </span>

                                    <small class="text-dark">
                                        ${row.short_name ?? '-'}
                                    </small>

                                </div>
                            `;
                        }
                },

                {
                    data: 'racer_number',

                    render: function (data, type, row) {
                        return `<span class="fw-medium text-dark">${row.racer_number ?? '-'}</span>`;
                    }
                },

                {
                    data: 'ttl',

                    render: function (data, type, row) {
                        return `<span class="fw-medium text-dark">${row.birth_location ?? '-'} , ${row.birth_date_formatted ?? '-'}</span>`;
                    }
                },

                {
                    data: 'hometown',

                    render: function (data, type, row) {
                        return `<span class="fw-medium text-dark">${row.hometown ?? '-'}</span>`;
                    }
                },

                {
                    data: 'phone_number',

                    render: function (data, type, row) {
                        return `<span class="fw-medium text-dark">${row.phone_number ?? '-'}</span>`;
                    }
                },

                // foto diri
                {
                    data: 'photo_url',

                    orderable: false,
                    searchable: false,

                    render: function (data, type, row) {

                        // BELUM ADA BUKTI
                        if (!row.photo_url) {

                            return `
                                -
                            `;
                        }

                        // ADA BUKTI
                        return `
                            <a href="${row.photo_url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">

                                <i class="ti ti-photo"></i>

                                Lihat Foto

                            </a>
                        `;
                    }
                },

                {
                    data: 'kis_url',

                    orderable: false,
                    searchable: false,

                    render: function (data, type, row) {

                        // BELUM ADA BUKTI
                        if (!row.kis_url) {

                            return `
                                -
                            `;
                        }

                        // ADA BUKTI
                        return `
                            <a href="${row.kis_url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">

                                <i class="ti ti-photo"></i>

                                Lihat Foto

                            </a>
                        `;
                    }
                },

                {
                    data: 'kta_url',

                    orderable: false,
                    searchable: false,

                    render: function (data, type, row) {

                        // BELUM ADA BUKTI
                        if (!row.kta_url) {

                            return `
                                -
                            `;
                        }

                        // ADA BUKTI
                        return `
                            <a href="${row.kta_url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">

                                <i class="ti ti-photo"></i>

                                Lihat Foto

                            </a>
                        `;
                    }
                },

                // STATUS
                // {
                //     data: 'status',

                //     render: function (data) {

                //         let color = data === 'active'
                //             ? 'success'
                //             : data === 'draft'
                //                 ? 'warning'
                //                 : 'danger';

                //         return `<span class="badge bg-${color}">${data}</span>`;
                //     }
                // },

            ]

        });

        // SEARCH (like users)
        $(document).on('keyup', '#search-racers', function () {
            console.log('typing:', $(this).val()); // debug
            table.ajax.reload();
        });

        // REFRESH (optional)
        $('#refresh-racers').on('click', function () {
            table.ajax.reload(null, false);
        });

    }

    // OPEN EDIT
    $(document).on('click', '.btn-edit-racer', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/api/racers/show/${id}`,
            type: 'GET',

            success: function (res) {

                const data = res.data;

                $('#edit_id').val(data.id);
                $('#edit_full_name').val(data.full_name);
                $('#edit_short_name').val(data.short_name);
                $('#edit_nik').val(data.nik);
                $('#edit_phone_number').val(data.phone_number);
                $('#edit_hometown').val(data.hometown);
                $('#edit_racer_number').val(data.racer_number);
                $('#edit_birth_location').val(data.birth_location);
                $('#edit_birth_date').val(data.birth_date_input);

                // reset preview dulu
                $('#preview_photo').html('');
                $('#preview_kis').html('');
                $('#preview_kta').html('');

                if (data.photo_url) {
                    $('#preview_photo').html(`
                        <div class="single-preview-box">
                            <img src="${data.photo_url}" alt="Photo">
                        </div>
                    `);
                }

                if (data.kis_url) {
                    $('#preview_kis').html(`
                        <div class="single-preview-box">
                            <img src="${data.kis_url}" alt="KIS">
                        </div>
                    `);
                }

                if (data.kta_url) {
                    $('#preview_kta').html(`
                        <div class="single-preview-box">
                            <img src="${data.kta_url}" alt="KTA">
                        </div>
                    `);
                }
            }
        });
    });

    // SUBMIT UPDATE RACER
    $('#form-edit-racer').on('submit', function (e) {

        e.preventDefault();

        const id = $('#edit_id').val();

        let formData = new FormData();

        formData.append('_method', 'PUT');

        formData.append('full_name', $('#edit_full_name').val());
        formData.append('short_name', $('#edit_short_name').val());
        formData.append('nik', $('#edit_nik').val());
        formData.append('phone_number', $('#edit_phone_number').val());
        formData.append('hometown', $('#edit_hometown').val());
        formData.append('racer_number', $('#edit_racer_number').val());
        formData.append('birth_location', $('#edit_birth_location').val());
        formData.append('birth_date', $('#edit_birth_date').val());

        // PHOTO
        if ($('#edit_photo')[0].files.length > 0) {
            formData.append('photo', $('#edit_photo')[0].files[0]);
        }

        // KIS
        if ($('#edit_kis')[0].files.length > 0) {
            formData.append('kis', $('#edit_kis')[0].files[0]);
        }

        // KTA
        if ($('#edit_kta')[0].files.length > 0) {
            formData.append('kta', $('#edit_kta')[0].files[0]);
        }

        // RESET ERROR
        $('#form-edit-racer .form-control').removeClass('is-invalid');
        $('#form-edit-racer .invalid-feedback').text('');

        $.ajax({

            url: `/api/racers/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {

                $('.btn-submit-edit')
                    .prop('disabled', true)
                    .html(`
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Loading...
                    `);
            },

            success: function (res) {

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message
                });

                // reload datatable
                $('#racer-list-table').DataTable().ajax.reload(null, false);

                // close offcanvas
                bootstrap.Offcanvas.getInstance(
                    document.getElementById('offcanvas_edit')
                ).hide();
            },

            error: function (xhr) {

                // VALIDATION ERROR
                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, value) {

                        $(`#edit_${key}`)
                            .addClass('is-invalid');

                        $(`.error-${key}`)
                            .text(value[0]);
                    });
                }

                // SERVER ERROR
                else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: xhr.responseJSON.message ?? 'Terjadi kesalahan'
                    });
                }
            },

            complete: function () {

                $('.btn-submit-edit')
                    .prop('disabled', false)
                    .html('Update');
            }
        });
    });

    $(document).on('click', '.btn-open-delete-racer', function () {
        deleteRacer = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete-racer', function () {

        if (!deleteRacer) return;

        $.ajax({
            url: `/api/racers/${deleteRacer}`,
            type: 'DELETE',

            success: function (res) {

                $('#racer-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Pembalap berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteRacer = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus Pembalap'
                });

            }

        });
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

});
