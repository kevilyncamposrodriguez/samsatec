<td width="20%" class="text-center">
    <div class="btn-group btn-group-justified">

        @if(file_exists( $ruta.'/'.$key.'.pdf' ))
        <a href="{{ $ruta.'/'.$key.'.pdf' }}" target="_blank" class="btn btn-md text-white" style="background-color: #192743;"><i title="Vista del Documento" class="fa fa-search"></i></a>
        @endif
        <a title="Mas opciones" href="javascript:;" data-toggle="dropdown" class="btn btn-blue btn-md dropdown-toggle">
            <span>Mas</span>
            <b class="caret"></b>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            @if(file_exists( $ruta.'/'.$key.'.xml' ))
            <a href="{{ $ruta.'/'.$key.'.xml' }}" download="{{ $key }}" title="XML de Factura" class="dropdown-item" class="btn btn-dark btn-sm">Descargar XML</a>
            @endif
            @if(file_exists( $ruta.'/'.$key.'-R.xml' ))
            <a href="{{ $ruta.'/'.$key.'-R.xml' }}" download="{{ $key }}" title="XML de Respuesta" class="dropdown-item" class="btn btn-dark btn-sm">Descargar XML-R</a>
            @endif
            @if (!session()->has('npay'))
            @if($type == 1)
            <button data-toggle="modal" data-target="#viewExpenseModal" wire:click="$emit('viewVoucher','{{ $key }}','{{ $id }}')" data-toggle="modal" class="dropdown-item" class="btn btn-dark btn-sm">Editar</button>
            @else
            <button data-toggle="modal" data-target="#electronicPurchaseModalU" wire:click="$emit('editVoucher','{{ $id }}')" data-toggle="modal" class="dropdown-item" class="btn btn-dark btn-sm">Editar</button>
            @endif
            @endif
            @if($type == 1 || $type == 2)
            @if(Auth::user()->currentTeam->plan_id > 1)
            <button class="dropdown-item" type="button" title="Realizar pagos" data-toggle="modal" data-target="#paymentModal" wire:click="$emit('genPayP',{{ $id }})" class="btn btn-primary btn-sm">Pagos</button>
            @endif
            @endif
            @if (!session()->has('npay'))
            @if($type == 3)
            <button class="dropdown-item" type="button" title="Generar Factura Electrónica de Compra" data-toggle="modal" data-target="#electronicPurchaseModal" wire:click="$emit('generateFEC',{{ $id }})" class="btn btn-primary btn-sm">Generar Factura Electrónica de Compra</button>
            @if(Auth::user()->currentTeam->plan_id > 1)
            <button class="dropdown-item" type="button" title="Generar Factura Interna de Compra" data-toggle="modal" data-target="#electronicPurchaseModal" wire:click="$emit('generateFIC',{{ $id }})" class="btn btn-primary btn-sm">Generar Factura Interna de Compra</button>
            @endif
            @endif
            @endif
        </div>
    </div>

</td>