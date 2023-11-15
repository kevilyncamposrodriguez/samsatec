@livewire('document.new-document-component')
@livewire('client.create-client-component')
@livewire('product.create-product-component')
@livewire('product.create-service-component')
@if (!session()->has('npay'))
<div style="text-align: right; margin-top: -19px;">
    <div class="uppercase">
        @if(Auth::user()->currentTeam->plan_id > 1)
        <button type="button" title="Factura" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','11')" class="btn btn-primary btn-sm text-white m-t-4 m-b-4">Factura Contingencia</button>
        @endif
        @if(Auth::user()->currentTeam->fe)
        <button type="button" title="Factura Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','01')" class="btn btn-secondary btn-sm text-white m-t-4 m-b-4">Factura Electronica</button>
        <button type="button" title="Tiquete Electronico" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','04')" class="btn btn-primary btn-sm m-t-4 m-b-4">Tiquete Electronico</button>
        @endif
    </div>
</div>
@endif
