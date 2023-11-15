<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <!-- Basic Page Needs
	================================================== -->
    <title>Samsatec S.A</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Specific Metas
	================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Favicons
	================================================== -->
    <link rel="icon" href="{{ asset('principal/images/logo.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('principal/images/logo.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('principal/images/logo.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('principal/images/logo.png') }}">

    <!-- CSS
	================================================== -->

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('principal/css/bootstrap.min.css') }}">
    <!-- Template styles-->
    <link rel="stylesheet" href="{{ asset('principal/css/style.css') }}">
    <!-- Responsive styles-->
    <link rel="stylesheet" href="{{ asset('principal/css/responsive.css') }}">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ asset('principal/css/font-awesome.min.css') }}">
    <!-- Animation -->
    <link rel="stylesheet" href="{{ asset('principal/css/animate.css') }}">
    <!-- Prettyphoto -->
    <link rel="stylesheet" href="{{ asset('principal/css/prettyPhoto.css') }}">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('principal/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('principal/css/owl.theme.css') }}">
    <!-- Flexslider -->
    <link rel="stylesheet" href="{{ asset('principal/css/flexslider.css') }}">
    <!-- Flexslider -->
    <link rel="stylesheet" href="{{ asset('principal/css/cd-hero.css') }}">
    <!-- Style Swicther -->
    <link href="{{ asset('principal/css/preset1.css') }}" media="screen" rel="stylesheet" type="text/css">

    <link href="{{ asset('principal/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="{{ asset('principal/js/html5shiv.js') }}"></script>
      <script src="{{ asset('principal/js/respond.min.js') }}"></script>
    <![endif]-->

</head>

<body>
    <div class="body-inner">
        <!-- Header start -->
        <header id="header" class="navbar-fixed-top header" role="banner">
            <div class="container">
                <div class="row">
                    <!-- Logo start -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="navbar-brand navbar-bg">
                            <a href="/">
                                <img class="img-responsive" src="assets/img/Logo-banner.png" alt="logo">
                            </a>
                        </div>
                    </div>
                    <!--/ Logo end -->
                    <nav class="collapse navbar-collapse clearfix" role="navigation">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="#home">Inicio</a></li>
                            <li><a href="#about">Nosotros</a></li>
                            <li><a href="{{ route('login') }}">Ingresar</a></li>
                            <li><a href="#prices">Planes</a></li>
                            <li><a href="#videos">Videos</a></li>
                            <li><a href="#contact">Contacto</a></li>
                        </ul>
                    </nav>
                    <!--/ Navigation end -->
                </div>
                <!--/ Row end -->
            </div>
            <!--/ Container end -->
        </header>
        <!--/ Header end -->

        <!-- Footer start -->
        <footer id="footer" class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-12 footer-widget">
                        <img class="img-responsive" src="assets/img/logo-auth.png" alt="logo">

                    </div>
                    <!--/ End Recent Posts-->


                    <div class="col-md-4 col-sm-12 footer-widget">
                        <h3 class="widget-title"></h3>

                        <div class="img-gallery">
                            <div class="img-container">

                            </div>
                        </div>
                    </div>
                    <!--/ end flickr -->

                    <div class="col-md-4 col-sm-12 footer-widget footer-about-us">
                        <h3 class="widget-title">Nuestras Oficinas</h3>
                        <p>Controla y procesa de forma inmediata, ágil, segura y eficiente la información que se produce a diario en la actividad de la empresa.</p>
                        <h4>Dirección</h4>
                        <p>Tejar del Guarco, Cartago</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Correo:</h4>
                                <p>info@samsatec.com</p>
                            </div>
                            <div class="col-md-6">
                                <h4>Teléfono</h4>
                                <p>+(506) 6326-6384</p>
                            </div>
                        </div>
                    </div>
                    <!--/ end about us -->

                </div><!-- Row end -->
            </div><!-- Container end -->
        </footer><!-- Footer end -->


        <!-- Footer start -->
        <section id="copyright" class="copyright angle">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <ul class="footer-social unstyled">
                            <li>
                                <a title="Twitter" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>S</i></span>
                                </a>
                                <a title="Facebook" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>A</i></span>
                                </a>
                                <a title="Google+" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>M</i></span>
                                </a>
                                <a title="linkedin" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>S</i></span>
                                </a>
                                <a title="Pinterest" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>A</i></span>
                                </a>
                                <a title="Skype" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>T</i></span>
                                </a>
                                <a title="Dribble" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>E</i></span>
                                </a>
                                <a title="Dribble" href="#">
                                    <span class="icon-pentagon wow bounceIn"><i>C</i></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Row end -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="copyright-info">
                            &copy; Copyright {{ date('Y') }} Samsatec S.A. <span>Designed by <a href="https://themefisher.com">KCR Soluciones</a></span>
                        </div>
                    </div>
                </div>
                <!--/ Row end -->
                <div id="back-to-top" data-spy="affix" data-offset-top="10" class="back-to-top affix">
                    <button class="btn btn-primary" title="Back to Top"><i class="fa fa-angle-double-up"></i></button>
                </div>
            </div>
            <!--/ Container end -->
        </section>
        <!--/ Footer end -->

        <!-- Javascript Files
	================================================== -->

        <!-- initialize jQuery Library -->
        <script type="text/javascript" src="{{ asset('principal/js/jquery.js') }}"></script>
        <!-- Bootstrap jQuery -->
        <script type="text/javascript" src="{{ asset('principal/js/bootstrap.min.js') }}"></script>
        <!-- Owl Carousel -->
        <script type="text/javascript" src="{{ asset('principal/js/owl.carousel.js') }}"></script>
        <!-- PrettyPhoto -->
        <script type="text/javascript" src="{{ asset('principal/js/jquery.prettyPhoto.js') }}"></script>
        <!-- Bxslider -->
        <script type="text/javascript" src="{{ asset('principal/js/jquery.flexslider.js') }}"></script>
        <!-- Owl Carousel -->
        <script type="text/javascript" src="{{ asset('principal/js/cd-hero.js') }}"></script>
        <!-- Isotope -->
        <script type="text/javascript" src="{{ asset('principal/js/isotope.js') }}"></script>
        <script type="text/javascript" src="{{ asset('principal/js/ini.isotope.js') }}"></script>
        <!-- Wow Animation -->
        <script type="text/javascript" src="{{ asset('principal/js/wow.min.js') }}"></script>
        <!-- SmoothScroll -->
        <script type="text/javascript" src="{{ asset('principal/js/smoothscroll.js') }}"></script>
        <!-- Eeasing -->
        <script type="text/javascript" src="{{ asset('principal/js/jquery.easing.1.3.js') }}"></script>
        <!-- Counter -->
        <script type="text/javascript" src="{{ asset('principal/js/jquery.counterup.min.js') }}"></script>
        <!-- Waypoints -->
        <script type="text/javascript" src="{{ asset('principal/js/waypoints.min.js') }}"></script>
        <!-- Template custom -->
        <script type="text/javascript" src="{{ asset('principal/js/custom.js') }}"></script>
        <script type="text/javascript" src="{{ asset('principal/vendor/glightbox/js/glightbox.min.js') }}"></script>

    </div><!-- Body inner end -->
</body>

</html>