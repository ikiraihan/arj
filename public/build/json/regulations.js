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

    if ($('#regulation-list-table').length > 0) {

        const table = $('#regulation-list-table').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: '/api/regulations',
                type: 'GET',

                data: function (d) {
                    d.search_regulation = $('#search-regulation').val();
                }
            },

            bFilter: false,
            bInfo: false,
            ordering: true,
            autoWidth: true,

            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search regulation",
                info: "_START_ - _END_ of _TOTAL_ regulations",
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
                            <div class="d-flex justify-content-center">

                                <div class="dropdown table-action">

                                    <a href="#"
                                        class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                        data-bs-toggle="dropdown">

                                        <i class="ti ti-dots-vertical"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        <a class="dropdown-item text-warning btn-edit-regulation"
                                            href="javascript:void(0);"
                                            data-id="${row.id}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_edit">

                                            <i class="ti ti-edit"></i>
                                            Edit
                                        </a>

                                        <a class="dropdown-item text-danger btn-open-delete-regulation"
                                            href="#"
                                            data-id="${row.id}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#delete_regulation">

                                            <i class="ti ti-trash"></i>
                                            Hapus
                                        </a>

                                    </div>

                                </div>

                            </div>
                        `;
                    }
                },

                // TITLE
                {
                    data: 'title',
                    name: 'title',

                    render: function (data) {

                        return `
                            <span class="fw-semibold text-dark">
                                ${data ?? '-'}
                            </span>
                        `;
                    }
                },

                // DESCRIPTION
                // {
                //     data: 'description',
                //     name: 'description',

                //     render: function (data,row) {

                //         if (!data) {
                //             return '-';
                //         }

                //         return data.length > 100
                //             ? data.substring(0, 100) + '...'
                //             : data;
                //     }
                // },

                // FILE
                {
                    data: 'file_url',
                    orderable: false,
                    searchable: false,

                    render: function (data, type, row) {

                        if (!row.file_url) {
                            return '-';
                        }

                        return `
                            <a href="${row.file_url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">

                                <i class="ti ti-file-text"></i>
                                Lihat File
                            </a>
                        `;
                    }
                },

                // STATUS
                {
                    data: 'is_active',
                    name: 'is_active',

                    render: function (data, type, row) {

                        return row.is_active == true
                            ? '<span class="badge bg-success">Ya</span>'
                            : '<span class="badge bg-danger">Tidak</span>';
                    }
                }

            ]

        });

        // SEARCH
        $(document).on('keyup', '#search-regulation', function () {
            table.ajax.reload();
        });

        // REFRESH
        $('#refresh-regulation').on('click', function () {
            table.ajax.reload(null, false);
        });

    }

    // OPEN EDIT
    $(document).on('click', '.btn-edit-regulation', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/api/regulations/show/${id}`,
            type: 'GET',

            success: function (res) {

                const data = res.data;

                $('#edit_id').val(data.id);
                $('#edit_title').val(data.title);
                $('#edit_description').val(data.description);
                $('#edit_is_active').prop('checked', data.is_active == 1);

                // reset preview dulu
                $('#preview_file').html('');

                if (data.file_url) {
                    $('#preview_file').html(`
                        <div class="single-preview-box">
                            <img src="${data.file_url}" alt="File">
                        </div>
                    `);
                }
            }
        });
    });

    // SUBMIT UPDATE RACER
    $('#form-edit-regulation').on('submit', function (e) {

        e.preventDefault();

        const id = $('#edit_id').val();

        let formData = new FormData();

        formData.append('_method', 'PUT');

        formData.append('title', $('#edit_title').val());
        formData.append('description', $('#edit_description').val());
        formData.append('is_active', $('#edit_is_active').val());
        // FILE
        if ($('#edit_file')[0].files.length > 0) {
            formData.append('file', $('#edit_file')[0].files[0]);
        }

        // RESET ERROR
        $('#form-edit-regulation .form-control').removeClass('is-invalid');
        $('#form-edit-regulation .invalid-feedback').text('');

        $.ajax({

            url: `/api/regulations/${id}`,
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
                $('#regulation-list-table').DataTable().ajax.reload(null, false);

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

    $(document).on('click', '.btn-open-delete-regulation', function () {
        deleteRegulation = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete-regulation', function () {

        if (!deleteRegulation) return;

        $.ajax({
            url: `/api/regulations/${deleteRegulation}`,
            type: 'DELETE',

            success: function (res) {

                $('#regulation-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Regulasi berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteRegulation = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus Regulasi'
                });

            }

        });
    });

});
