<td width="10%" class="text-center">
    <div class="btn-group btn-group-justified">
        @if(Auth::user()->currentTeam->plan_id > 2)
        @if($type)
        <button data-toggle="modal" data-target="#productUModal" wire:click="$emit('editProduct',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @else
        <button data-toggle="modal" data-target="#serviceUModal" wire:click="$emit('editService',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @endif
        @else
        <button data-toggle="modal" data-target="#changeStockModal" wire:click="$emit('editInventory',{{ $id }})" class="btn btn-primary btn-md"><i class="fa fa-edit"></i></button>
        @endif
    </div>
</td>