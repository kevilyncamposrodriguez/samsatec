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
    @livewire('product.create-product-component')
    @livewire('product.create-service-component')
    @livewire('product.update-product-component')
    @livewire('product.update-service-component')
    @include('livewire.product.import')

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Productos y Servicios</h4>
                <div class="panel-heading-btn">
                    
                    <div class="m-r-5 m-b-1">
                        <button class="btn btn-blue m-r-5" title="Importar Productos" data-toggle="modal" data-target="#productImportModal"><i class="fa fa-plus"> Importar</i></button>
                        <a title="Factura Electronica" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-toggle">
                            <span>Nuevo</span>
                            <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" title="Nuevo Producto" data-toggle="modal" data-target="#productModal" wire:click.prevent="newProduct(1)" wire:click="$emit('refresh', 1)" class="btn btn-blue">Producto</a>
                            <a class="dropdown-item" title="Nuevo Servicio" data-toggle="modal" data-target="#serviceModal" wire:click.prevent="newProduct(0)" wire:click="$emit('refresh', 1)" class="btn btn-blue">Servicio</a>
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
                <livewire:product.products-table />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>