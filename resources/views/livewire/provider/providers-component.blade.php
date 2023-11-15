<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.provider.import')
    @livewire('provider.create-provider-component')
    @include('livewire.provider.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Proveedores</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-blue m-r-5" title="Importar Proveedores" data-toggle="modal" data-target="#providerImportModal"><i class="fa fa-plus"> Importar</i></button>
                    <button class="btn btn-md  btn-blue" title="Nuevo Proveedor" wire:click.prevent="cancel()" data-toggle="modal" data-target="#providerModal"><i class="fa fa-plus"> Nuevo Proveedor</i></button>
                </div>
            </div>
            <!-- end panel-heading -->
            @if (session()->has('message'))
            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                <div class="flex">
                    <div>
                        <p class="text-sm">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
            @endif
            <!-- begin panel-body -->
            <div class="panel-body">
                <livewire:provider.providers-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>