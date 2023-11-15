<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600" x-show="! recovery">
                {{ __('Confirme el acceso a su cuenta ingresando el código de autenticación proporcionado por su aplicación de autenticación.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600" x-show="recovery">
                {{ __('Confirme el acceso a su cuenta ingresando uno de sus códigos de recuperación de emergencia.') }}
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="/two-factor-challenge">
                @csrf

                <div class="mt-4 form-group" x-show="! recovery">
                    <x-labelLogin for="code" value="{{ __('Código') }}" />
                    <x-input id="code" class="form-control form-control-lg" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4 form-group" x-show="recovery">
                    <x-labelLogin for="recovery_code" value="{{ __('Código de recuperación') }}" />
                    <x-input id="recovery_code" class="form-control form-control-lg" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="btn btn-danger btn-block mb-4"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('Usa un código de recuperación') }}
                    </button>

                    <button type="button" class="btn btn-danger btn-block mb-4"
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('Usa un código de autentificaion') }}
                    </button>

                    <x-button >
                        {{ __('Ingresar') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
