<td>
    <div class="btn-group btn-group-justified uppercase">
        @if($state_proccess != 2)
        <button data-toggle="modal" data-target="#documentUModal" data-toggle="modal" wire:click="$emit('editCellar',{{ $id }})" class="btn btn-secondary btn-md"><i class="fa fa-edit"></i></button>
        <a href="{{ url('ticketCeller/'.$id) }}" target="_blank" class="btn btn-primary btn-sm"><i title="Imprimir Tiquete" class="fa fa-print text-white"></i></a>
        @else
        <button data-toggle="modal" data-target="#documentVModal" data-toggle="modal" wire:click="$emit('viewCellar',{{ $id }})" class="btn btn-secondary btn-md"><i class="fa fa-search"></i></button>
        <a href="{{ url('ticketCellerProcessed/'.$id) }}" target="_blank" class="btn btn-primary btn-sm"><i title="Imprimir Tiquete" class="fa fa-print text-white"></i></a>
        @endif
    </div>
</td>