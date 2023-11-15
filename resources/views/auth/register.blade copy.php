<x-guest-layout>
    @section('title', 'Registro')

    <x-authentication-card>
        <x-slot name="header" >
            <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
        </x-slot>
        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="margin-bottom-0">
            @csrf

            <div class="form-group m-b-10">
                <x-labelLogin for="name" value="{{ __('Nombre de usuario') }}" />
                <x-input id="name" class="form-control form-control-lg" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="form-group m-b-10">
                <x-labelLogin for="email" value="{{ __('Correo') }}" />
                <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="form-group m-b-10">
                <x-labelLogin for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="form-group m-b-20">
                <x-labelLogin for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                <x-input id="password_confirmation" class="form-control form-control-lg" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <x-jet-label for="terms">
                    <div class="flex">
                        <div class="row">
                            <x-jet-checkbox class="ml-4 mt-1" name="terms" id="terms" />

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>

                    </div>
                </x-jet-label>
            </div>
            @endif
            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Registrar') }}
                </x-button>
            </div>
            <div class="m-t-20 text-right">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Ya estoy registrado!') }}
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