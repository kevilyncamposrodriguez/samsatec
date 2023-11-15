<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif

    @livewire('document.edit-document-component')
    @livewire('payment-invoice.pay-invoices-component')
    @livewire('document.send-document-component')
    @include('livewire.document.import')


    <div class="panel panel-inverse">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h1 class="panel-title font-medium uppercase">Documentos Emitidos</h1>
            @if (!session()->has('npay'))
            <a class="btn btn-blue m-r-5 uppercase" title="Importar Documentos" data-toggle="modal" data-target="#documentImportModal">Importar</a>
            <div class="btn-group m-r-5 m-b-1">

                <a title="Factura Electronica" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-toggle">
                    <span class="uppercase">Nuevo Documento</span>
                    <b class="caret"></b>
                </a>
                <div class="dropdown-menu dropdown-menu-right uppercase">
                    @if(Auth::user()->currentTeam->plan_id > 1)
                    <a class="dropdown-item" title="Cotizacion" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','99')">Proforma</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Orden de venta" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','00')">Orden de Venta</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Factura" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','11')">Factura Contingencia</a>
                    <div class="dropdown-divider"></div>
                    @endif
                    @if(Auth::user()->currentTeam->fe)

                    <a class="dropdown-item" title="Tiquete Electronico" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','04')">Tiquete Electronico</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Factura Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','01')">Factura Electronica</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Nota de Debito Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','02')">Nota Electronica de Debito </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Nota de Credito Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','03')">Nota Electronica de Credito </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" title="Factura Electronica de Exportacion" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','09')">Factura Electronica de Exportacion</a>
                    @endif
                </div>

            </div>
            @endif
        </div>
        <!-- begin panel-body -->
        <div class="panel-body">
            <livewire:document.documents-table />
        </div>
        <!-- end panel-body -->
    </div>
</div>