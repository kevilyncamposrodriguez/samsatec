<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#discountUModal" wire:click="$emit('editDiscount',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteDiscount',{{ $id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
    </div>
</td>