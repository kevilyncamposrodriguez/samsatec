<x-form-section2 submit="updateTeamKey">

    <x-slot name="description">
        {{ __('Lave y pin generados desde el sistema del Ministerio de hacienda ATV.') }}
    </x-slot>

    <x-slot name="form">

        <!-- Team Name -->        
        <div class="form-group row">
            <x-label for="cryptographic_key" value="{{ __('Llave criptográfica') }}" />
            <div class="col-lg-8">
                <div class="input-group date" >
                    <x-input id="cryptographic_key" type="file"
                             class="form-control" placeholder="Llave Criptográfica"
                             wire:model="cryptographic_key"
                             :disabled="!Gate::check('update', $team)" />
                    <x-input-error for="cryptographic_key" class="mt-2" />
                </div>
            </div>
        </div>
         <!-- Team Name -->        
        <div class="form-group row">
            <x-label for="pin" value="{{ __('PIN') }}" />
            <div class="col-lg-8">
                <div class="input-group date" >
                    <x-input id="pin" type="number"
                             class="form-control" placeholder="Pin"
                             wire:model.defer="state.pin"
                             :disabled="! Gate::check('update', $team)" />
                    <x-input-error for="pin" class="mt-2" />
                </div>
            </div>
        </div>
    </x-slot>

    @if (Auth::user()->teams->find($team->id)->membership->role == "admin")
    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Actualizado.') }}
        </x-action-message>
        <div class="login-buttons">
                <x-button >
                {{ __('Actualizar') }}
                </x-button>
            </div>
    </x-slot>
    @endif
</x-form-section2>

