<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>@yield('title') | Ayah Racing Jaya</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('assets/landing/lib/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/landing/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/landing/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

        <!-- Bootstrap Stylesheet -->
        <link href="{{ asset('assets/landing/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('assets/landing/css/style.css') }}" rel="stylesheet">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}">

        <!-- Apple Icon -->
        <link rel="apple-touch-icon" href="{{ URL::asset('assets/landing/img/logo_arj.jpeg') }}">
    </head>

    <body>

        <!-- Spinner Start -->
        {{-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> --}}
        <!-- Spinner End -->


        <!-- Topbar Start -->
        <div class="container-fluid topbar px-0 d-none d-lg-block">
            <div class="container px-0">
                <div class="row gx-0 align-items-center" style="height: 45px;">
                    <div class="col-lg-11 text-center text-lg-start mb-lg-0">
                        <div class="d-flex flex-wrap">

                            <a href="https://maps.google.com/?q=Jl.+Pumpungan+3+No.+12"
                                target="_blank"
                                class="text-light me-4">
                                <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                Jl. Pumpungan 3 No. 12
                            </a>

                            <a href="mailto:arj.racingorganizer@gmail.com"
                                class="text-light me-4">
                                <i class="fas fa-envelope text-secondary me-2"></i>
                                arj.racingorganizer@gmail.com
                            </a>

                            <a href="https://wa.me/6281216983050"
                                target="_blank"
                                class="text-light me-4">
                                <i class="fab fa-whatsapp text-success me-2"></i>
                                +62 812-1698-3050 (Belgis)
                            </a>

                            <a href="https://wa.me/6281252917553"
                                target="_blank"
                                class="text-light me-0">
                                <i class="fab fa-whatsapp text-success me-2"></i>
                                +62 812-5291-7553 (Irvan)
                            </a>

                        </div>
                    </div>
                    <div class="col-lg-1 text-center text-lg-end">
                        <div class="d-flex align-items-center justify-content-end">
                            {{-- <a href="" class="btn btn-dark btn-square rounded-circle nav-fill me-3"><i class="fab fa-facebook-f text-secondary"></i></a> --}}
                            {{-- <a href="#" class="btn btn-dark btn-square rounded-circle nav-fill me-3"><i class="fab fa-twitter text-secondary"></i></a> --}}
                            {{-- <a href="" class="btn btn-dark btn-square rounded-circle nav-fill me-3"><i class="fab fa-instagram text-secondary"></i></a> --}}
                            <a href="https://www.tiktok.com/@ayahracingjaya" class="btn btn-dark btn-square rounded-circle nav-fill me-0"><i class="fab fa-tiktok text-secondary"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid sticky-top px-0">
            <div class="position-absolute bg-dark" style="left: 0; top: 0; width: 100%; height: 100%;">
            </div>
            <div class="container px-0">
                <nav class="navbar navbar-expand-lg navbar-dark bg-white py-3 px-4">
                    <a href="/" class="navbar-brand p-0">
                        <h1 class="text-primary m-0">
                            <img src="{{ asset('assets/landing/img/logo_arj.jpeg') }}" class="img-fluid w-12" alt="Image">
                            Ayah Racing Jaya
                        </h1>
                        <!-- <img src="img/logo.png" alt="Logo"> -->
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="#home" class="nav-item nav-link">Beranda</a>
                            <a href="#event" class="nav-item nav-link">Event</a>
                            <a href="#dokumentasi" class="nav-item nav-link">Dokumentasi</a>
                            <a href="#regulasi" class="nav-item nav-link">Regulasi</a>
                            <a href="#disupport" class="nav-item nav-link">Disupport</a>
                        </div>
                        <div class="d-flex align-items-center pt-xl-0">
                            <a href="/login" class="btn btn-dark rounded-pill text-white py-2 px-4">Daftar</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <div class="content">
            @yield('content')
        </div>

        <!-- Footer Start -->
        {{-- <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay="0.2s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <div class="footer-item">
                                <h4 class="text-white mb-4">Newsletter</h4>
                                <p class="mb-3">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
                                <div class="position-relative mx-auto rounded-pill">
                                    <input class="form-control rounded-pill w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                                    <button type="button" class="btn btn-primary rounded-pill position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Explore</h4>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Home</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Services</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> About Us</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Latest Projects</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> testimonial</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Our Team</a>
                            <a href="#"><i class="fas fa-angle-right me-2"></i> Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="text-white mb-4">Contact Info</h4>
                            <a href=""><i class="fa fa-map-marker-alt me-2"></i> 123 Street, New York, USA</a>
                            <a href=""><i class="fas fa-envelope me-2"></i> info@example.com</a>
                            <a href=""><i class="fas fa-envelope me-2"></i> info@example.com</a>
                            <a href=""><i class="fas fa-phone me-2"></i> +012 345 67890</a>
                            <a href="" class="mb-3"><i class="fas fa-print me-2"></i> +012 345 67890</a>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-light btn-md-square me-2" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-light btn-md-square me-2" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-light btn-md-square me-2" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-light btn-md-square me-0" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item-post d-flex flex-column">
                            <h4 class="text-white mb-4">Popular Post</h4>
                            <div class="d-flex flex-column mb-3">
                                <p class="text-uppercase text-primary mb-2">Investment</p>
                                <a href="#" class="text-body">Revisiting Your Investment & Distribution Goals</a>
                            </div>
                            <div class="d-flex flex-column mb-3">
                                <p class="text-uppercase text-primary mb-2">Business</p>
                                <a href="#" class="text-body">Dimensional Fund Advisors Interview with Director</a>
                            </div>
                            <div class="footer-btn text-start">
                                <a href="#" class="btn btn-light rounded-pill px-4">View All Post <i class="fa fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <!-- Footer End -->

        <div class="container-fluid footer py-5 bg-dark text-white">
            <div class="container">
                <div class="row g-5 justify-content-center">

                    <!-- Tentang -->
                    <div class="col-lg-4 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white mb-4">Ayah Racing Jaya</h4>

                            <p class="mb-4">
                                Ayah Racing Jaya (ARJ) merupakan Racing Organizer yang bergerak di bidang penyelenggaraan event otomotif. ARJ hadir untuk menyediakan kompetisi yang profesional, sportif, dan berkualitas sebagai wadah bagi para pembalap untuk mengembangkan bakat serta meraih prestasi.
                            </p>

                            {{-- <div class="d-flex">
                                <a class="btn btn-outline-light btn-sm-square rounded-circle me-2" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a class="btn btn-outline-light btn-sm-square rounded-circle me-2" href="#">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a class="btn btn-outline-light btn-sm-square rounded-circle me-2" href="#">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <a class="btn btn-outline-light btn-sm-square rounded-circle" href="#">
                                    <i class="fab fa-tiktok"></i>
                                </a>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Menu -->
                    <div class="col-lg-2 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white mb-4">Menu</h4>

                            <a href="#home" class="d-block mb-2">
                                <i class="fas fa-angle-right me-2"></i> Beranda
                            </a>

                            {{-- <a href="#about" class="d-block mb-2">
                                <i class="fas fa-angle-right me-2"></i> Tentang Kami
                            </a> --}}

                            <a href="#event" class="d-block mb-2">
                                <i class="fas fa-angle-right me-2"></i> Event
                            </a>

                            <a href="#dokumentasi" class="d-block mb-2">
                                <i class="fas fa-angle-right me-2"></i> Dokumentasi
                            </a>

                            <a href="#regulasi" class="d-block mb-2">
                                <i class="fas fa-angle-right me-2"></i> Regulasi
                            </a>

                            <a href="#disupport" class="d-block">
                                <i class="fas fa-angle-right me-2"></i> Disupport Oleh
                            </a>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="col-lg-4 col-md-10">
                        <div class="footer-item">
                            <h4 class="text-white mb-4">Kontak Kami</h4>

                            <p>
                                <a href="https://maps.google.com/?q=Jl.+Pumpungan+3+No.12+Surabaya"
                                target="_blank"
                                class="text-light text-decoration-none">
                                    <i class="fas fa-map-marker-alt text-secondary me-2"></i>
                                    Jl. Pumpungan 3 No.12, Surabaya
                                </a>
                            </p>

                            <p>
                                <a href="mailto:arj.racingorganizer@gmail.com"
                                class="text-light text-decoration-none">
                                    <i class="fas fa-envelope text-secondary me-2"></i>
                                    arj.racingorganizer@gmail.com
                                </a>
                            </p>

                            <p>
                                <a href="https://wa.me/6281216983050"
                                target="_blank"
                                class="text-light text-decoration-none">
                                    <i class="fab fa-whatsapp text-success me-2"></i>
                                    +62 812-1698-3050 (Belgis)
                                </a>
                            </p>

                            <p>
                                <a href="https://wa.me/6281252917553"
                                target="_blank"
                                class="text-light text-decoration-none">
                                    <i class="fab fa-whatsapp text-success me-2"></i>
                                    +62 812-5291-7553 (Irvan)
                                </a>
                            </p>

                            <p class="mb-0">
                                <a href="https://www.tiktok.com/@ayahracingjaya"
                                target="_blank"
                                class="text-light text-decoration-none">
                                    <i class="fab fa-tiktok text-secondary me-2"></i>
                                    @ayahracingjaya
                                </a>
                            </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Copyright -->
        {{-- <div class="container-fluid bg-primary py-3">
            <div class="container text-center">
                <small class="text-light">
                    © {{ date('Y') }} Ayah Racing Jaya. All Rights Reserved.
                </small>
            </div>
        </div> --}}


        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container text-center">
                <small class="text-light">
                    © {{ date('Y') }} Ayah Racing Jaya. All Rights Reserved.
                </small>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/landing/lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('assets/landing/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('assets/landing/lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/landing/lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/landing/lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/landing/lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('assets/landing/js/main.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const sections = document.querySelectorAll('[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        const observer = new IntersectionObserver(
            entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {

                        navLinks.forEach(link =>
                            link.classList.remove('active')
                        );

                        const activeLink = document.querySelector(
                            `.nav-link[href="#${entry.target.id}"]`
                        );

                        if (activeLink) {
                            activeLink.classList.add('active');
                        }
                    }
                });
            },
            {
                threshold: 0.4
            }
        );

        sections.forEach(section => observer.observe(section));
    });
    </script>
    </body>

</html>
