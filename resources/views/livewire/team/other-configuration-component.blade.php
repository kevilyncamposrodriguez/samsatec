<div>
    @if(Auth::user()->currentTeam->plan_id ==4)
    @section('title', 'Configuracion')
    <x-form-section2 submit="updateConfiguration">
        <x-slot name="description">
            {{ __('Otras configuraciones.') }}
        </x-slot>
        <x-slot name="form">
            <div class="form-group row">
                <x-label class="col-lg-8" for="bo_inventory" value="Deseas dividir el inventario por sucursal? :" />
                <div class="col-lg-2">
                    <input class="m-r-5 m-b-1" type="checkbox" name="bo_inventory" style="border-radius: 20%; width: 30px; height: 30px; cursor: pointer;" wire:model="bo_inventory">
                    <x-input-error for="bo_inventory" class="mt-2" />
                </div>
            </div>
            <div class="form-group row">
                <x-label class="col-lg-8" for="cash_register" value="Activar apertura y cierre de caja? :" />
                <div class="col-lg-2">
                    <input class="m-r-5 m-b-1" type="checkbox" name="cash_register" style="border-radius: 20%; width: 30px; height: 30px; cursor: pointer;" wire:model="cash_register">
                    <x-input-error for="cash_register" class="mt-2" />
                </div>
            </div>
        </x-slot>
        <x-slot name="actions">
            <x-action-message class="mr-3" on="saved">
                {{ __('Actualizado.') }}
            </x-action-message>

            <x-button2>
                {{ __('Actualizar') }}
            </x-button2>
        </x-slot>
    </x-form-section2>
    @endif
</div>