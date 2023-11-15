<!-- Modal -->
<div wire:ignore.self class="modal fade" id="clientUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualización de Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-3 col-form-label">Codigo: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input class="form-control" type="text" name="code" id="code" wire:model="code" />
                            @error('code') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Cliente: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name_client" data-parsley-required="true" class="form-control" placeholder="Nombre Cliente" wire:model="name_client" />
                            @error('name_client') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Identifiación y tipo de identificación: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6 mb-2 mb-sm-0">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number" name="id_card" id="id_card" placeholder="Numero Identificación" minlength="9" maxlength="12" wire:model="id_card" />
                                    @error('id_card') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="type_id_card" id="type_id_card" wire:model="type_id_card">
                                        @foreach($type_id_cards as $type_id_card)
                                        <option style="color: black;" value="{{ $type_id_card->id }}">{{ $type_id_card->type }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_id_card') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Dirección: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-4">
                                    <select class="form-control" name="id_province" id="id_province" wire:model="id_province">
                                        <option style="color: black;" value="0">Provincia</option>
                                        @foreach($provinces as $province)
                                        <option style="color: black;" value="{{ $province->id }}">{{ $province->province }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_province') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_canton" id="id_canton" wire:model="id_canton">
                                        <option style="color: black;" value="0">Canton</option>
                                        @foreach($cantons as $canton)
                                        <option style="color: black;" value="{{ $canton->id }}">{{ $canton->canton }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_canton') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_district" id="id_district" wire:model="id_district">
                                        <option style="color: black;" value="0">Distrito</option>
                                        @foreach($districts as $district)
                                        <option style="color: black;" value="{{ $district->id }}">{{ $district->district }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_district') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Otras señas: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="other_signs" data-parsley-required="true" class="form-control" wire:model="other_signs" placeholder="DIreccion exacta" />
                            @error('other_signs') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="emails" class="form-control" data-parsley-type="email" wire:model="emails" placeholder="ejemplo@dominio.com" />
                            @error('emails') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Codigo de pais y Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" wire:model="id_country_code">
                                        <option style="color: black;" value="" disabled selected="true">Cod. país</option>
                                        @foreach($country_codes as $country_code)
                                        @if($country_code->id == 52)
                                        <option selected="true" style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('id_country_code') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-6">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number" name="phone" minlength="8" maxlength="8" wire:model="phone" placeholder="88888888" />
                                    @error('phone') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Condición de venta, Plazo y Monto: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-4">
                                    <select class="form-control" data-parsley-required="true" name="id_sale_condition" id="id_sale_condition" wire:model="id_sale_condition">
                                        @foreach($sale_conditions as $sale_condition)
                                        <option style="color: black;" value="{{ $sale_condition->id }}">{{ $sale_condition->sale_condition }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_sale_condition') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-group col-xs-4 mb-2 mb-sm-0">
                                    <div class="input-group-prepend"><span class="input-group-text">dias</span></div>
                                    <input class="form-control" type="number" name="time" id="time" value="1" min="1" max="99" wire:model="time" />
                                    @error('time') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="input-group col-xs-4 mb-2 mb-sm-0">
                                    <div class="input-group-prepend"><span class="input-group-text">₡</span></div>
                                    <input class="form-control" type="number" {{ ($id_sale_condition != 2)?'disabled':'' }} name="total_credit" id="total_credit" value="0" wire:model="total_credit" />
                                    @error('total_credit') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Moneda y método de pago: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <div class="row row-space-10">
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_currency" wire:model="id_currency">
                                        @foreach($currencies as $currency)
                                        @if($currency->id == 55)
                                        <option selected="true" style="color: black;" value="{{ $currency->id }}">{{ $currency->code." - ".$currency->currency }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $currency->id }}">{{ $currency->currency." - ".$currency->code }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('id_currency') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-xs-6">
                                    <select class="form-control" data-parsley-required="true" name="id_payment_method" id="id_payment_method" wire:model="id_payment_method">
                                        @foreach($payment_methods as $payment_method)
                                        <option style="color: black;" value="{{ $payment_method->id }}">{{ $payment_method->payment_method }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_payment_method') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- end form-group -->
                    @if(Auth::user()->currentTeam->plan_id > 3)
                    <!-- end form-group -->
                    <div class="form-group row">
                        <label class="col-lg-5 col-form-label">Lista de precios preferida: <span class="text-danger"></span></label>
                        <div class="col-lg-7">
                            <select class="form-control" data-parsley-required="true" name="id_price_list" wire:model="id_price_list">
                                <option selected="true" style="color: black;" value="">Por defecto</option>
                                @foreach($allPriceList as $priceList)
                                <option selected="true" style="color: black;" value="{{ $priceList->id }}">{{ $priceList->name }}</option>
                                @endforeach
                            </select>
                            @error('id_price_list') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('clientU_modal_hide', event => {
        $('#clientUModal').modal('hide');
    });
</script>