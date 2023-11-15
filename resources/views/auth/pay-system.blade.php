<x-guest-layout>
    @section('title', 'Registro')

    <x-authentication-card2>
        <x-slot name="header">
            <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
        </x-slot>

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif
        <form method="POST" action="{{ route('register') }}" class="margin-bottom-0">
            @csrf
            <h5 class="text-center"><strong>Registro de Usuario</strong></h5>
            <label class="control-label" for="name">{{ __('Nombre de usuario') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="text" id="name" class="form-control" name="name" :value="old('name')" placeholder="Nombre de usuario" />
                    <x-input-error for="name"></x-input-error>
                </div>
            </div>

            <label class="control-label" for="email">{{ __('Correo') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="email" id="email" class="form-control" name="email" :value="old('email')" placeholder="Correo Electronico" />
                    <x-input-error for="email"></x-input-error>
                </div>
            </div>

            <label class="control-label" for="password">{{ __('Contraseña') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="password" id="password" class="form-control" name="password" placeholder="{{ __('Contraseña') }}" autocomplete="new-password" />
                    <x-input-error for="password"></x-input-error>
                </div>
            </div>

            <label class="control-label" for="password_confirmation">{{ __('Confirmar contraseña') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="{{ __('Confirmar contraseña') }}" autocomplete="new-password" />
                    <x-input-error for="password"></x-input-error>
                </div>
            </div>
            <label class="control-label" for="referredby">{{ __('Referido por') }}</label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="text" id="referredby" class="form-control" name="referredby" placeholder="{{ __('Referido por') }}" value="{{ Cookie::get('referral') }}"  />
                    <x-input-error for="referredby"></x-input-error>
                </div>
            </div>

            <h5 class="text-center"><strong>Registro de pago</strong></h5>
            <label class="control-label" for="idcard_count">{{ __('Cedula del propietario de la cuenta') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="text" id="idcard_count" class="form-control" name="idcard_count" placeholder="Cedula del propietario" />
                    <x-input-error for="idcard_count"></x-input-error>
                </div>
            </div>
            <label class="control-label" for="name_count">{{ __('Nombre completo del propietario de la cuenta') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="text" id="name_count" class="form-control" name="name_count" placeholder="Nombre del propietario" />
                    <x-input-error for="name_count"></x-input-error>
                </div>
            </div>
            <label class="control-label" for="iban">{{ __('Numero de cuenta (IBAN)') }}<span class="text-danger">*</span></label>
            <div class="row m-b-15">
                <div class="col-md-12">
                    <input type="text" id="iban" class="form-control" name="iban" placeholder="Cuenta IBAN" />
                    <x-input-error for="iban"></x-input-error>
                </div>
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
                    {{ __('Pagar y Registrar') }}
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
    </x-authentication-card2>
</x-guest-layout>