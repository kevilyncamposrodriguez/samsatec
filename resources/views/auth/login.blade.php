
<x-guest-layout>
    @section('title', 'Inicio de sesión')
    <x-authentication-card>        
        <x-slot name="header">
        <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
        </x-slot>
        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="margin-bottom-0">
            @csrf

            <div class="form-group m-b-20">
                <x-jet-label for="email" value="{{ __('Correo') }}" />
                
                 <x-input id="email" class="form-control form-control-lg" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="form-group m-b-20">
                <x-jet-label for="password" value="{{ __('Contraseña') }}" />
                <x-input id="password" class="form-control form-control-lg" type="password" name="password" required autocomplete="current-password" />
            </div>

            <dl class="row m-b-20">
                <dt class="text-inverse col-6 text-truncate">
                    <div class="checkbox checkbox-css">
                        <input type="checkbox" id="remember_me" name="remember" /> 
                        <label for="remember_me">
                            Recordarme
                        </label>
                    </div>
                </dt>
                <dd class="col-6 text-truncate text-right">
                    <div class="checkbox checkbox-css">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('No recuerda su contraseña?') }}
                        </a>
                        @endif
                    </div>
                </dd>
            </dl>

            <div class="login-buttons">
                <x-button >
                    {{ __('Ingresar') }}
                </x-button>
            </div>
            <div class="m-t-20 text-right">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ 'membership',1 }}">
                    {{ __('¿Aun no estás registrado?') }}
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

