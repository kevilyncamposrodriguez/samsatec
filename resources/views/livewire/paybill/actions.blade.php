<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#paybillUModal" data-toggle="modal" wire:click="$emit('editPayBill',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deletePayBill',{{ $id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
    </div>
</td>