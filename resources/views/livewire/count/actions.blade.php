<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        @if($id_count!='')
        <button data-toggle="modal" data-target="#countUModal" wire:click="$emit('editCount',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteCount',{{ $id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
        @endif
    </div>
</td>