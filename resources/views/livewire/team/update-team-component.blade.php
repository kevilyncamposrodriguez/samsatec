<div>
    @section('title', 'Configuracion')
    <x-form-section2 submit="update">
        <x-slot name="description">
            {{ __('Datos de la compañia.') }}           
        </x-slot>

        <div class="dropdown-menu dropdown-menu-right uppercase">
            <a class="dropdown-item" title="Nueva Factura Electronica de Compra" data-toggle="modal" data-target="#electronicPurchaseModal">Factura Electronica de Compra</a>
        </div>
        <x-slot name="form">
            <div align="center">
                <img src="{{ $logo_url }}" width="200m" wire:model="logo_url">
            </div>

            <div class="form-group row">
                <x-label for="logo" value="{{ __('Logo de la empresa') }}" />
                <div class="col-lg-8">
                    <div class="input-group date" id="logo">
                        <x-input id="logo" type="file" class="form-control" placeholder="Logo de empresa" wire:model="logo" />
                        <button type="button" wire:click.prevent="chargeLogo()" onclick="this.disabled=true;" class="btn btn-primary">Cargar</button>
                        <x-input-error for="logo" class="mt-2" />
                    </div>
                </div>
            </div>
            <!-- Team Name -->
            <div class="form-group row">
                <x-label for="name" value="{{ __('Nombre de la compañia') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <x-input id="name" name="name" type="text" class="form-control" placeholder="Nombre de la compañia" wire:model="name" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="name" class="mt-2" />
                    </div>
                </div>
            </div>
            <!-- Identificacion -->
            <div class="form-group row">
                <x-label for="id_card" value="{{ __('Numero y tipo de identificación') }}" />
                <div class="col-lg-8">
                    <div class="row row-space-10">
                        <div class="col-xs-6 mb-2 mb-sm-0">
                            <x-input id="id_card" type="number" class="form-control" placeholder="Numero de identificación" wire:model="id_card" :disabled="! Gate::check('update', $team)" />
                            <x-input-error for="id_card" class="mt-2" />
                        </div>
                        <div class="col-xs-6">
                            <select class="form-control" wire:model="type_id_card" :disabled="! Gate::check('update', $team)" name="type_id_card" id="type_id_card">
                                <option style="color: black;" value="0">Tipo identifiación</option>
                                <option style="color: black;" value="1">Fisica</option>
                                <option style="color: black;" value="2">Juridica</option>
                                <option style="color: black;" value="3">DIMEX</option>
                                <option style="color: black;" value="4">NITE</option>
                            </select>
                            <x-input-error for="type_id_card" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>            
            <div class="form-group row">
                <x-label for="email_company" value="{{ __('Correo para Información') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <x-input id="email_company" type="text" class="form-control" placeholder="Correo de la compañia" wire:model="email_company" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="email_company" class="mt-2" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <x-label for="phone_company" value="{{ __('Teléfono para Información') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <x-input id="phone_company" type="text" class="form-control" placeholder="Teléfono de la comapañia" wire:model="phone_company" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="phone_company" class="mt-2" />
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <x-label for="accounts" value="{{ __('Cuentas contables para depósitos') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <textarea class="form-control" class="col-lg-12" id="accounts" rows="10" placeholder="Cuentas para mostrar en correos e informes de CXC" wire:model="accounts" :disabled="! Gate::check('update', $team)"></textarea>
                        <x-input-error for="accounts" class="mt-2" />
                    </div>
                </div>
            </div>
        </x-slot>

        @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Actualizado.') }}
            </x-action-message>

            <x-button2>
                {{ __('Actualizar') }}
            </x-button2>
        </x-slot>
        @endif
    </x-form-section2>
</div>