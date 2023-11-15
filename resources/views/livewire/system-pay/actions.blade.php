<td width="10%" class="text-center">
    <div class="btn-group btn-group-justified">

        @if($id_pay == null)
        <button class="btn btn-md text-white" style="background-color: #192743;" title="Pagar" data-target="#paySytemPayModal" data-toggle="modal" wire:click="$emit('payFactSystem',{{ $id }})"><i class="fa fa-credit-card"> Pagar</i></button>
        @else
        <button class="btn btn-md text-white" style="background-color: #192743;" title="Mostar Factura" data-target="#viewSytemPayModal" data-toggle="modal" wire:click="$emit('viewFactSystem',{{ $id }})"><i class="fa fa-search"> Ver</i></button>
        @endif
    </div>
</td>