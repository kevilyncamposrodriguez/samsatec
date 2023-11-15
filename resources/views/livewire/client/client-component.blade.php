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
    @livewire('client.create-client-component')
    @include('livewire.client.update')
    @include('livewire.client.import')

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Clientes</h4>
                <div class="panel-heading-btn">
                    <div class="btn-group m-r-5 m-b-1">
                        <a title="Nuevo Cliente" data-toggle="modal" data-target="#clientModal" wire:click.prevent="cancel()" wire:click="$emit('refresh', 1)" class="btn btn-blue">Nuevo Cliente</a>
                        <a href="#" data-toggle="dropdown" class="btn btn-blue dropdown-toggle"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" title="Importar Clientes" wire:click.prevent="cancel()" data-toggle="modal" data-target="#clientImportModal">Importar</a>
                        </div>
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
                <livewire:client.clients-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>