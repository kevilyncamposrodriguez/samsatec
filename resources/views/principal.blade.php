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

        <!-- Slider start -->
        <section id="home" class="no-padding">

            <div id="main-slide" class="ts-flex-slider">

                <div class="flexSlideshow flexslider">
                    <ul class="slides">
                        <li id="hero" class="d-flex align-items-center">
                            <div class="overlay2">
                                <img class="" src="{{ asset('principal/images/fondo.jpg') }}" alt="slider">
                            </div>
                            <div class="slider-content">
                                <div class="row">
                                    <div class="col-lg-6 d-flex ">
                                        <h3 class="animated2" style="color: goldenrod;">
                                            Todo en un mismo lugar!
                                        </h3>
                                        <h3 class="animated3">
                                            Contabilidad, Inventario y Facturación electrónica en un mismo sitio
                                        </h3>
                                        <p class="animated4"><a class="animated4 slider btn btn-primary btn-min-block solid" href="#prices">Planes</a></p>
                                    </div>
                                    <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
                                        <img src="principal/images/hero-img.png" class="img-fluid animated" alt="">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="overlay2">
                                <img class="" src="{{ asset('principal/images/slider/bg1.jpg') }}" alt="slider">
                            </div>
                            <div class="flex-caption slider-content">
                                <div class="col-md-12 text-center">
                                    <h2 class="animated4 " style="color: goldenrod;">
                                        Implementación sencilla y rápida
                                    </h2>
                                    <h3 class="animated5">
                                        Nuestro agentes estarán siempre dispuestos a aclarar sus dudas.
                                    </h3>
                                    <p class="animated6"><a href="#prices" class="slider btn btn-primary white">Adquirir</a></p>
                                </div>

                            </div>
                        </li>
                        <li>
                            <div class="overlay2">
                                <img class="" src="{{ asset('principal/images/slider/bg2.jpg') }}" alt="slider">
                            </div>
                            <div class="flex-caption slider-content">
                                <div class="col-md-12 text-center">
                                    <h2 class="animated7" style="color: goldenrod;">
                                        Compatibilidad con sus diferentes dispositivos
                                    </h2>
                                    <h3 class="animated8">
                                        Use nuestro sistema desde cualquier lugar en cualquier momento.
                                    </h3>
                                    <div class="">
                                        <a class="animated4 slider btn btn-primary btn-min-block white" href="#prices">Planes</a><a class="animated4 slider btn btn-primary btn-min-block solid" href="#videos">Videos</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!--/ Main slider end -->
        </section>
        <!--/ Slider end -->

        <!-- About tab start -->
        <section id="about" class="about angle">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 heading">
                        <span class="title-icon pull-left"><i class="fa fa-cogs"></i></span>
                        <h2 class="title">Conozca nuestra compañía <span class="title-desc">Ofreciendo soluciones novedosas para la pequeña, mediana y gran empresa</span></h2>
                    </div>
                </div><!-- Title row end -->

                <div class="row">
                    <div class="featured-tab clearfix">
                        <ul class="nav nav-tabs nav-stacked col-md-3 col-sm-5">
                            <li class="active">
                                <a class="animated fadeIn" href="#tab_a" data-toggle="tab">
                                    <span class="tab-icon"><i class="fa fa-bullseye"></i></span>
                                    <div class="tab-info">
                                        <h3>Mision</h3>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="animated fadeIn" href="#tab_b" data-toggle="tab">
                                    <span class="tab-icon"><i class="fa fa-eye"></i></span>
                                    <div class="tab-info">
                                        <h3>Visión</h3>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="animated fadeIn" href="#tab_c" data-toggle="tab">
                                    <span class="tab-icon"><i class="fa fa-diamond"></i></span>
                                    <div class="tab-info">
                                        <h3>Valores</h3>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="animated fadeIn" href="#tab_d" data-toggle="tab">
                                    <span class="tab-icon"><i class="fa fa-list-ol"></i></span>
                                    <div class="tab-info">
                                        <h3>Objetivos</h3>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content col-md-9 col-sm-7">
                            <div class="tab-pane active animated fadeInRight" id="tab_a">
                                <img class="img-responsive pull-left" width="50%" src="principal/images/mision.png" alt="">
                                <h3>Nuestra misión</h3>
                                <p>Proveer soluciones integrales de alta innovación tecnológica, adaptadas a las necesidades del cliente en cuanto a seguridad y desarrollo de software.</p>

                            </div>
                            <div class="tab-pane animated fadeInLeft" id="tab_b">
                                <img class="img-responsive pull-right" width="50%" src="principal/images/vision.png" alt="">
                                <h3>Nuestra Visión</h3>
                                <p>Ser una empresa posicionada en el sector tecnológico con servicios administrativos contables, brindando soluciones eficientes con el software SamsaTec, en el mercado nacional e internacional, que permitan asegurar la sostenibilidad de la empresa a largo plazo, por medio de respeto, la ética y el compromiso que nos caracteriza. </p>

                            </div>
                            <div class="tab-pane animated fadeIn" id="tab_c">
                                <img class="img-responsive pull-left" width="25%" src="principal/images/valores.png" alt="">
                                <h3 class="text-center">Nuestros Valores</h3>
                                <ul class="check-list">
                                    <li><i class="fa fa-check"></i> Excelencia</li>
                                    <li><i class="fa fa-check"></i>Compromiso</li>
                                    <li><i class="fa fa-check"></i> Disciplina</li>
                                    <li><i class="fa fa-check"></i> Respeto</li>
                                    <li><i class="fa fa-check"></i> Esfuerso</li>
                                    <li><i class="fa fa-check"></i> Honestidad</li>
                                </ul>
                            </div>
                            <div class="tab-pane animated fadeIn" id="tab_d">
                                <div class="row">
                                    <div class="feature-box col-sm-4 " data-wow-delay=".5s">
                                        <span class="feature-icon pull-left"><i class="fa fa-area-chart"></i></span>
                                        <div class="feature-content">
                                            <h3>Alcance</h3>
                                            <p>Ser una empresa líder en soluciones tecnológicas a corto plazo en el mercado nacional. </p>
                                        </div>
                                    </div>
                                    <!--/ End first featurebox -->

                                    <div class="feature-box col-sm-4 " data-wow-delay=".5s">
                                        <span class="feature-icon pull-left"><i class="fa fa-lightbulb-o"></i></span>
                                        <div class="feature-content">
                                            <h3>Promover</h3>
                                            <p>Promover la innovación en el servicio que desarrolla la empresa a nivel tecnológico.</p>
                                        </div>
                                    </div>
                                    <!--/ End first featurebox -->

                                    <div class="feature-box col-sm-4 " data-wow-delay=".5s">
                                        <span class="feature-icon pull-left"><i class="fa fa-line-chart"></i></span>
                                        <div class="feature-content">
                                            <h3>Incentivar</h3>
                                            <p>Incentivar la eficiencia en el servicio propuesto como herramienta tecnológica para las empresas.</p>
                                        </div>
                                    </div>
                                    <!--/ End first featurebox -->
                                </div><!-- Content row end -->
                                <div class="row">
                                    <div class="feature-box col-sm-4 " data-wow-delay=".5s">
                                        <span class="feature-icon pull-left"><i class="fa fa-heart-o"></i></span>
                                        <div class="feature-content">
                                            <h3>Facilitar</h3>
                                            <p>Facilitar las actividades diarias en las áreas administrativas y contables de los clientes.</p>
                                        </div>
                                    </div>
                                    <!--/ End first featurebox -->

                                    <div class="feature-box col-sm-4" data-wow-delay=".5s">
                                        <span class="feature-icon pull-left"><i class="fa fa-globe"></i></span>
                                        <div class="feature-content">
                                            <h3>Promocionar</h3>
                                            <p>Promocionar el uso de tecnología dentro de las organizaciones, para que se incentive un mejor uso de los recursos naturales. </p>
                                        </div>
                                    </div>
                                    <!--/ End first featurebox -->
                                </div><!-- Content row end -->
                            </div>


                        </div><!-- tab content -->
                    </div><!-- Featured tab end -->
                </div><!-- Content row end -->
            </div><!-- Container end -->
        </section><!-- About end -->

        <!-- Counter Strat -->
        <section id="" class="ts_counter no-padding">
            <div class="container-fluid">
                <div class="row facts-wrapper wow fadeInLeft text-center">
                    <div class="facts one col-md-6 col-sm-6" style="color: goldenrod;">
                        <span class="facts-icon"><i class="fa fa-user"></i></span>
                        <div class="facts-num">
                            <span class="counter">{{ $clients }}</span>
                        </div>
                        <h3 style="color: goldenrod;">Clientes</h3>
                    </div>

                    <div class="facts two col-md-6 col-sm-6" style="color: goldenrod;">
                        <span class="facts-icon"><i class="fa fa-institution"></i></span>
                        <div class="facts-num">
                            <span class="counter">1277
                            </span>
                        </div>
                        <h3 style="color: goldenrod;">Visitas</h3>
                    </div>
                </div>
            </div>
            <!--/ Container end -->
        </section>
        <!--/ Counter end -->
        <!-- Pricing table start -->
        <section id="prices" class="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 heading">
                        <span class="title-icon pull-left"><i class="fa fa-university"></i></span>
                        <h2 class="title">Sistema de inventario y facturación electronica<span class="title-desc">Obtenga el mejor servicio sin pagar de más.</span></h2>
                    </div>
                </div><!-- Title row end -->
                <div class="row center">
                    <!-- plan start -->
                    <!-- plan start -->
                    <div class="col-md-3 col-sm-3 wow fadeInUp" data-wow-delay="1s">
                        <div class="plan text-center">
                            <span class="plan-name">Facturador<small>Plan mensual</small></span>
                            <p class="plan-price"><sup class="currency">₡</sup><strong>5 000</strong><sub>+IVA</sub></p>
                            <ul class="list-unstyled">
                                <li>Dashboard de resumen de movimientos</li>
                                <li>Proformas y Ordenes de Venta</li>
                                <li>Facturación Electrónica</li>
                                <li>Facturas de Venta pendientes de impugnación</li>
                                <li>Carga automática de comprobantes de compra</li>
                                <li>Aceptación y rechazo de comprobantes electrónicos.</li>
                                <li> Registro De Compras Y Gastos Electrónicos
                                    pendientes de impugnación</li>
                                <li>Registro De Compras Y Gastos no Deducibles</li>
                                <li>Control de Productos y Servicios</li>
                                <li>Control de Clientes</li>
                                <li>Control de Proveedores</li>
                                <li>Control de Compradores</li>
                                <li>Control de Vendedores</li>
                                <li>Control de exoneraciones</li>
                                <li>Control de descuentos</li>
                                <li>Control de impuestos según Ministerio Hacienda</li>
                                <li>Variedad en Reportería</li>
                                <li>Multimoneda</li>
                                <li>Cuenta con Soporte Técnico</li>
                                <li>Sistema de Referidos</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('membership',1) }}">Comprar</a>
                        </div>
                    </div><!-- plan end -->
                    <!-- plan start -->
                    <div class="col-md-3 col-sm-3 wow fadeInUp" data-wow-delay="1.4s">
                        <div class="plan text-center featured">
                            <span class="plan-name">Básico <small>Plan mensual</small></span>
                            <p class="plan-price"><sup class="currency">₡</sup><strong>10 000</strong><sub>+IVA</sub></p>
                            <ul class="list-unstyled">
                                <li>Dashboard de resumen de movimientos</li>
                                <li>Proformas y Ordenes de Venta</li>
                                <li>Facturación Electrónica</li>
                                <li>Facturas de Venta pendientes de impugnación</li>
                                <li>Carga automática de comprobantes de compra</li>
                                <li>Aceptación y rechazo de comprobantes electrónicos.</li>
                                <li> Registro De Compras Y Gastos Electrónicos
                                    pendientes de impugnación</li>
                                <li>Registro De Compras Y Gastos no Deducibles</li>
                                <li>Control de Productos y Servicios</li>
                                <li>Control de Clientes</li>
                                <li>Control de Proveedores</li>
                                <li>Control de Compradores</li>
                                <li>Control de Vendedores</li>
                                <li>Control de exoneraciones</li>
                                <li>Control de descuentos</li>
                                <li>Control de impuestos según Ministerio Hacienda</li>
                                <li>Variedad en Reportería</li>
                                <li>Multimoneda</li>
                                <li>Control de Pagos</li>
                                <li>Cuentas contables personalizables</li>
                                <li>Prorrateo</li>
                                <li>Cuenta con Soporte Técnico</li>
                                <li>Sistema de Referidos</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('membership',2) }}">Comprar</a>
                        </div>
                    </div><!-- plan end -->

                    <!-- plan start -->
                    <div class="col-md-3 col-sm-3 wow fadeInUp" data-wow-delay="1s">
                        <div class="plan text-center">
                            <span class="plan-name">Estandar<small>Plan mensual</small></span>
                            <p class="plan-price"><sup class="currency">₡</sup><strong>20 000</strong><sub>+IVA</sub></p>
                            <ul class="list-unstyled">
                                <li>Dashboard de resumen de movimientos</li>
                                <li>Proformas y Ordenes de Venta</li>
                                <li>Facturación Electrónica</li>
                                <li>Facturas de Venta pendientes de impugnación</li>
                                <li>Carga automática de comprobantes de compra</li>
                                <li>Aceptación y rechazo de comprobantes electrónicos.</li>
                                <li> Registro De Compras Y Gastos Electrónicos
                                    pendientes de impugnación</li>
                                <li>Registro De Compras Y Gastos no Deducibles</li>
                                <li>Control de Productos y Servicios</li>
                                <li>Control de Clientes</li>
                                <li>Control de Proveedores</li>
                                <li>Control de Compradores</li>
                                <li>Control de Vendedores</li>
                                <li>Control de exoneraciones</li>
                                <li>Control de descuentos</li>
                                <li>Control de impuestos según Ministerio Hacienda</li>
                                <li>Variedad en Reportería</li>
                                <li>Manejo de Categorías, familias, clases, zonas, lotes</li>
                                <li>Control de inventario por compañía</li>
                                <li>Cuentas contables personalizables</li>
                                <li>Listas de precios personalizadas e ilimitadas</li>
                                <li>Registro mensual de IVA</li>
                                <li>Registro anual de IVA</li>
                                <li>Dashboard análisis pago renta</li>
                                <li>Multimoneda</li>
                                <li>Control de Pagos</li>
                                <li>Prorrateo</li>
                                <li>Cuenta con Soporte Técnico</li>
                                <li>Sistema de Referidos</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('membership',3) }}">Comprar</a>
                        </div>
                    </div><!-- plan end -->

                    <!-- plan start -->
                    <div class="col-md-3 col-sm-3 wow fadeInUp" data-wow-delay="1.4s">
                        <div class="plan text-center featured">
                            <span class="plan-name">Intermedio <small>Plan mensual</small></span>
                            <p class="plan-price"><sup class="currency">₡</sup><strong>40 0000</strong><sub>+IVA</sub></p>

                            <ul class="list-unstyled ">
                                <div data-scrollbar="true" data-height="100px">
                                    <li>Dashboard de resumen de movimientos</li>
                                    <li>Proformas y Ordenes de Venta</li>
                                    <li>Facturación Electrónica</li>
                                    <li>Facturas de Venta pendientes de impugnación</li>
                                    <li>Carga automática de comprobantes de compra</li>
                                    <li>Aceptación y rechazo de comprobantes electrónicos.</li>
                                    <li> Registro De Compras Y Gastos Electrónicos
                                        pendientes de impugnación</li>
                                    <li>Registro De Compras Y Gastos no Deducibles</li>
                                    <li>Control de Productos y Servicios</li>
                                    <li>Control de Clientes</li>
                                    <li>Control de Proveedores</li>
                                    <li>Control de Compradores</li>
                                    <li>Control de Vendedores</li>
                                    <li>Control de exoneraciones</li>
                                    <li>Control de descuentos</li>
                                    <li>Control de impuestos según Ministerio Hacienda</li>
                                    <li>Variedad en Reportería</li>
                                    <li>Manejo de Categorías, familias, clases, zonas, lotes</li>
                                    <li>Control de inventario por compañía</li>
                                    <li>Cuentas contables personalizables</li>
                                    <li>Listas de precios personalizadas e ilimitadas</li>
                                    <li>Registro mensual de IVA</li>
                                    <li>Registro anual de IVA</li>
                                    <li>Dashboard análisis pago renta</li>
                                    <li>Multimoneda</li>
                                    <li>Control de Pagos</li>
                                    <li>Prorrateo</li>
                                    <li>Control de inventario por sucursal</li>
                                    <li>Control de Cuentas por Cobrar</li>
                                    <li>Control de Cuentas por Pagar</li>
                                    <li>Control de Documentos por Pagar</li>
                                    <li>Cuenta con Soporte Técnico</li>
                                    <li>Sistema de Referidos</li>
                                </div>
                            </ul>

                            <a class="btn btn-primary" href="{{ route('membership',4) }}">Comprar</a>
                        </div>
                    </div><!-- plan end -->


                </div>
                <!--/ Content row end -->
            </div>
            <!--/  Container end-->
        </section>
        <!--/ Pricing table end -->
        <!-- Parallax 2 start -->
        <section class="parallax parallax2">
            <div class="parallax-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2>Gana dinero con nosotros!</h2>
                        <h3>Registrate a nuestro sistema de referidos y gana con nosotros vendiendo nuestros productos</h3>
                        <p>
                            <a href="#" class="btn btn-primary white">Más información</a>
                            <a href="#" class="btn btn-primary solid">Registrarme</a>
                        </p>
                    </div>
                </div>
            </div><!-- Container end -->
        </section><!-- Parallax 2 end -->
        <!-- Portfolio start -->
        <section id="contact">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 heading text-center">
                        <span class="icon-pentagon wow bounceIn"><i class="fa fa-suitcase"></i></span>
                        <h2 class="title2">Contacto
                            <span class="title-desc">Una forma rapida y segura de contactarnos, estamos atentos a cualquier solicitud.</span>
                        </h2>
                    </div>
                </div> <!-- Title row end -->
                <div class="row">
                    <div class="col-md-12">
                        <form id="contact-form" action="contact-form.php" method="post" role="form">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color: black;">Nombre:</label>
                                        <input class="form-control" name="name" id="name" placeholder="" type="text" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color: black;">Correo:</label>
                                        <input class="form-control" name="email" id="email" placeholder="" type="email" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="color: black;">Asunto:</label>
                                        <input class="form-control" name="subject" id="subject" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label style="color: black;">Mensaje:</label>
                                <textarea class="form-control" name="message" id="message" placeholder="" rows="10" required></textarea>
                            </div>
                            <div class="text-right"><br>
                                <button class="btn btn-primary solid blank" type="submit">Enviar Mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <!--/ container end -->

        </section>
        <!--/ Main container end -->



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