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
    if ($('#manage-users-list').length > 0) {

        const table = $('#manage-users-list').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: '/api/users',
                type: 'GET',

                data: function (d) {

                    d.search_user = $('#search-users').val();

                }
            },

            bFilter: false,
            bInfo: false,
            ordering: true,
            autoWidth: true,

            language: {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                info: "_START_ - _END_ of _TOTAL_ items",
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
                {
                    data: null,
                    name: 'action',
                    orderable: false,
                    searchable: false,

                    render: function (data, type, row) {

                        return `
                            <div class="d-flex justify-content-center align-items-center">

                                <div class="dropdown table-action">

                                    <a href="#"
                                    class="action-icon btn btn-xs shadow btn-icon btn-outline-light"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                        <i class="ti ti-dots-vertical"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right">

                                        <a class="dropdown-item btn-edit-user"
                                        href="javascript:void(0);"
                                        data-id="${row.id}"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas_edit">

                                            <i class="ti ti-edit text-blue"></i>
                                            Edit
                                        </a>

                                        <a class="dropdown-item btn-open-delete"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="delete_contact#"
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
                    data: 'name',
                    name: 'name',

                    render: function (data, type, row) {

                        return `
                            <h6 class="d-flex align-items-center fs-14 fw-medium mb-0">

                                <a href="javascript:void(0);" class="d-flex flex-column">

                                    ${row.name}

                                    <span class="text-body fs-13 mt-1 d-inline-block fw-normal">

                                        ${row.role ?? '-'}

                                    </span>

                                </a>

                            </h6>
                        `;
                    }
                },

                {
                    data: 'phone_number',
                    name: 'phone_number',
                    defaultContent: '-'
                },

                {
                    data: 'email',
                    name: 'email'
                },

                {
                    data: 'created_at',
                    name: 'created_at',

                    render: function (data) {

                        const date = new Date(data);
                        return date.toLocaleString('id-ID', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                    }
                },
            ]
        });

        $('#search-users').on('keyup', function () {

            table.ajax.reload();

        });

        $('#refresh-users').on('click', function () {

            table.ajax.reload(null, false);

        });

    }

    $(document).on('click', '.btn-edit-user', function () {

        const id = $(this).data('id');

        let timerInterval;

        // 🔥 SweetAlert loading dulu (opsional tapi enak UX)
        // Swal.fire({
        //     title: 'Loading user data...',
        //     html: 'Please wait <b></b> ms',
        //     timer: 800,
        //     timerProgressBar: true,
        //     allowOutsideClick: false,

        //     didOpen: () => {

        //         Swal.showLoading();

        //         const b = Swal.getHtmlContainer().querySelector('b');

        //         timerInterval = setInterval(() => {
        //             b.textContent = Swal.getTimerLeft();
        //         }, 100);

        //     },

        //     willClose: () => {
        //         clearInterval(timerInterval);
        //     }

        // });

        // 🔥 ambil data user
        $.ajax({
            url: `/api/users/${id}`,
            type: 'GET',

            success: function (res) {

                const user = res.data;

                $('#edit-id').val(user.id);
                $('#edit-name').val(user.name);
                $('#edit-email').val(user.email);
                $('#edit-role').val(user.role);
                $('#edit-phone').val(user.phone_number);

                $('#edit-password').val('');
                $('#edit-password-confirm').val('');

                setTimeout(() => {
                    Swal.close();
                }, 300);

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak bisa mengambil data user'
                });

            }
        });

    });
    $('#form-edit-user').on('submit', function (e) {

        e.preventDefault();

        const id = $('#edit-id').val();

        $.ajax({
            url: `/api/users/${id}`,
            type: 'POST',

            data: $('#form-edit-user').serialize() + '&_method=PUT',

            success: function (res) {

                $('#offcanvas_edit').offcanvas('hide');
                $('#manage-users-list').DataTable().ajax.reload(null, false);

                let timerInterval;

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: res.message || 'User berhasil diupdate',
                    timer: 2000,
                    timerProgressBar: false,

                    showConfirmButton: false,

                    showCloseButton: true,   // 🔥 tombol silang
                    closeButtonHtml: '&times;', // optional (custom X)

                    // didOpen: () => {

                    //     const b = Swal.getHtmlContainer().querySelector('b');

                    //     timerInterval = setInterval(() => {
                    //         if (b) {
                    //             b.textContent = Swal.getTimerLeft();
                    //         }
                    //     }, 100);

                    // },

                    willClose: () => {
                        clearInterval(timerInterval);
                    }

                });

            },
            error: function (xhr) {

                let message = 'Terjadi kesalahan';

                if (xhr.status === 422) {
                    message = xhr.responseJSON?.message || 'Validasi gagal';
                }

                let timerInterval;

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: message
                    // + '<br>Closing in <b></b> ms'
                    ,
                    timer: 2500,
                    timerProgressBar: false,

                    showConfirmButton: false,

                    showCloseButton: true,   // 🔥 tombol silang
                    closeButtonHtml: '&times;',

                    // didOpen: () => {

                    //     const b = Swal.getHtmlContainer().querySelector('b');

                    //     timerInterval = setInterval(() => {
                    //         if (b) {
                    //             b.textContent = Swal.getTimerLeft();
                    //         }
                    //     }, 100);

                    // },

                    willClose: () => {
                        clearInterval(timerInterval);
                    }

                });
            }
        });
    });

    let deleteUserId = null;

    $(document).on('click', '.btn-open-delete', function () {
        deleteUserId = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete', function () {

        if (!deleteUserId) return;

        $.ajax({
            url: `/api/users/${deleteUserId}`,
            type: 'DELETE',

            success: function (res) {

                $('#manage-users-list').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'User berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteUserId = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus user'
                });

            }

        });

    });

});
