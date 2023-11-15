<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">

        @if($state)
        <button data-toggle="modal" data-target="#CCRModal" wire:click="$emit('closeCR',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i>Cerrar</i></button>
        @else
        <a href="{{ $path.'/'.$id.'.pdf' }}" target="_blank" class="btn btn-md text-white" style="background-color: #192743;"><i title="Vista de Cierre" class="fa fa-search"></i></a>
        @endif
    </div>
</td>