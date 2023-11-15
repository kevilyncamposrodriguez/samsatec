<!-- Modal -->
<div wire:ignore.self class="modal fade " id="changeDiscountUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Descuento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <!-- begin form-group -->
                <div class="form-group row">
                    <div class="col-md-4">
                        <strong> Lista de descuentos:</strong>
                        <select class="form-control" name="discount" wire:click="changeDiscount()" wire:model="id_discount">
                            <option style="color: black;" value="">Seleccionar ...</option>
                            @foreach($allDiscounts as $discount)
                            <option style="color: black;" value="{{ $discount->id }}">{{ $discount->nature }}</option>
                            @endforeach
                        </select>
                        @error('id_discount') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Monto Descuento</strong>
                                <input class="form-control" type="number" name="discount" id="discount" wire:change="changeDiscount()" min="0" wire:model="discount" />
                            </div>
                            <div class="col-md-6">
                                <strong>% Descuento</strong>
                                <input class="form-control" type="number" name="percent_discount" wire:change="changeDiscountP()" min="0" id="percent_discount" wire:model="percent_discount" />
                            </div>
                        </div>
                        @error('discount') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="saveDiscount()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>