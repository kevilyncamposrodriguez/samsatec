<x-guest-layout>
    @section('title', 'Verificación')
    <x-authentication-card>
        <x-slot name="header">
            <h5 class="">Gracias por registrarte! Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar? Si no recibió el correo electrónico, con gusto le enviaremos otro.</h5>
        </x-slot>
        <x-validation-errors class="mb-4" />

        @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro..') }}
        </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}" class="margin-bottom-0">
                @csrf
                <div class="login-buttons">
                    <x-button >
                        {{ __('Reenviar correo de verificación') }}
                    </x-button>
                </div>
            </form>
            <br>
            <form method="POST" action="{{ route('logout') }}" class="margin-bottom-0">
                @csrf
                <x-button class="Button Button-red">
                    {{ __('Salir') }}
                </x-button>
            </form>
        </div>
        <hr />
        <p class="text-center text-grey-darker mb-0">
            &copy; Grupo Samsa All Right Reserved 2020
            <br> Create by Soluciones k&L
        </p>
    </x-authentication-card>
</x-guest-layout>
k.camposr05@gmail.com