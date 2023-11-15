<x-guest-layout>
    @section('title', 'Restablecimiento de contraseña')
    <x-authentication-card>
        <x-slot name="header">
            <h5 class="text-center">{{ __('¿Olvidaste tu contraseña? No hay problema. Simplemente díganos su dirección de correo electrónico y le enviaremos un enlace para restablecer la contraseña que le permitirá elegir una nueva.') }}</h5>
        </x-slot>
        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="margin-bottom-0">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Enviar enlace de restablecimiento de contraseña') }}
                </x-button>
            </div>
            <div class="m-t-20 text-right">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Regresar al inicio de sesión') }}
                </a>
            </div>
            <br>
            <p class="text-center text-grey-darker mb-0">
                &copy; Grupo Samsa All Right Reserved 2020
                <br> Create by Soluciones k&L
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
