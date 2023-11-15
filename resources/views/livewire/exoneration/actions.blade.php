<td width="10%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#exonerationUModal" data-toggle="modal" wire:click="$emit('editExoneration',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteExoneration',{{ $id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
    </div>
</td>