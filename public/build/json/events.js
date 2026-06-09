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
    //list event datatable
    if ($('#event-list-table').length > 0) {

        const table = $('#event-list-table').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                url: '/api/events',
                type: 'GET',

                data: function (d) {
                    d.search_event = $('#search-events').val();
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
                info: "_START_ - _END_ of _TOTAL_ events",
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

                                        <a class="dropdown-item text-info"
                                        href="/events/${row.id}">
                                            <i class="ti ti-eye text-blue"></i>
                                            Detail
                                        </a>

                                        <a class="dropdown-item text-warning btn-edit-event"
                                            href="javascript:void(0);"
                                            data-id="${row.id}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvas_edit">

                                                <i class="ti ti-edit text-blue"></i>
                                                Edit
                                        </a>

                                        <a class="dropdown-item text-danger btn-open-delete"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_contact"
                                        data-id="${row.id}">

                                            <i class="ti ti-trash"></i>
                                            Hapus
                                        </a>

                                         <a class="dropdown-item text-danger btn-open-delete-payment"
                                        href="#"
                                        data-bs-toggle="modal"
                                        data-bs-target="#delete_payment"
                                        data-id="${row.id}">

                                            <i class="ti ti-currency-dollar"></i>
                                            Hapus Bukti Pembayaran
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
                            <h6 class="d-flex flex-column fs-14 fw-medium mb-0">

                                ${row.name}

                                <span class="text-body fs-13 mt-1 fw-normal">
                                    ${row.category ?? 'Event'}
                                </span>

                            </h6>
                        `;
                    }
                },

                {
                    data: 'type_formatted',
                    render: function(data) {
                        return data.charAt(0).toUpperCase() + data.slice(1);
                    }
                },

                // VENUE + MAPS
                {
                    data: null,

                    render: function (data) {

                        return `
                            <div>
                                <div class="fw-medium">${data.venue}</div>

                                <a href="${data.link_maps}" target="_blank"
                                class="text-primary small d-inline-flex align-items-center">
                                    <i class="ti ti-map-pin me-1"></i> Lihat Maps
                                </a>
                            </div>
                        `;
                    }
                },

                {
                    data: 'registration_date_formatted'
                },

                {
                    data: 'event_date_formatted'
                },

                // REGISTERED
                {
                    data: 'registrants',

                    render: function (data) {
                        return `<span >${data} Pendaftar</span>`;
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
        $(document).on('keyup', '#search-events', function () {
            console.log('typing:', $(this).val()); // debug
            table.ajax.reload();
        });

        // REFRESH (optional)
        $('#refresh-events').on('click', function () {
            table.ajax.reload(null, false);
        });

    }

    let selectedFiles = [];

    /* =========================
    INIT SORTABLE
    ========================= */
   const previewContainer = document.getElementById('image-preview');

    if (previewContainer) {

        new Sortable(previewContainer, {
            animation: 150,
            onEnd: function () {

                const newOrder = [];

                document.querySelectorAll('.preview-box').forEach(el => {
                    const id = el.getAttribute('data-id');

                    const found = selectedFiles.find(f => f.id === id);
                    if (found) newOrder.push(found);
                });

                selectedFiles = newOrder;
            }
        });

    }

    /* =========================
    HANDLE FILE SELECT
    ========================= */
    document.getElementById('formFileMultiple').addEventListener('change', function (e) {

        const files = Array.from(e.target.files);

        files.forEach(file => {
            selectedFiles.push({
                id: crypto.randomUUID(),
                file: file
            });
        });

        renderPreview();
    });

    /* =========================
    RENDER PREVIEW
    ========================= */
    function renderPreview() {

        previewContainer.innerHTML = '';

        selectedFiles.forEach(item => {

            const reader = new FileReader();

            reader.onload = function (e) {

                const div = document.createElement('div');
                div.classList.add('preview-box');
                div.setAttribute('data-id', item.id);

                div.innerHTML = `
                    <img src="${e.target.result}">
                    <button type="button"
                            class="preview-remove"
                            data-id="${item.id}">
                        ×
                    </button>
                `;

                previewContainer.appendChild(div);
            };

            reader.readAsDataURL(item.file);
        });
    }

    /* =========================
    DELETE IMAGE
    ========================= */
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('preview-remove')) {

            const id = e.target.getAttribute('data-id');

            selectedFiles = selectedFiles.filter(item => item.id !== id);

            renderPreview();
        }
    });

    /* =========================
    SUBMIT FORM FIX
    ========================= */
    $('form').on('submit', function () {

        let input = document.getElementById('formFileMultiple');
        let dataTransfer = new DataTransfer();

        selectedFiles.forEach(item => {
            dataTransfer.items.add(item.file);
        });

        input.files = dataTransfer.files;
    });

    let contactIndex = parseInt(
            $('#contact-person-wrapper').data('contact-count')
        ) || 1;

    $('#btn-add-contact').on('click', function () {

        const html = `
            <div class="card border mb-3 contact-item">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Contact
                                </label>

                                <input type="text"
                                    name="contacts[${contactIndex}][name]"
                                    class="form-control"
                                    placeholder="Nama contact person">

                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="contacts[${contactIndex}][phone_number]"
                                    class="form-control"
                                    placeholder="08xxxxxxxxxx">

                            </div>
                        </div>

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
        `;

        $('#contact-person-wrapper').append(html);

        contactIndex++;
    });

    $(document).on('click', '.btn-remove-contact', function () {

        if ($('.contact-item').length > 1) {

            $(this).closest('.contact-item').remove();

        }

    });


    $(document).on('click', '.btn-edit-event', function () {

        const id = $(this).data('id');

        $.ajax({
            url: `/api/events/${id}`,
            type: 'GET',

            success: function (res) {

                const event = res.data;

                // BASIC
                $('#edit-id').val(event.id);
                $('#edit-name').val(event.name);
                $('#edit-description').val(event.description);

                $('#edit-type').val(event.type);

                $('#edit-venue').val(event.venue);
                $('#edit-link-maps').val(event.link_maps);

                $('#edit-provinsi').val(event.provinsi);
                $('#edit-kota').val(event.kota);

                // DATE
                $('#edit-registration-start-date').val(event.registration_start_date?.slice(0, 16));
                $('#edit-registration-end-date').val(event.registration_end_date?.slice(0, 16));

                $('#edit-start-date').val(event.start_date?.split('T')[0]);
                $('#edit-end-date').val(event.end_date?.split('T')[0]);

                // STATUS
                $('#edit-is-active').prop('checked', event.is_active);

                $('#edit-contact-person-wrapper').html('');

                if (event.contacts?.length > 0) {

                    event.contacts.forEach((contact, index) => {

                        $('#edit-contact-person-wrapper').append(`
                            <div class="card border mb-3 contact-item">
                                <div class="card-body">

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Nama Contact
                                                </label>

                                                <input type="text"
                                                    name="contacts[${index}][name]"
                                                    value="${contact.name ?? ''}"
                                                    class="form-control"
                                                    placeholder="Nama contact person">

                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <div class="mb-3">

                                                <label class="form-label">
                                                    Nomor Telepon
                                                </label>

                                                <input type="text"
                                                    name="contacts[${index}][phone_number]"
                                                    value="${contact.phone_number ?? ''}"
                                                    class="form-control"
                                                    placeholder="08xxxxxxxxxx">

                                            </div>
                                        </div>

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
                        `);

                    });

                } else {

                    $('#edit-contact-person-wrapper').html(`
                        <div class="card border mb-3 contact-item">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nama Contact
                                            </label>

                                            <input type="text"
                                                name="contacts[0][name]"
                                                class="form-control"
                                                placeholder="Nama contact person">

                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="mb-3">

                                            <label class="form-label">
                                                Nomor Telepon
                                            </label>

                                            <input type="text"
                                                name="contacts[0][phone_number]"
                                                class="form-control"
                                                placeholder="08xxxxxxxxxx">

                                        </div>
                                    </div>

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
                    `);
                }

                $('#edit-bank-name').val(event.payment_account?.bank_name ?? '');
                $('#edit-account-number').val(event.payment_account?.account_number ?? '');
                $('#edit-account-holder-name').val(event.payment_account?.account_holder_name ?? '');

                 $('#edit-link-documentation').val(event.link_documentation ?? '');
                 $('#edit-link-documentation-active').prop('checked', event.link_documentation_active ?? false);

                existingEditImages = event.photos ?? [];

                editSelectedFiles = [];

                renderEditImages();
            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak bisa mengambil data event'
                });

            }
        });

    });

    $('#form-edit-event').on('submit', function (e) {

        e.preventDefault();

        const id = $('#edit-id').val();

        let formData = new FormData(this);

        // HAPUS images dari formData biar tidak double
        formData.append('deleted_images', JSON.stringify(deletedImages));

        // pakai sumber utama: editInput.files
        let files = editInput.files;

        formData.delete('images[]');

        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        formData.append('_method', 'PUT');

        $.ajax({
            url: `/api/events/${id}`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                $('#offcanvas_edit').offcanvas('hide');
                $('#event-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: res.message || 'Event berhasil diupdate',
                    timer: 2000,
                    showConfirmButton: false,
                    showCloseButton: true
                });

            },

            error: function (xhr) {

                let message = 'Terjadi kesalahan';

                if (xhr.status === 422) {

                    const errors = xhr.responseJSON?.errors;

                    if (errors) {
                        message = '<ul class="text-start mb-0">';
                        Object.keys(errors).forEach(function (key) {
                            errors[key].forEach(function (err) {
                                message += `<li>${err}</li>`;
                            });
                        });
                        message += '</ul>';
                    }

                }

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: message,
                    timer: 3500,
                    showCloseButton: true
                });

            }
        });

    });
    /* =====================================
    EDIT EVENT IMAGES
    ===================================== */

    let editSelectedFiles = [];
    let existingEditImages = [];

    const editInput = document.getElementById('edit-images');
    const editPreview = document.getElementById('edit-image-preview');

    /* =====================================
    SELECT NEW IMAGE
    ===================================== */
    editInput.addEventListener('change', function (e) {

        const files = Array.from(e.target.files);

        files.forEach(file => {

            editSelectedFiles.push({
                id: crypto.randomUUID(),
                file: file
            });

        });

        renderEditImages();

    });

    /* =====================================
    RENDER IMAGES
    ===================================== */
    function renderEditImages() {

        editPreview.innerHTML = '';

        /* OLD IMAGES */
        existingEditImages.forEach(item => {

            const div = document.createElement('div');

            div.classList.add('preview-box');

            div.innerHTML = `
                <img src="/storage/${item.path}">

                <button type="button"
                    class="preview-remove remove-old-image"
                    data-id="${item.id}">
                    ×
                </button>
            `;

            editPreview.appendChild(div);

        });

        /* NEW IMAGES */
        editSelectedFiles.forEach(item => {

            const reader = new FileReader();

            reader.onload = function (e) {

                const div = document.createElement('div');

                div.classList.add('preview-box');

                div.innerHTML = `
                    <img src="${e.target.result}">

                    <button type="button"
                        class="preview-remove remove-new-image"
                        data-id="${item.id}">
                        ×
                    </button>
                `;

                editPreview.appendChild(div);

            };

            reader.readAsDataURL(item.file);

        });

    }

    let deletedImages = [];

    /* =====================================
    REMOVE OLD IMAGE
    ===================================== */
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('remove-old-image')) {

            const id = e.target.dataset.id;

            deletedImages.push(id); // ⬅️ WAJIB

            existingEditImages =
                existingEditImages.filter(img => img.id != id);

            renderEditImages();
        }

    });

    /* =====================================
    REMOVE NEW IMAGE
    ===================================== */
    document.addEventListener('click', function (e) {

        if (e.target.classList.contains('remove-new-image')) {

            const id = e.target.dataset.id;

            editSelectedFiles =
                editSelectedFiles.filter(file => file.id != id);

            renderEditImages();

        }

    });

    let editContactIndex = 0;

    $('#btn-add-edit-contact').on('click', function () {

        const html = `
            <div class="card border mb-3 contact-item">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <div class="mb-3">

                                <label class="form-label">
                                    Nama Contact
                                </label>

                                <input type="text"
                                    name="contacts[${editContactIndex}][name]"
                                    class="form-control"
                                    placeholder="Nama contact person">

                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="mb-3">

                                <label class="form-label">
                                    Nomor Telepon
                                </label>

                                <input type="text"
                                    name="contacts[${editContactIndex}][phone_number]"
                                    class="form-control"
                                    placeholder="08xxxxxxxxxx">

                            </div>
                        </div>

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
        `;

        $('#edit-contact-person-wrapper').append(html);

        editContactIndex++;
    });

    let deleteEventId = null;

    $(document).on('click', '.btn-open-delete', function () {
        deleteEventId = $(this).data('id');
    });

    $(document).on('click', '.btn-open-delete-payment', function () {
        deleteEventId = $(this).data('id');
    });

    $(document).on('click', '.btn-confirm-delete', function () {

        if (!deleteEventId) return;

        $.ajax({
            url: `/api/events/${deleteEventId}`,
            type: 'DELETE',

            success: function (res) {

                $('#event-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Event berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteEventId = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus event'
                });

            }

        });
    });

    $(document).on('click', '.btn-confirm-delete-payment', function () {

        if (!deleteEventId) return;

        $.ajax({
            url: `/api/events/${deleteEventId}/delete-payment-proof`,
            type: 'DELETE',

            success: function (res) {

                $('#event-list-table').DataTable().ajax.reload(null, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: res.message || 'Event berhasil dihapus',
                    timer: 2000,
                    showConfirmButton: false
                });

                deleteEventId = null;

            },

            error: function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Tidak bisa menghapus event'
                });

            }

        });
    });

});
