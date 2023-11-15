<!-- Modal -->
<div wire:ignore.self class="modal fade " id="sellerUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de Vendedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre Vendedor: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="name_seller" data-parsley-required="true" class="form-control" placeholder="Nombre Vendedor" wire:model="name_seller" />
                            @error('name_seller') <span class="text-red-500">{{ $message }}</span>@enderror
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
                        <label class="col-lg-3 col-form-label">Código de pais y Telefono: </label>
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
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Comisión por venta %: </label>\
                        <div class="col-2">
                            <input data-placement="after" placeholder="0" class="form-control" type="number" name="commission" wire:model="commission" />
                        </div>
                        @error('commission') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
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
    window.addEventListener('sellerU_modal_hide', event => {
        $('#sellerUModal').modal('hide');
    });
</script>