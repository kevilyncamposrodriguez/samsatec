<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>Samsatec | @yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="shortcut icon" href="/assets/img/logo.png" type="image/x-icon">
    <link rel="icon" href="/assets/img/logo.png" type="image/x-icon">
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <!-- Styles -->
    @livewireStyles
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link href="/assets/css/apple/app.min.css" rel="stylesheet" />

    <!-- ================== END BASE CSS STYLE ================== -->
    <title>{{ config('app.name', 'Samsatec') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    @stack('css')

</head>

<body style="background:rgba(0, 0, 0, 0.7) url(/assets/img/fond.jpg);background-blend-mode: darken;">
    <div class="font-sans text-gray-900 " >
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
    <script src="/assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
    <!-- ================== END PAGE LEVEL JS ================== -->
    @livewireScripts
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
        window.addEventListener('successRegister', e => {
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
                    location.href="/login";
                }
            });

        });
    </script>
</body>

</html>