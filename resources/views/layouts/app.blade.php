<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Samsatec | @yield('title')</title>
    <title>{{ config('app.name', 'Samsatec') }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="shortcut icon" href="/assets/img/logo.png" type="image/x-icon">
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="/assets/css/apple/app.css" rel="stylesheet" />
    <link href="/assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <!-- ================== END BASE CSS STYLE ================== -->
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL STYLE ================== -->
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
    <link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="/assets/plugins/lightbox2/dist/css/lightbox.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL STYLE ================== -->

    <!-- ================== END PAGE LEVEL STYLE ================== -->
    @livewireStyles
    @stack('css')

</head>

<body>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-container page-sidebar-fixed page-header-fixed">
        @include('includes.header')
        @include('includes.sidebar')
        <!-- begin #content -->
        <div id="content" class="content">
          
            {{ $slot }}
        </div>
    </div>
    <!-- end #content -->
    @stack('modals')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="/assets/js/app.min.js"></script>
    <script src="/assets/js/theme/apple.min.js"></script>
    <!-- ================== END BASE JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
    <script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
    <script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
    <script src="/assets/plugins/select2/dist/js/select2.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="/assets/js/principal.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="/assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/jstree/dist/jstree.min.js"></script>
    <script src="/assets/js/demo/ui-tree.demo.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/isotope-layout/dist/isotope.pkgd.min.js"></script>
    <script src="/assets/plugins/lightbox2/dist/js/lightbox.min.js"></script>
    <script src="/assets/js/demo/gallery.demo.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/5bdf7dbf4cfbc9247c1eae8c/1evuveffc';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
    <!-- Scripts -->

    <script>
        window.addEventListener('errorData', e => {
            e.preventDefault();
            swal({
                title: 'Error',
                text: e.detail.errorData,
                icon: 'error',
                time: '5000',
                position: 'top-end',
                buttons: {
                    cancel: {
                        text: 'Cerrar',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    }
                }
            });
        });
        window.addEventListener('messageData', e => {
            e.preventDefault();
            swal({
                title: 'Proceso Realizado',
                text: e.detail.messageData,
                icon: 'success',
                time: '5000',
                buttons: {
                    cancel: {
                        text: 'Cerrar',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    }
                }
            }).then(function() {
                if (e.detail.refresh == 1) {
                    location.reload();
                }
            });

        });
        window.addEventListener('alert', e => {
            e.preventDefault();
            swal({
                title: 'Error',
                text: e.detail.alert,
                icon: 'error',
                time: '5000',
                position: 'top-end',
                buttons: {
                    cancel: {
                        text: 'Cerrar',
                        value: null,
                        visible: true,
                        className: 'btn btn-default',
                        closeModal: true,
                    }
                }
            });
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>

</html>