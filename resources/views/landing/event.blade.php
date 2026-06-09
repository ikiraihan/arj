@extends('landing.index')

@section('title', 'Event')
@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb">
    <div class="bg-breadcrumb-single"></div>
    <div class="container text-center py-2" style="max-width: 900px;">
        <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Events</h4>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active text-primary">Events</li>
        </ol>
    </div>
</div>
<!-- Header End -->

<div class="container-fluid faq py-5 mt-n4">
    <div class="container py-5">
        <div class="row g-5 align-items-center">

            <!-- LEFT CONTENT -->
            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">

                <div class="pb-4">

                    <small class="text-dark d-block">
                        {{ $event->type_formatted ?? '-' }}
                    </small>
                    <h2 class="display-5">{{ $event->name }} </h2>
                    <span class="badge bg-danger mb-3">
                        Pendaftaran dengan Harga Normal hingga {{ $event->registration_end_date_formatted }} WIB
                    </span>
                </div>
                <!-- DETAIL EVENT -->
                <div class="pb-4">
                    <h4 class="text-primary">Detail Event</h4>
                    <ul class="list-unstyled text-event-detail">
                        <li class="mb-2">
                            <strong>📅 Tanggal Event:</strong>
                            {{ $event->event_date_formatted }}
                        </li>

                        <li class="mb-2">
                            <strong>📍 Lokasi:</strong>

                            @if($event->link_maps)
                                <a href="{{ $event->link_maps }}"
                                    class="text-primary"
                                    target="_blank"
                                    rel="noopener noreferrer">
                                    {{ $event->location }}
                                </a>
                            @else
                                {{ $event->location }}
                            @endif
                        </li>
                    </ul>

                    <h4 class="text-primary">
                        Kelas Event
                    </h4>

                    <ul class="list-unstyled text-event-detail">

                        @forelse($event->classes as $class)

                            <li class="mb-2">

                                <strong>{{ $class->name }}</strong>

                                - Rp. {{ number_format($class->final_price, 0, ',', '.') }}

                            </li>

                        @empty

                            <li>
                                Belum ada kelas tersedia
                            </li>

                        @endforelse

                    </ul>

                    @if(!empty($event->description))

                        <div class="pb-3">

                            <h5 class="text-primary">
                                Deskripsi Event
                            </h5>

                            <p id="shortDesc" class="text-event-detail">
                                {{ \Illuminate\Support\Str::limit(strip_tags($event->description), 200) }}
                            </p>

                            <p id="fullDesc"
                                class="text-event-detail"
                                style="display: none;">
                                {!! nl2br(e($event->description)) !!}
                            </p>

                            @if(strlen(strip_tags($event->description)) > 200)
                                <a href="javascript:void(0)"
                                    id="readMoreBtn"
                                    class="text-primary">
                                    Read More
                                </a>
                            @endif

                        </div>

                    @endif
                </div>

                <!-- BUTTON -->
                <div class="pt-2">
                    <a class="btn btn-dark rounded-pill py-3 px-5" href="{{ route('registration-form', $event->id) }}">
                        Daftar Sekarang!
                    </a>
                </div>

            </div>

            <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.1s">
                <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">

                    <!-- INDICATOR (bulatan bawah) -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="2"></button>
                    </div>

                    <!-- SLIDES -->
                   <div class="carousel-inner rounded">

                        <div class="carousel-item active">

                            <img src="{{ $event->photo_url }}"
                                class="d-block w-100 rounded"
                                alt="{{ $event->name }}">

                        </div>

                    </div>
                    <!-- BUTTON PREV/NEXT -->
                    {{-- <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>

                    <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button> --}}

                </div>
            </div>

            <!-- RIGHT IMAGE -->
            {{-- <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.1s">
                <div class="faq-img RotateMoveRight rounded">
                    <img src="{{ asset('assets/landing/img/poster_1.png') }}" class="img-fluid rounded w-100" alt="Image">
                </div>
            </div> --}}

        </div>
    </div>
</div>
<script>
    document.getElementById("readMoreBtn").addEventListener("click", function () {
        const shortDesc = document.getElementById("shortDesc");
        const fullDesc = document.getElementById("fullDesc");
        const btn = this;

        if (fullDesc.style.display === "none") {
            fullDesc.style.display = "block";
            shortDesc.style.display = "none";
            btn.innerText = "Read Less";
        } else {
            fullDesc.style.display = "none";
            shortDesc.style.display = "block";
            btn.innerText = "Read More";
        }
    });
</script>

@endsection
