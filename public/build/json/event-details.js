$(document).ready(function () {

    // setup ajax csrf (SAFE VERSION)
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        },
        headers: {
            'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
        }
    });

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
        return null;
    }

    // EVENT CARDS FETCH
    const cards = document.querySelectorAll('.event-card');

    console.log('EVENT CARD COUNT:', cards.length);

    let eventDataStore = {};

    cards.forEach(card => {

        const id = card.dataset.id;
        const body = card.querySelector('.event-body');

        if (!id || !body) return;

        fetch(`/api/events/${id}`)
            .then(res => res.json())
            .then(res => {

                const event = res.data;

                // simpan full desc
                eventDataStore[event.id] = event.description;

                const fullDesc = event.description ?? '-';
                const shortDesc = fullDesc.length > 200
                    ? fullDesc.substring(0, 200) + '...'
                    : fullDesc;

                body.innerHTML = `
                <div class="d-flex flex-column w-100">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-start w-100 mb-3">

                        <div class="flex-grow-1 pe-3">
                            <h4 class="mb-1">${event.name}</h4>

                            <p class="text-muted mb-0">
                                ${event.venue}, ${event.kota}, ${event.provinsi}
                            </p>
                        </div>

                        <div class="act-dropdown flex-shrink-0">

                            <a href="#"
                                data-bs-toggle="dropdown"
                                class="action-icon btn btn-icon btn-sm btn-outline-light shadow"
                                aria-expanded="false">

                                <i class="ti ti-dots-vertical"></i>

                            </a>

                            <div class="dropdown-menu dropdown-menu-right">

                                <a class="dropdown-item"
                                    href="javascript:void(0);"
                                    data-id="${event.id}"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvas_edit">

                                    <i class="ti ti-edit-circle me-1"></i>
                                    Edit

                                </a>

                                <a class="dropdown-item"
                                    href="javascript:void(0);"
                                    data-bs-toggle="modal"
                                    data-bs-target="#delete_contact">

                                    <i class="ti ti-trash me-1"></i>
                                    Delete

                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- SINGLE ACCORDION -->
                    <div class="accordion accordion-border-primary accordions-items-seperate w-100"
                        id="accordion-event-${event.id}">

                        <div class="accordion-item">

                            <h2 class="accordion-header" id="heading-${event.id}">

                                <button class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-${event.id}"
                                    aria-expanded="false">

                                    Detail Event

                                </button>

                            </h2>

                            <div id="collapse-${event.id}"
                                class="accordion-collapse collapse"
                                data-bs-parent="#accordion-event-${event.id}">

                                <div class="accordion-body p-0">

                                    <!-- EVENT DETAIL -->
                                    <ul class="list-group list-group-flush mb-3">

                                        <li class="list-group-item">
                                            <strong>Tipe:</strong><br>

                                            ${event.type
                                            ? event.type.charAt(0).toUpperCase() + event.type.slice(1)
                                            : '-'}
                                        </li>

                                        <li class="list-group-item">
                                            <strong>Lokasi:</strong><br>
                                            ${event.venue}, ${event.kota}, ${event.provinsi}
                                            <a href="${event.link_maps}"
                                                target="_blank"
                                                class="fw-bold text-primary d-inline-flex align-items-center gap-1">

                                                <i class="ti ti-map-pin"></i>
                                                Buka Maps

                                            </a>
                                        </li>

                                        <li class="list-group-item">
                                            <strong>Registrasi:</strong><br>

                                            ${event.registration_date_formatted ?? '-'}
                                        </li>

                                        <li class="list-group-item">
                                            <strong>Event Date:</strong><br>

                                            ${event.event_date_formatted ?? '-'}
                                        </li>

                                        <li class="list-group-item">

                                            <strong>Deskripsi:</strong><br>

                                            <span id="desc-${event.id}">
                                                ${shortDesc}
                                            </span>

                                            ${fullDesc.length > 200 ? `
                                                <a href="javascript:void(0);"
                                                    class="read-more text-primary ms-1"
                                                    data-id="${event.id}">
                                                    Lihat Selengkapnya
                                                </a>
                                            ` : ''}

                                        </li>

                                        <li class="list-group-item">
                                            <strong>Status:</strong><br>
                                            ${event.is_active ? 'Aktif' : 'Nonaktif'}
                                        </li>

                                    </ul>

                                    <!-- CONTACT -->
                                    <h6 class="px-3">Contact Person</h6>

                                    <ul class="list-group list-group-flush mb-3">

                                        ${(event.contacts || []).map(c => `
                                            <li class="list-group-item">
                                                ${c.name} - ${c.phone_number}
                                            </li>
                                        `).join('')}

                                    </ul>

                                    <!-- PAYMENT -->
                                    <h6 class="px-3">Payment</h6>

                                    <ul class="list-group list-group-flush mb-3">

                                        <li class="list-group-item">
                                            Bank: ${event.payment_account?.bank_name ?? '-'}
                                        </li>

                                        <li class="list-group-item">
                                            Rek: ${event.payment_account?.account_number ?? '-'}
                                        </li>

                                        <li class="list-group-item">
                                            A/N: ${event.payment_account?.account_holder_name ?? '-'}
                                        </li>

                                    </ul>

                                    <!-- PHOTOS -->
                                    <h6 class="px-3">Photos</h6>

                                    <div class="p-3 d-flex gap-2 flex-wrap">

                                        ${(event.photos || []).map(p => `
                                            <img src="/storage/${p.path}"
                                                style="
                                                    width:140px;
                                                    height:90px;
                                                    object-fit:cover;
                                                    border-radius:10px;
                                                ">
                                        `).join('')}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                `;
            })
            .catch(err => {
                console.error('EVENT LOAD ERROR:', err);
                body.innerHTML = `<span class="text-danger">Failed load</span>`;
            });

    });

    $(document).on('click', '.read-more', function (e) {

        e.preventDefault();
        e.stopPropagation();

        const button = $(this);

        const id = button.data('id');
        const span = $(`#desc-${id}`);
        const fullText = eventDataStore[id];

        if (!fullText) return;

        const expanded = button.hasClass('expanded');

        if (!expanded) {

            span.text(fullText);

            button
                .text('Tutup')
                .addClass('expanded');

        } else {

            span.text(fullText.substring(0, 200) + '...');

            button
                .text('Lihat Selengkapnya')
                .removeClass('expanded');
        }

    });

});
/**
 * OPEN TAB FROM URL HASH
 */
$(document).ready(function () {

    const hash = window.location.hash;

    if (hash) {

        const trigger = $(`a[href="${hash}"]`);

        if (trigger.length) {

            new bootstrap.Tab(trigger[0]).show();

        }

    }

});


/**
 * UPDATE URL HASH WHEN TAB CHANGED
 */
$('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {

    history.replaceState(null, null, $(e.target).attr('href'));

});
