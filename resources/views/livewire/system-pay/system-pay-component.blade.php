<div>
    @section('title', 'Pay Membership')
    
    <!-- begin brand -->
    <div class="login-header" style=" margin-top: 55px;">
        <div class="text-center">
            <span class=""><img src="/assets/img/logo-auth.png" style="margin: auto;" width="100" alt="Samsa App" /></span>
        </div>
    </div>
    <!-- end brand -->
    <!-- begin news-feed -->
    <div class="ml-20 mr-20 mb-2">
        <form>
            <div class="row mt-4" style="background: white;">
                <div class="text-center mt-4" style="margin: auto;">
                    <h2 class="text-center"><strong>Para obtener tu producto favor seguir los siguientes 3 pasos.</strong></h2>
                </div>

                <div class="col-md-7 mt-4 mb-4">
                    <h5 class="text-center text-black"><strong>1. Verificar pedido</strong></h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center" width="60%">{{ __('Plan seleccionado') }}</th>
                                <th class="text-center" width="20%">{{ __('Cant. Meses') }}</th>
                                <th class="text-center" width="20%">{{ __('Monto') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="gradeU">
                                <td width="60%">
                                    <div class="col-md-12">
                                        <select class="form-control" name="plan" id="plan" wire:model="plan">
                                            @foreach($allPlans as $p)
                                            <option style="color: black;" value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error for="plan"></x-input-error>
                                    </div>
                                </td>
                                <td width="20%" class="text-center "><input type="number" id="qty" class="form-control" name="qty" wire:model="qty" /></td>
                                <td width="20%" class="text-center ">{{ number_format($monto  ,2,'.',',')}}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th class="text-center ">Descuento</th>
                                <th class="text-center ">{{ number_format($discount  ,2,'.',',')}}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="text-center ">IVA</th>
                                <th class="text-center ">{{ number_format($iva  ,2,'.',',')}}</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th class="text-center ">Total</th>
                                <th class="text-center ">{{ number_format($total  ,2,'.',',')}}</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="mt-4">
                        <h5 class="text-center text-black"><strong>3. Registro de pago</strong></h5>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="text-black" for="type_idcard">{{ __('Tipo de Cédula') }}<span class="text-danger">*</span></label>
                                <select class="form-control" name="type_idcard" id="type_idcard" wire:model="type_idcard">
                                    <option style="color: black;" value="0">Cédula nacional</option>
                                    <option style="color: black;" value="1">DIMEX</option>
                                    <option style="color: black;" value="2">Pasaporte</option>
                                    <option style="color: black;" value="3">Licencia de conducir</option>
                                    <option style="color: black;" value="4">Identificación del Gobierno</option>
                                    <option style="color: black;" value="5">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="text-black" for="idcard_count">{{ __('Cedula del propietario') }}<span class="text-danger">*</span></label>
                                <div class="row m-b-15">
                                    <div class="col-md-12">
                                        <input type="text" id="idcard_count" class="form-control" wire:model="idcard_count" name="idcard_count" placeholder="Cedula del propietario" />
                                        <x-input-error for="idcard_count"></x-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class=" text-black" for="name_count">{{ __('Nombre completo del propietario de la cuenta') }}<span class="text-danger">*</span></label>
                                <div class="row m-b-15">
                                    <div class="col-md-6">
                                        <input type="text" id="name_count" class="form-control" name="name_count" placeholder="Nombre " wire:model="name_count" />
                                        <x-input-error for="name_count"></x-input-error>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" id="lastname_count" class="form-control" name="lastname_count" placeholder="Apellidos" wire:model="lastname_count" />
                                        <x-input-error for="lastname_count"></x-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="control-label text-black" for="iban">{{ __('Numero de cuenta (IBAN)') }}<span class="text-danger">*</span></label>
                                <div class="row m-b-15">
                                    <div class="col-md-12">
                                        <input type="text" id="iban" class="form-control" name="iban" placeholder="Cuenta IBAN" wire:model="iban" />
                                        <x-input-error for="iban"></x-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-6">
                                <x-jet-checkbox name="fe" id="fe" wire:model="fe" />
                                <label class="control-label text-black" for="fe"> ¿Desea Factura electrónica?</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 ">
                    <!-- begin right-content -->
                    <div class="right-content mt-4">
                        <!-- begin login-content -->
                        <div class="login-content">
                            <x-slot name="header">
                                <h5 class="text-center">Sistema de administración de inventario con facturación electrónica.</h5>
                            </x-slot>

                            @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                            @endif

                            @csrf
                            <h5 class="text-center"><strong>2. Registro de Usuario</strong></h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label" for="name">{{ __('Nombre de usuario') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="text" id="name" class="form-control" name="name" :value="old('name')" wire:model="name" placeholder="Nombre de usuario" />
                                            <x-input-error for="name"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="email">{{ __('Correo') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="email" id="email" class="form-control" name="email" :value="old('email')" wire:model="email" placeholder="Correo Electronico" />
                                            <x-input-error for="email"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="password">{{ __('Contraseña') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="password" id="password" class="form-control" name="password" wire:model="password" placeholder="{{ __('Contraseña') }}" autocomplete="new-password" />
                                            <x-input-error for="password"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="password_confirmation">{{ __('Confirmar contraseña') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="password" id="password_confirmation" class="form-control" wire:model="password_confirmation" name="password_confirmation" placeholder="{{ __('Confirmar contraseña') }}" autocomplete="new-password" />
                                            <x-input-error for="password"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="phone">{{ __('Telefono') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="phone" id="phone" class="form-control" name="phone" :value="old('phone')" wire:model="phone" placeholder="Telefono" />
                                            <x-input-error for="phone"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label" for="referredby">{{ __('Referido por (Opcional)') }}</label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="text" id="referredby" class="form-control" name="referredby" wire:model="referredby" placeholder="{{ __('Referido por') }}" value="{{ Cookie::get('referral') }}" />
                                            <x-input-error for="referredby"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-2">
                                <x-jet-label for="terms">
                                    <div class="flex">
                                        <x-jet-checkbox class="mt-1 mr-1" name="terms" id="terms" wire:model="terms" />
                                        <div>
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-jet-label>
                            </div>
                            @endif
                            <div class="flex items-center justify-end mt-4">
                                <x-button wire:click.prevent="register()" onclick="this.disabled=true;">
                                    {{ __('Pagar y Registrar') }}
                                </x-button>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button wire:click.prevent="registerFree()" onclick="this.disabled=true;" style="background-color: #192743;">
                                    {{ __('Probar por 30 días') }}
                                </x-button>

                            </div>
                            <div class="text-center flex items-center">
                                <span>¡Los datos de pago no son necesarios!</span>
                            </div>
                            <div class="m-t-20 text-right">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                    {{ __('Ya estoy registrado!') }}
                                </a>
                            </div>

                        </div>
                        <!-- end login-content -->
                    </div>
                </div>
                <div class=" col-12 text-center text-grey-darker mb-2 mt-2">
                    <p>
                        &copy; Grupo Samsa All Right Reserved 2020
                        <br> Create by Soluciones k&L
                    </p>
                </div>
            </div>
        </form>
    </div>
    <!-- end news-feed -->
</div>