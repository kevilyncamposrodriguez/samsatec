<!-- Modal -->
<div wire:ignore.self class="modal fade " id="changeTaxUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Impuesto y Exoneración</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <!-- begin form-group -->
                <div class="form-group row">
                    <div class="col-md-4">
                        <strong> Lista de Impuestos:</strong>
                        <select class="form-control" name="id_tax" wire:change="changeTax()" wire:model="id_tax">
                            @foreach($allTaxes as $tax)
                            <option style="color: black;" value="{{ $tax->id }}">{{ $tax->description }}</option>
                            @endforeach
                        </select>
                        @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>% Impuesto</strong>
                                <input class="form-control" type="number" name="percent_tax" min="0" disabled wire:model="percent_tax" />
                            </div>
                            <div class="col-md-6">
                                <strong>Monto Impuesto</strong>
                                <input class="form-control" type="number" name="tax" min="0" disabled wire:model="tax" />
                            </div>
                        </div>
                        @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <strong> Lista de Exoneraciones:</strong>
                        <select class="form-control" name="id_exoneration" wire:change="changeExo()" wire:model="id_exoneration">
                            <option style="color: black;" value="">Seleccionar ...</option>
                            @foreach($allExonerations as $exo)
                            <option style="color: black;" value="{{ $exo->id }}">{{ $exo->description }}</option>
                            @endforeach
                        </select>
                        @error('id_exoneration') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>% Exoneracion</strong>
                                <input class="form-control" type="number" name="percent_exo" disabled min="0" wire:model="percent_exo" />
                            </div>
                            <div class="col-md-6">
                                <strong>Monto Exoneración</strong>
                                <input class="form-control" type="number" name="exoneration" disabled min="0" wire:model="exoneration" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="saveTax()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>