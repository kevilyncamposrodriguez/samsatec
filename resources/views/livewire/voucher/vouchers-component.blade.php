<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.voucher.view')
    @include('livewire.voucher.edit')
    @include('livewire.voucher.newElectronicPurchase')
    @livewire('payment.pay-invoices-component')
    @livewire('product.create-product-component')
    @livewire('product.create-service-component')
    @livewire('product.update-product-component')
    @livewire('product.update-service-component')
    @livewire('provider.create-provider-component')
    <!-- begin panel -->
    <div class="col-xl-12">
        <!-- begin panel -->
        <div class="panel panel-inverse">
            <div class="panel panel-inverse">
                <!-- begin panel-heading -->
                <div class="panel-heading">
                    <h4 class="panel-title">Comprobantes Procesados</h4>
                    @if (!session()->has('npay'))
                    <div class="btn-group m-r-5 m-b-1">
                        <a title="Factura Electronica" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-toggle">
                            <span class="uppercase">Nuevo Comprobante</span>
                            <b class="caret"></b>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right uppercase">
                            @if(Auth::user()->currentTeam->fe)
                            <a class="dropdown-item" title="Nueva Factura Electronica de Compra" data-toggle="modal" data-target="#electronicPurchaseModal" wire:click="newPurchase(1)">Factura Electronica de Compra</a>
                            
                            @endif
                            @if(Auth::user()->currentTeam->plan_id > 1)
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" title="Nueva Factura Interna de Compra" data-toggle="modal" data-target="#electronicPurchaseModal" wire:click="newPurchase(2)">Factura Interna de Compra</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" title="Nueva Orden de Compra" data-toggle="modal" data-target="#electronicPurchaseModal" wire:click="newPurchase(3)">Orden de Compra</a>
                            @endif
                        </div>
                    </div>
                    @endif
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
                    <livewire:voucher.vouchers-table />
                </div>
                <!-- end panel-body -->
            </div>
        </div>
    </div>
</div>