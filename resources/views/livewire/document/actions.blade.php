<td>
    <div class="btn-group btn-group-justified uppercase">
        @if(file_exists ($path.'/'.$key.'.pdf'))
        <a href="{{ $path.'/'.$key.'.pdf' }}" target="_blank" class="btn btn-md text-white" style="background-color: #192743;"><i title="Vista de Factura" class="fa fa-search"></i></a>
        @endif
        <a title="Mas opciones" href="javascript:;" data-toggle="dropdown" class="btn btn-blue btn-md dropdown-toggle">
            <span>Mas</span>
            <b class="caret"></b>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            @if($type_doc!='00' && $type_doc!='11' && $type_doc!='99')

            @else
            @if (!session()->has('npay'))
            <button class="dropdown-item" data-toggle="modal" data-target="#documentUModal" data-toggle="modal" wire:click="$emit('editDocument',{{ $id }})" class="btn btn-dark btn-sm">Editar Documento</button>
            @if($type_doc=='00' && Auth::user()->currentTeam->plan_id > 1)
            <button class="dropdown-item" type="button" title="Factura Interna" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('generateFC',{{ $id }})" class="btn btn-primary btn-sm">Generar Factura Contingencia</button>
            @endif
            <button class="dropdown-item" type="button" title="Factura Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('generateFE',{{ $id }})" class="btn btn-primary btn-sm">Generar Factura Electronica</button>
            <button class="dropdown-item" type="button" title="Tiquete Electronico" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('generateTE',{{ $id }})" class="btn btn-primary btn-sm">Generar Tiquete Electronico</button>
            @endif
            @endif
            @if(file_exists ($path.'/'.$key.'-Firmado.xml'))
            <a class="dropdown-item" href="{{ $path.'/'.$key.'-Firmado.xml' }}" download="{{ $key }}" title="XML de Factura" class="btn btn-dark btn-sm">Descargar XML</a>
            @endif
            @if(file_exists ($path.'/'.$key.'-R.xml'))
            <a class="dropdown-item" href="{{ $path.'/'.$key.'-R.xml' }}" download="{{ $key }}" title="XML de Respuesta" class="btn btn-primary btn-sm">Descargar XML-R</a>
            @endif
            @if (!session()->has('npay'))
            <button class="dropdown-item" type="button" title="Factura Interna" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('genCopy',{{ $id }}, {{ $type_doc }})" class="btn btn-primary btn-sm">Generar Copia</button>
            @if($type_doc=='04')
            <button class="dropdown-item" type="button" title="Factura Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('genFE',{{ $id }})" class="btn btn-primary btn-sm">Generar Factura Electronica</button>
            @endif
            @if($type_doc=='01' || $type_doc=='04')
            <button class="dropdown-item" type="button" title="Nota de Credito Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('genNC',{{ $id }})" class="btn btn-primary btn-sm">Generar Nota de credito</button>
            @endif
            @endif
            @if(Auth::user()->currentTeam->plan_id > 1)
            @if(($type_doc=='01' || $type_doc=='04' || $type_doc=='11'))
            <button class="dropdown-item" type="button" title="Realizar pagos" data-toggle="modal" data-target="#paymentModal" wire:click="$emit('genPay',{{ $id }})" class="btn btn-primary btn-sm">Pagos</button>
            @endif
            @endif
        </div>
        @if( $state_send == 'enviado')
        <button type="button" data-toggle="modal" data-target="#sendInvoiceModal" wire:click="$emit('chargeSendInvoice','{{ $id_client }}','{{ $id }}')" class="btn btn-sm" style="background-color: #192743;"><i title="Enviado, Click para reenviar!" class="fa fa-paper-plane text-green-darker"></i></button>
        @else
        <button type="button" data-toggle="modal" data-target="#sendInvoiceModal" wire:click="$emit('chargeSendInvoice','{{ $id_client }}','{{ $id }}')" class="btn btn-sm" style="background-color: #192743;"><i title="No enviado, Click para enviar!" class="fa fa-paper-plane text-red-darker"></i></button>
        @endif
        @if($answer_mh == 'procesando' || ($answer_mh == 'Ninguna' && $type_doc!='00' && $type_doc!='99' && $type_doc!='11'))
        <button type="button" wire:click.prevent="$emit('consultStateP',{{ json_encode($key) }})" onclick="this.disabled=true;" class="btn btn-primary btn-sm"><i title="Procesando, Click para actualizar!" class="fa fa-sync-alt text-white"></i></button>
        @endif
        <a href="{{ url('ticketPDF/'.$id) }}" target="_blank" class="btn btn-primary btn-sm"><i title="Imprimir Tiquete" class="fa fa-print text-white"></i></a>

    </div>
</td>