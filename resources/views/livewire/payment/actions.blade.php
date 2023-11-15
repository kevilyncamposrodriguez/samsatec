<td width="15%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#paymentUModal" data-toggle="modal" wire:click="$emit('editPayment',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deletePayment',{{ $id }},{{ $mount }},{{ $id_expense }})" class="btn btn-md text-white" style="background-color: #192743;"><i class="fa fa-trash"></i></button>
    </div>
</td>