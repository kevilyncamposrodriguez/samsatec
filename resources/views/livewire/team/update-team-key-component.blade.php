<div>
    <x-form-section2 submit="update">
        <x-slot name="description">
            {{ __('Datos generados desde el sistema del Ministerio de hacienda ATV para Facturación Eléctronica.') }}{{ ($team->fe) }}
            @if(Auth::user()->currentTeam->plan_id > 1)
            <h5 class="m-r-5 m-b-1"><span class="m-r-5 m-b-1">
                    <label class="text-red" for="fe">Facturación Eléctronica:</label>
                    <input class="m-r-5 m-b-1" type="checkbox" wire:change="is_fe()" id="fe" style="border-radius: 20%; width: 30px; height: 30px; cursor: pointer;" wire:model="fe">
                </span>
            </h5>
            @endif
        </x-slot>
        <x-slot name="form">
            <div class="form-group row" {{ ($team->fe)?'':'hidden' }}>
                <x-label for="user_mh" value="{{ __('Usuario MH') }}" />
                <div class="col-lg-8">
                    <div class="input-group date" id="datetimepicker2">
                        <x-input id="user_mh" type="text" class="form-control" placeholder="Usuario MH" wire:model="user_mh" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="user_mh" class="mt-2" />
                    </div>
                </div>
            </div>
            <div class="form-group row" {{ ($team->fe)?'':'hidden' }}>
                <x-label for="pass_mh" value="{{ __('Contraseña MH') }}" />
                <div class="col-lg-8">
                    <div class="input-group date" id="datetimepicker2">
                        <x-input id="pass_mh" type="text" class="form-control" placeholder="Contraseña MH" wire:model="pass_mh" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="pass_mh" class="mt-2" />
                    </div>
                </div>
            </div>
            <!-- Team Name -->
            <div class="form-group row" {{ ($team->fe)?'':'hidden' }}>
                <x-label for="cryptographic_key" value="{{ __('Llave criptografica') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <x-input name="cryptographic_key" type="file" class="form-control" placeholder="Llave Criptografica" wire:model="cryptographic_key" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="cryptographic_key" class="mt-2" />
                    </div>
                </div>
            </div>
            <!-- Team Name -->
            <div class="form-group row" {{ ($team->fe)?'':'hidden' }}>
                <x-label for="pin" value="{{ __('PIN') }}" />
                <div class="col-lg-8">
                    <div class="input-group date">
                        <x-input id="pin" type="number" class="form-control" placeholder="Pin" wire:model="pin" :disabled="! Gate::check('update', $team)" />
                        <x-input-error for="pin" class="mt-2" />
                    </div>
                </div>
            </div>
        </x-slot>

        @if (Gate::check('update', $team) && $team->fe)
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