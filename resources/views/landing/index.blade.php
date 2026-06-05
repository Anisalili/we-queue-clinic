<!doctype html>
<html class="no-js" lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Qlinic — Apotek Anna Farma</title>
    <meta
        name="description"
        content="Clinic Queue - Booking online, pantau antrian real-time, dan cek jadwal layanan klinik."
    />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="manifest" href="{{ asset('landing/site.webmanifest') }}" />
    <link
        rel="shortcut icon"
        type="image/x-icon"
        href="{{ asset('landing/assets/img/favicon.ico') }}"
    />

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('landing/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/slicknav.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/gijgo.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/animated-headline.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/fontawesome-all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/themify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('landing/assets/css/style.css') }}" />
    <style>
        .hero-image {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            max-height: 520px;
        }
        .stat-card h3,
        .stat-card p,
        #metric-schedule-info {
            color: #0f2f1e;
        }
        .hero__caption h1 { line-height: 1.1; }
        .hero-block {
            background: #e7f5ea;
            padding: 90px 0;
        }
        .home-blog-single .blog-img img {
            width: 100%;
            height: 320px;
            object-fit: cover;
        }
        .service-area .row {
            display: flex;
            flex-wrap: wrap;
        }
        .service-area .row > [class*="col-"] {
            display: flex;
        }
        .service-area .single-cat {
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="preloader-active">
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="preloader-inner position-relative">
                <div class="preloader-circle"></div>
                <div class="preloader-img pere-text">
                    <img src="{{ asset('landing/assets/img/logo/loder.png') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <header>
        <div class="header-area">
            <div class="main-header header-sticky">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-2 col-lg-2 col-md-1">
                            <div class="logo">
                                <a href="#home" style="text-decoration: none;">
                                    <img src="{{ asset('landing/assets/img/logo.png') }}" alt="Qlinic - Apotek Anna Farma" style="max-height: 70px; width: auto;" />
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <div class="menu-main d-flex align-items-center justify-content-end">
                                <div class="main-menu f-right d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="#home">Beranda</a></li>
                                            <li><a href="#features">Fitur</a></li>
                                            <li><a href="#stats">Status Hari Ini</a></li>
                                            <li><a href="#contact">Kontak</a></li>
                                            <li><a href="/login">Masuk</a></li>
                                            <li><a href="/register">Daftar</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="header-right-btn f-right d-none d-lg-block ml-15">
                                    <a href="/booking/create" class="btn header-btn">Buat Booking</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <section class="hero-block" id="home">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-6 col-md-12">
                        <div class="hero-wrapper">
                            <div class="hero__caption">
                                <h1>
                                    Pantau Antrian Klinik<br />Tanpa Ribet
                                </h1>
                                <p>
                                    Booking online, pantau status antrian, dan datang saat giliran Anda dipanggil.
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="/login" class="btn">Masuk</a>
                                    <a href="/register" class="btn btn-border">Daftar</a>
                                    <a href="/booking/create" class="btn btn-border">Buat Booking</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-12 mt-4 mt-lg-0">
                        <img
                            src="{{ asset('landing/assets/img/Anna Farma.png') }}"
                            alt="Apotek Anna Farma"
                            class="hero-image"
                        />
                    </div>
                </div>
            </div>
        </section>

        <div class="about-area2 section-padding40">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7 col-md-12">
                        <div class="about-img">
                            <img
                                src="{{ asset('landing/assets/img/meja depan.jpg.jpeg') }}"
                                alt="Meja resepsionis klinik"
                                style="border-radius: 24px;"
                            />
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="about-caption">
                            <div class="section-tittle mb-35">
                                <h2 id="features">Alur Booking Klinik Queue</h2>
                            </div>
                            <p class="pera-top mb-40">
                                Daftar online, dapatkan nomor antrian, dan cek progres secara real-time.
                            </p>
                            <p class="pera-bottom mb-30">
                                1) Buat booking dan pilih kategori pasien (BPJS/Umum).<br />
                                2) Check-in di resepsionis saat tiba.<br />
                                3) Pantau nomor yang sedang dilayani dan giliran Anda.
                            </p>
                            <div class="icon-about">
                                <img src="{{ asset('landing/assets/img/icon/about1.svg') }}" alt="" class="mr-20" />
                                <img src="{{ asset('landing/assets/img/icon/about2.svg') }}" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="wantToWork-area section-bg3" data-background="{{ asset('landing/assets/img/gallery/section_bg01.png') }}" id="stats">
            <div class="container">
                <div class="wants-wrapper w-padding2">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-7 col-lg-9 col-md-8">
                            <div class="wantToWork-caption wantToWork-caption2">
                                <h2>Status Antrian Hari Ini</h2>
                                <p>Pantau kapasitas klinik, pasien dalam antrian, dan sisa slot. ({{ $today }})</p>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-4">
                            <a href="/booking/create" class="btn f-right sm-left">Mulai Booking</a>
                        </div>
                    </div>
                    <div class="row mt-4 g-3">
                        <div class="col-md-4 col-6 stat-card">
                            <div class="single-services text-center p-3" style="background: rgba(255,255,255,0.7); border-radius: 10px;">
                                <h3>{{ number_format($stats['total_bookings']) }}</h3>
                                <p>Booking Masuk</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 stat-card">
                            <div class="single-services text-center p-3" style="background: rgba(255,255,255,0.7); border-radius: 10px;">
                                <h3>{{ number_format($stats['waiting_count']) }}</h3>
                                <p>Pasien Dalam Antrian</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 mt-3 mt-md-0 stat-card">
                            <div class="single-services text-center p-3" style="background: rgba(255,255,255,0.7); border-radius: 10px;">
                                <h3>{{ number_format($stats['slots_available']) }}</h3>
                                <p>{{ $slotsLabel }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <p class="mb-0" id="metric-schedule-info">{{ $scheduleInfo }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="service-area">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-cat text-center mb-50">
                            <div class="cat-icon">
                                <img src="{{ asset('landing/assets/img/icon/services1.svg') }}" alt="" />
                            </div>
                            <div class="cat-cap">
                                <h5><a href="/booking/create">Booking Online</a></h5>
                                <p>Dapatkan nomor antrian dari rumah, pilih kategori pasien (BPJS/Umum) dan tanggal yang tersedia. <em>Antrian BPJS dan Umum digabung dalam satu daftar berdasarkan urutan booking.</em></p>
                                <a href="/booking/create" class="plus-btn"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-cat text-center mb-50">
                            <div class="cat-icon">
                                <img src="{{ asset('landing/assets/img/icon/services2.svg') }}" alt="" />
                            </div>
                            <div class="cat-cap">
                                <h5><a href="/login">Pantau Antrian</a></h5>
                                <p>Lihat nomor yang sedang dipanggil, sisa antrian, dan posisi Anda langsung dari dashboard pasien.</p>
                                <a href="/login" class="plus-btn"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="single-cat text-center mb-50">
                            <div class="cat-icon">
                                <img src="{{ asset('landing/assets/img/icon/services3.svg') }}" alt="" />
                            </div>
                            <div class="cat-cap">
                                <h5><a href="#stats">Jadwal & Kuota</a></h5>
                                <p>Cek jam layanan hari ini, kuota tersisa, dan pastikan Anda datang sesuai jadwal yang ditentukan.</p>
                                <a href="#stats" class="plus-btn"><i class="ti-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="home-blog-area section-padding30">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-9 col-sm-10">
                        <div class="section-tittle text-center mb-100">
                            <h2>Panduan Cepat</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="home-blog-single mb-40">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img
                                        src="{{ asset('landing/assets/img/booking online.png') }}"
                                        alt="Booking online"
                                    />
                                </div>
                                <div class="blog-cap">
                                    <h3><a href="/booking/create">Cara Booking Online</a></h3>
                                    <p>Pilih tanggal yang tersedia, tentukan kategori BPJS/Umum, dan dapatkan nomor antrian otomatis.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="home-blog-single mb-40">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img
                                        src="{{ asset('landing/assets/img/Lihat Antrian.png') }}"
                                        alt="Pantau nomor antrian"
                                    />
                                </div>
                                <div class="blog-cap">
                                    <h3><a href="/login">Pantau Nomor Antrian</a></h3>
                                    <p>Login ke dashboard pasien untuk lihat nomor yang sedang dipanggil dan sisa antrian sebelum giliran Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="home-blog-single mb-40">
                            <div class="blog-img-cap">
                                <div class="blog-img">
                                    <img
                                        src="{{ asset('landing/assets/img/on time.png') }}"
                                        alt="Datang tepat waktu"
                                    />
                                </div>
                                <div class="blog-cap">
                                    <h3><a href="/queue/display">Datang Tepat Waktu</a></h3>
                                    <p>Cek monitor antrian publik sebelum berangkat, lalu check-in di resepsionis sesuai jadwal.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-low-area mt-30">
            <div class="container">
                <div class="about-cap-wrapper">
                    <div class="row">
                        <div class="col-xl-5 col-lg-6 col-md-10 offset-xl-1">
                            <div class="about-caption mb-50">
                                <div class="section-tittle mb-35">
                                    <h2>Antrian Transparan & Cepat</h2>
                                </div>
                                <p>Notifikasi status booking, nomor yang sedang dilayani, dan sisa antrian diperbarui otomatis.</p>
                                <a href="/login" class="border-btn">Lihat Dashboard</a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="about-img">
                                <div class="about-font-img">
                                    <img
                                        src="https://images.unsplash.com/photo-1504813184591-01572f98c85f?auto=format&fit=crop&w=1200&q=80"
                                        alt="Tim klinik"
                                        style="border-radius: 24px;"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact">
        <div class="footer-wrappr section-bg3" data-background="{{ asset('landing/assets/img/gallery/footer-bg.png') }}">
            <div class="footer-area footer-padding">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-8 col-lg-8 col-md-6 col-sm-12">
                            <div class="single-footer-caption mb-50">
                                <div class="footer-logo mb-25">
                                    <a href="#home" style="text-decoration: none;">
                                        <img src="{{ asset('landing/assets/img/logo.png') }}" alt="Qlinic - Apotek Anna Farma" style="max-height: 90px; width: auto;" />
                                    </a>
                                </div>
                                <div class="header-area">
                                    <div class="main-header main-header2">
                                        <div class="menu-main d-flex align-items-center justify-content-start">
                                            <div class="main-menu main-menu2">
                                                <nav>
                                                    <ul>
                                                        <li><a href="#home">Beranda</a></li>
                                                        <li><a href="#features">Fitur</a></li>
                                                        <li><a href="#stats">Status</a></li>
                                                        <li><a href="/login">Masuk</a></li>
                                                        <li><a href="/register">Daftar</a></li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-social mt-50">
                                    <a href="#"><i class="fab fa-twitter"></i></a>
                                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="single-footer-caption">
                                <div class="footer-tittle mb-30">
                                    <h4>Kontak Klinik</h4>
                                </div>
                                <div class="footer-pera mb-20">
                                    <p class="mb-1">Telepon: 0812-3456-7890</p>
                                    <p class="mb-1">WhatsApp: 0812-3456-7890</p>
                                    <p class="mb-0">Email: support@clinicqueue.test</p>
                                </div>
                                <div class="footer-tittle mb-20">
                                    <h4>Alamat Klinik</h4>
                                </div>
                                <div class="footer-pera">
                                    <p class="mb-0">Jl. Klinik Sehat No. 123, Jakarta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom-area">
                <div class="container">
                    <div class="footer-border">
                        <div class="row">
                            <div class="col-xl-10">
                                <div class="footer-copy-right">
                                    <p>© <script>document.write(new Date().getFullYear());</script> Qlinic — Apotek Anna Farma</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="back-top">
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <script src="{{ asset('landing/assets/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/animated.headline.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('landing/assets/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.sticky.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/hover-direction-snake.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/contact.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.form.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/mail-script.js') }}"></script>
    <script src="{{ asset('landing/assets/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('landing/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('landing/assets/js/main.js') }}"></script>
</body>
</html>
