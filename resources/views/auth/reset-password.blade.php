<x-guest-layout>
    @section('title', 'Restablecer contraseña')
    <x-authentication-card>
         <x-slot name="header">
        <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
        </x-slot>
        <x-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="margin-bottom-0">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="form-group m-b-20">
                <x-label for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="form-group m-b-20">
                <x-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="form-group m-b-20">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                <x-input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            
            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Restablecer contraseña') }}
                </x-button>
            </div>
            <div class="m-t-20 text-right">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Regresar al inicio de sesión') }}
                </a>
            </div>
            <hr />
            <p class="text-center text-grey-darker mb-0">
                &copy; Grupo Samsa All Right Reserved 2020
                <br> Create by Soluciones k&L
            </p>
        </form>
    </x-authentication-card>
</x-guest-layout>
