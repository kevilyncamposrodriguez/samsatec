<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#lotUModal" data-toggle="modal" wire:click="$emit('editLot',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteLot',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
    </div>
</td>