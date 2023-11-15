<x-app-layout>
    <div class="text-center mb-3">
        <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
        <h2 class=""> Conexión Banco Ridivi </h2>
        <form class="form-horizontal form-bordered">
            <div class="form-group row">
                <label class="col-lg-1 col-form-label">Usuario: <span class="text-danger"></span></label>
                <div class="col-lg-2 col-xl-2">
                    <input class="form-control" type="text" name="ridivi_username" id="ridivi_username" placeholder="Usuario de conexión para ridivi" wire:model="ridivi_username" />
                    @error('ridivi_username') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
                <label class="col-lg-1 col-form-label">Contraseña: <span class="text-danger"></span></label>
                <div class="col-lg-2 col-xl-2">
                    <input class="form-control" type="text" name="ridivi_pass" id="ridivi_pass" placeholder="Usuario de conexión para ridivi" wire:model="ridivi_pass" />
                    @error('ridivi_pass') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>
        </form>
        <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Guardar</button>
        <button type="button" wire:click.prevent="conection()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Provar conexión</button>
    </div>
</x-app-layout>