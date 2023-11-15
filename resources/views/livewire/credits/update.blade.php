<!-- Modal -->
<div wire:ignore.self class="modal fade " id="creditUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de Creditos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-2 col-form-label">Número: <span class="text-danger"></span></label>
                        <div class="col-lg-3 col-xl-3">
                            <input class="form-control" type="text" name="credit_number" id="credit_number" wire:model="credit_number" />
                            @error('credit_number') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Financiera: <span class="text-danger"></span></label>
                        <div class="col-lg-5 col-xl-5">
                            <input type="text" name="financial_entity" class="form-control" placeholder="Nombre de la financiera" wire:model="financial_entity" />
                            @error('financial_entity') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Fecha de contrato: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="date" name="date_issue" class="form-control" placeholder="Fecha" wire:model="date_issue" />
                            @error('date_issue') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Día de pago: <span class="text-danger"></span></label>
                        <div class="col-lg-2 col-xl-2">
                            <input type="number" name="pay_day" class="form-control" placeholder="Dia" min="1" max="31" wire:model="pay_day" />
                            @error('pay_day') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periodo: <span class="text-danger"></span></label>
                        <div class="input-group col-xs-4 mb-4 mb-sm-0">
                            <div class="input-group-prepend"><span class="input-group-text">Meses</span></div>
                            <input class="form-control" type="number" name="period" id="period" value="1" min="1" wire:model="period" />
                            @error('period') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Moneda: </label>
                        <div class="col-4">
                            <select class="form-control " name="currencyU" id="currencyU">
                                @foreach($allCurrencies as $currency)
                                @if($currency->code=='CRC')
                                <option style="color: black;" selected='true' value="{{ $currency->code }}">{{ $currency->code.' - '.$currency->currency }}</option>
                                @else
                                <option style="color: black;" value="{{ $currency->code }}">{{ $currency->code.' - '.$currency->currency }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('currencyU') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">

                        <label class="col-lg-2 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-xs-4 mb-4 mb-sm-0">
                            <input class="form-control" type="number" name="credit_mount" id="credit_mount" wire:model="credit_mount" />
                            @error('credit_mount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Tasa %: <span class="text-danger"></span></label>
                        <div class="col-xs-4 mb-2 mb-sm-0">
                            <input class="form-control" type="number" name="credit_rate" id="credit_rate" wire:model="credit_rate" />
                            @error('credit_rate') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">

                        <label class="col-lg-2 col-form-label">Gastos Formalizacion: <span class="text-danger"></span></label>
                        <div class="col-xs-4 mb-4 mb-sm-0">
                            <input class="form-control" type="number" name="formalization_expenses" id="formalization_expenses" wire:model="formalization_expenses" />
                            @error('formalization_expenses') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <!-- begin custom-checkbox -->
                        <label class="col-lg-2 col-form-label">Es tributado?: <span class="text-danger"></span></label>
                        <div class="col-xs-4 mb-4 mb-sm-0">
                            <div class="m-b-10 custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="taxed" wire:model="taxed">
                                <label class="custom-control-label" for="taxed"></label>
                            </div>
                        </div>

                        <!-- end custom-checkbox -->
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" wire:click.prevent="resetInputFields()" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary" data-dismiss="modal">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('creditU_modal_hide', event => {
        $('#creditUModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('#currencyU').select2();
        $('#currencyU').on('change', function(e) {
            livewire.emit('changeCurrency', $('#currencyU').select2("val"))
        });
    });
    //manejo de clientes
    window.addEventListener('currencyU-updated', event => {
        $("#currencyU").select2().val(event.detail.newValue).trigger('change.select2');
    })
</script>