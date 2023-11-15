<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#familyUModal" wire:click="$emit('editFamily',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteFamily',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
    </div>
</td>