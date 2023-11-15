<td>
    <div class="btn-group btn-group-justified uppercase">
        <button type="button" title="Realizar pagos" data-toggle="modal" wire:click="$emit('genPayP',{{ $id }})" data-target="#paymentModal" class="btn-sm text-blue-500">{{ $qty }}</button>
    </div>
</td>