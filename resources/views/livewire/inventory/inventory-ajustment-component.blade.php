<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @push('css')
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    @endpush
    @include('livewire.inventory.managerInventory')
    @include('livewire.inventory.traslateInventory')
    @include('livewire.inventory.viewTraslateInventory')
    @livewire('inventory.transform-inventory-component')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Ajustes de Inventario</h4>
                <div class="panel-heading-btn">
                    <div class="m-r-5 m-b-1">
                        <button class="btn btn-blue m-r-5" {{(Auth::user()->currentTeam->bo_inventory)?'':''}} title="Traslado de Inventario" data-toggle="modal" data-target="#transformModal" wire:click="$emit('newTransform')"> Transformar</button>
                        <button class="btn btn-blue m-r-5" {{(Auth::user()->currentTeam->bo_inventory)?'':''}} title="Traslado de Inventario" data-toggle="modal" data-target="#transferIModal" wire:click="newTransfer()"> Traslado</button>
                        <button class="btn btn-blue m-r-5" title="Ajuste de Inventario" data-toggle="modal" data-target="#AIModal" wire:click="newAI()">Nuevo Ajuste</button>
                    </div>
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
                <livewire:inventory.inventory-ajustment-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>