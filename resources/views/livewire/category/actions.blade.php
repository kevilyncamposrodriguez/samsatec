<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#categoryUModal" data-toggle="modal" wire:click="$emit('editCategory',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteCategory',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
    </div>
</td>