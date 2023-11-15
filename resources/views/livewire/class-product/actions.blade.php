<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#classUModal" data-toggle="modal" wire:click="$emit('editClass',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @if($removable)
        <button wire:click="$emit('deleteClass',{{ $id }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
        @endif
    </div>
</td>