<td width="10%" class="text-center">
    <div class="btn-group btn-group-justified">
        <button data-toggle="modal" data-target="#creditUModal" data-toggle="modal" wire:click="$emit('editCredit',{{ $id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
        <button wire:click="$emit('deleteCredit',{{ $id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
        <button type="button" title="Control de cuotas" data-toggle="modal" data-target="#paymentModal" wire:click="$emit('genPayCredit',{{ $id }})" class="btn btn-primary btn-sm">Cuotas</button>
    </div>
</td>