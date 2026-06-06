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

    // event grid
    $(document).ready(function () {

        let start = 0;
        let length = 8;

        let loading = false;
        let lastPage = false;

        function loadEvents() {

            if (loading || lastPage) return;

            loading = true;

            $("#event-loading").removeClass("d-none");

            $("#load-more-btn")
                .prop("disabled", true)
                .html(`
                    <i class="ti ti-loader-2 me-1"></i>
                    Loading...
                `);

            $.ajax({

                url: `/api/events?type=grid&start=${start}&length=${length}`,

                type: "GET",

                dataType: "json",

                success: function (res) {

                    if (!res.data || res.data.length === 0) {

                        lastPage = true;

                        $("#load-more-wrapper").html(`
                            <div class="text-center text-muted py-3">
                                Semua event sudah ditampilkan
                            </div>
                        `);

                        return;
                    }

                    let html = "";

                    res.data.forEach(event => {

                        html += `
                            <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 mb-4">

                                <div class="card border shadow h-100 overflow-hidden">

                                    <!-- PHOTO -->
                                    <div class="position-relative">

                                        <img src="${event.photos?.[0]?.url ?? '/default.png'}"
                                            class="w-100"
                                            style="
                                                height:220px;
                                                object-fit:cover;
                                            "
                                            alt="img">

                                    </div>

                                    <!-- BODY -->
                                    <div class="card-body p-3 d-flex flex-column">
                                        <!-- TITLE -->
                                        <div class="mb-2">
                                            <h5 class="mb-1">
                                                ${event.name}
                                            </h5>

                                        </div>

                                        <!-- DETAIL -->
                                        <div>

                                            <!-- EVENT DATE -->
                                            <p class="mb-1 d-flex align-items-start">
                                                <i class="ti ti-calendar-event me-2 mt-1 text-primary"></i>
                                                <span>
                                                    <small class="text-muted d-block">
                                                        Tanggal Event
                                                    </small>
                                                    <strong>
                                                        ${event.event_date_formatted ?? '-'}
                                                    </strong>
                                                </span>
                                            </p>
                                            <a href="${event.link_maps ?? '#'}"
                                                target="_blank"
                                                class="event-location-link text-muted text-decoration-none">

                                                <p class="mb-1 d-flex align-items-start">
                                                    <i class="ti ti-map-pin me-2 mt-1"></i>

                                                    <span>
                                                        <small class="text-muted d-block">
                                                            Lokasi
                                                        </small>

                                                        <strong>
                                                            ${event.location ?? '-'}
                                                        </strong>
                                                    </span>
                                                </p>

                                            </a>

                                            <!-- REGISTRATION -->
                                            <p class="mb-1 d-flex align-items-start">
                                                <i class="ti ti-clock-hour-4 me-2 mt-1 text-warning"></i>

                                                <span>
                                                    Pendaftaran dengan Harga Normal hingga<br>
                                                    <strong>
                                                        ${event.registration_end_date_formatted ?? '-'} WIB
                                                    </strong>
                                                </span>

                                            </p>
                                        </div>

                                        <!-- BUTTON -->
                                        <div class="mt-auto">

                                            <a href="/events/${event.id}/registration"
                                                class="btn btn-primary w-100">

                                                <i class="ti ti-ticket me-1"></i>
                                                Daftar Event

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        `;
                    });

                    $("#event-grid").append(html);

                    // update offset
                    start += length;

                    // jika data kurang dari length berarti sudah habis
                    if (res.data.length < length) {

                        lastPage = true;

                        $("#load-more-wrapper").html(`
                            <div class="text-center text-muted py-3">
                                Semua event sudah ditampilkan
                            </div>
                        `);
                    }
                },

                error: function () {

                    alert("Gagal memuat data event");

                },

                complete: function () {

                    loading = false;

                    $("#event-loading").addClass("d-none");

                    if (!lastPage) {

                        $("#load-more-btn")
                            .prop("disabled", false)
                            .html(`
                                <i class="ti ti-plus me-1"></i>
                                Tampilkan Lainnya
                            `);
                    }
                }
            });
        }

        // initial load
        loadEvents();

        // manual pagination
        $(document).on("click", "#load-more-btn", function () {
            loadEvents();
        });

    });

    $('.select2-racer').select2({

        placeholder: 'Pilih Pembalap',
        allowClear: true,

        ajax: {

            transport: function (params, success, failure) {

                const userId = $('.select2-racer').data('user-id');

                $.ajax({
                    url: `/api/racer/select/${userId}`,
                    type: 'GET',
                    data: params.data,
                    success: success,
                    error: failure
                });

            },

            delay: 250,

            data: function (params) {

                return {
                    q: params.term
                };

            },

            processResults: function (response) {

                let results = [];

                // 🔥 tambah option manual
                results.push({
                    id: 'new',
                    text: '+ Tambah Pembalap Baru'
                });

                // 🔥 data API
                $.each(response.data, function (i, item) {

                    results.push({
                        id: item.id,
                        text: item.name
                    });

                });

                return {
                    results: results
                };

            }

        }

    });

    /**
     * SHOW FORM
     */
    $(document).on('change', '#racer_id', function () {

        if ($(this).val() == 'new') {

            $('#new-racer-form').slideDown();

        } else {

            $('#new-racer-form').slideUp();

        }

    });

    $(document).on('change', '.class-checkbox', function () {

        const wrapper = $(this).closest('.border');

        if ($(this).is(':checked')) {

            wrapper.find('.class-detail-form')
                .stop(true, true)
                .slideDown(200);

        } else {

            wrapper.find('.class-detail-form')
                .stop(true, true)
                .slideUp(200);

        }

    });

    $('#form-register-event').on('submit', function (e) {

        e.preventDefault();

        const form = $(this);
        const button = form.find('button[type="submit"]');

        // hapus error lama
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();


        let formData = new FormData(this);

        $.ajax({

            url: '/api/event/registration',
            type: 'POST',

            data: formData,

            processData: false,
            contentType: false,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {

                button.prop('disabled', true);

                button.html(`
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Loading...
                `);

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

                setTimeout(() => {
                    window.location.href = '/event/payment';
                }, 1500);


                // optional reset
                // form[0].reset();

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    const errors = xhr.responseJSON.errors;

                    $.each(errors, function (field, messages) {

                        let fieldName = field.replace(/\.(\w+)$/g, '][$1]');

                        fieldName = fieldName.replace(/^([^.]+)\.(\d+)/, '$1[$2');

                        fieldName += ']';

                        let input = form.find(`[name="${fieldName}"]`);

                        if (!input.length) {
                            input = form.find(`[name="${field}"]`);
                        }

                        if (!input.length) {
                            input = form.find(`[name="${field}[]"]`);
                        }

                        input.addClass('is-invalid');

                        input.last().after(`
                            <div class="invalid-feedback d-block">
                                ${messages[0]}
                            </div>
                        `);

                    });

                } else {

                    if (xhr.status === 422) {

                        const errors = xhr.responseJSON.errors;

                        const firstMessage = Object.values(errors)[0][0];

                        Swal.fire({
                            icon: 'warning',
                            title: 'Validasi Gagal',
                            text: firstMessage,
                            showCloseButton: true
                        });

                        return;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan server',
                        timer: 3500,
                        showCloseButton: true
                    });

                }

            },

            complete: function () {

                button.prop('disabled', false);

                button.html(`
                    <i class="ti ti-send me-1"></i>
                    Submit
                `);

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
