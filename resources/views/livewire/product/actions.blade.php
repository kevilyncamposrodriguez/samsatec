
<td width="10%" class="text-center">
    <div class="btn-group btn-group-justified">
        @if($type)
        <button data-toggle="modal" data-target="#productUModal" wire:click="$emit('editProduct',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @else
        <button data-toggle="modal" data-target="#serviceUModal" wire:click="$emit('editService',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @endif
        <button wire:click="$emit('deleteProduct',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
    </div>
</td>