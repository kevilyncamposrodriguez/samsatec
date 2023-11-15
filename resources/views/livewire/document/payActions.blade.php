<td>
    <div class="btn-group btn-group-justified uppercase">
        <button type="button" title="Realizar pagos" data-toggle="modal" data-target="#paymentModal" wire:click="$emit('genPay',{{ $id }})" class="btn-sm text-blue-500">{{ $qty }}</button>
    </div>
</td>