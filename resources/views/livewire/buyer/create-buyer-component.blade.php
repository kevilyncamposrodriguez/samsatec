<div>
    
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="buyerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Creación de Comprador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-bordered">
                        @csrf
                        <!-- begin form-group -->
                        <div class="form-group row m-b-10">
                            <label class="col-lg-3 col-form-label">Proveedor: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" name="id_provider" wire:model="id_provider" hidden>
                                <input class="form-control" type="text" name="name_provider" list="providers" placeholder="Ninguno" wire:model="name_provider">
                                <datalist id="providers">
                                    @foreach($allProviders as $provider)
                                    <option style="color: black;">{{ $provider->name_provider }}</option>
                                    @endforeach
                                </datalist>
                                @error('id_provider') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        <!-- begin form-group -->
                        <div class="form-group row m-b-10">
                            <label class="col-lg-3 col-form-label">Nombre Comprador: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" name="name_buyer" data-parsley-required="true" class="form-control" placeholder="Nombre Comprador" wire:model="name_buyer" />
                                @error('name_buyer') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Identifiacion y tipo de identificacion: <span class="text-danger"></span></label>
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
                            <label class="col-lg-3 col-form-label">Direccion: <span class="text-danger"></span></label>
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
                                        <input type="text" name="id_country_code" wire:model="id_country_code" hidden>
                                        <input class="form-control" type="text" name="name_country" list="countries" placeholder="Ninguna" wire:model="name_country">
                                        <datalist id="countries">
                                            @foreach($country_codes as $country_code)
                                            <option style="color: black;">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                            @endforeach
                                        </datalist>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('buyer_modal_hide', event => {
            $('#buyerModal').modal('hide');
        });
    </script>
</div>