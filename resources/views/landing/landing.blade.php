@extends('landing.index')

@section('title', 'Home')

@section('content')
<!-- Carousel Start -->
<div id="home" class="header-carousel owl-carousel">
    <div class="header-carousel-item mx-auto">
        <div class="header-carousel-item-img-2">
            <img src="{{ asset('assets/landing/img/IMG_7844.JPG') }}" class="img-fluid w-100" alt="Image">
        </div>
        <div class="carousel-caption">
            <div class="carousel-caption-inner text-center p-3">
                <h1 class="display-3 text-capitalize text-white mb-4">Ayah Racing Jaya</h1>
                <p class="mb-5 fs-5">
                    Ayah Racing Jaya (ARJ) merupakan race organizer yang bergerak di bidang penyelenggaraan event balap motor. ARJ hadir untuk menyediakan kompetisi yang profesional, sportif, dan berkualitas sebagai wadah bagi para pembalap untuk mengembangkan bakat serta meraih prestasi.
                </p>
                {{-- <a class="btn btn-primary rounded-pill py-3 px-5 mb-4 me-4" href="#">Apply Now</a> --}}
                <a class="btn btn-dark rounded-pill py-3 px-5 mb-4" href="/login">Daftar Sekarang!</a>
            </div>
        </div>
    </div>
    {{-- <div class="header-carousel-item">
        <div class="header-carousel-item-img-1">
            <img src="{{ asset('assets/landing/img/carousel-1.jpg') }}" class="img-fluid w-100" alt="Image">
        </div>
        <div class="carousel-caption">
            <div class="carousel-caption-inner text-start p-3">
                <h1 class="display-3 text-capitalize text-white mb-4 fadeInUp animate__animated" data-animation="fadeInUp" data-delay="1.3s" style="animation-delay: 1.3s;">Dummy Race</h1>
                <p class="mb-5 fs-5 fadeInUp animate__animated" data-animation="fadeInUp" data-delay="1.5s" style="animation-delay: 1.5s;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                </p>
                <a class="btn btn-primary rounded-pill py-3 px-5 mb-4 me-4 fadeInUp animate__animated" data-animation="fadeInUp" data-delay="1.5s" style="animation-delay: 1.7s;" href="#">Apply Now</a>
                <a class="btn btn-dark rounded-pill py-3 px-5 mb-4 fadeInUp animate__animated" data-animation="fadeInUp" data-delay="1.5s" style="animation-delay: 1.7s;" href="#">Read More</a>
            </div>
        </div>
    </div> --}}
</div>
<!-- Carousel End -->

<!-- Services Start -->
<div id="event" class="container-fluid service py-5">
    <div class="container py-2">

        <div class="text-center mx-auto pb-5 wow fadeInUp"
            data-wow-delay="0.1s"
            style="max-width: 800px;">

            <h1 class="display-4">Event Kami</h1>

        </div>

        <div class="row g-4 justify-content-center text-center">

            @forelse($events as $event)

                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp">

                    <div class="service-item bg-light rounded">

                        <div class="service-img">

                           <img src="{{ $event->photo_url }}"
                                class="img-fluid w-100 rounded-top"
                                alt="{{ $event->name }}">

                            <div class="service-plus-icon">
                               <a href="{{ $event->photo_url }}"
                                    data-lightbox="event-{{ $event->id }}"
                                    class="btn btn-dark btn-md-square rounded-pill">
                                    <i class="fas fa-eye fa-1x"></i>
                                </a>
                            </div>

                        </div>

                        <div class="service-content text-center p-4">

                            <div class="service-content-inner">

                                <p class="fw-bold mb-4">
                                    {{ $event->name }}
                                </p>

                                <a class="btn btn-dark rounded-pill py-2 px-4"
                                    href="{{ route('landing-event-details', $event->id) }}"
                                    {{-- href="" --}}
                                    >
                                    Daftar
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">
                    <div class="alert alert-danger">
                        Belum ada event tersedia.
                    </div>
                </div>

            @endforelse

        </div>

    </div>
</div>
<div id="dokumentasi" class="container-fluid py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="text-primary fw-bold">Dokumentasi Event</h2>
            <p class="text-muted">
                Lihat dokumentasi dari event-event yang telah kami selenggarakan sebelumnya.
            </p>
        </div>

        <div class="row g-4">

            @forelse($documentationEvents as $documentation)

                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-start">

                                <div class="me-3">

                                    <i class="fas fa-file-image text-danger fa-3x"></i>

                                </div>

                                <div class="flex-grow-1">

                                    <h5 class="mb-2">
                                         Dokumentasi: {{ $documentation->name }}
                                    </h5>

                                    <div class="d-flex flex-wrap gap-2">

                                        <a href="{{ $documentation->link_documentation }}"
                                            target="_blank"
                                            class="btn btn-dark rounded-pill py-1 px-3">

                                            <i class="fas fa-eye me-1"></i>
                                            Lihat Foto

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-danger text-center">
                        Belum ada dokumentasi yang tersedia.
                    </div>

                </div>

            @endforelse

        </div>

    </div>
</div>
<!-- Services End -->
<div class="container-fluid py-5" id="regulasi">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="text-primary fw-bold">Regulasi</h2>
            <p class="text-muted">
                Unduh dan pelajari regulasi yang berlaku sebelum mengikuti event.
            </p>
        </div>

        <div class="row g-4">

            @forelse($regulations as $regulation)

                <div class="col-lg-6">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body p-4">

                            <div class="d-flex align-items-start">

                                <div class="me-3">

                                    <i class="fas fa-file-pdf text-danger fa-3x"></i>

                                </div>

                                <div class="flex-grow-1">

                                    <h5 class="mb-2">
                                        {{ $regulation->title }}
                                    </h5>

                                    @if($regulation->description)
                                        <p class="text-muted mb-3">
                                            {{ $regulation->description }}
                                        </p>
                                    @endif

                                    <div class="d-flex flex-wrap gap-2">

                                        <a href="{{ asset('storage/' . $regulation->path) }}"
                                            target="_blank"
                                            class="btn btn-dark rounded-pill py-1 px-3">

                                            <i class="fas fa-eye me-1"></i>
                                            Lihat PDF

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-danger text-center">
                        Belum ada dokumentasi yang tersedia.
                    </div>

                </div>

            @endforelse

        </div>

    </div>
</div>
<!-- Team Start -->
<div id="disupport" class="container-fluid team pb-5">
    <div class="container pb-5">
        <div class="text-center mx-auto pb-3 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
            <h2 class="text-primary fw-bold">Disupport Oleh</h2>
        </div>
        <div class="row g-4 justify-content-center">
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
            <div class="team-item rounded">
                <div class="team-img">
                    <img src="{{ asset('assets/landing/img/KONI_JATIM.jpeg') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                </div>
            </div>
        </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/FIM.jpeg') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/FIA.jpeg') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/imi.jpg') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.3s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/imi_jatim.jpg') }}" class="img-fluid w-100 rounded-top" alt="Team Member 2">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/LOGO BASIC.png') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/LOGO BOLS.png') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/LOGO SUPER STAR.png') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded">
                    <div class="team-img">
                        <img src="{{ asset('assets/landing/img/LOGO GRACIA.png') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
            <div class="team-item rounded">
                <div class="team-img">
                    <img src="{{ asset('assets/landing/img/BPJS_TK.jpeg') }}" class="img-fluid w-100 rounded-top" alt="ikatan motor indonesia">
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<!-- Team End -->
@endsection
