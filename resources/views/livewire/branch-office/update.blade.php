<!-- Modal -->
<div wire:ignore.self class="modal fade" id="boUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualización de Sucursal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <input type="hidden" wire:model="bo_id">
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input type="text" id="name" name="name" wire:model="name" class="form-control" placeholder="Nombre sucursal" />
                        </div>
                        <div class="col-3">
                            <input data-toggle="number" data-placement="after" class="form-control" type="number" name="number" wire:model="number" placeholder="Numero" data-parsley-required="true" />
                        </div>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
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
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_canton" id="id_canton" wire:model="id_canton">
                                        <option style="color: black;" value="0">Canton</option>
                                        @foreach($cantons as $canton)
                                        <option style="color: black;" value="{{ $canton->id }}">{{ $canton->canton }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <select class="form-control" data-parsley-required="true" name="id_district" id="id_district" wire:model="id_district">
                                        <option style="color: black;" value="0">Distrito</option>
                                        @foreach($districts as $district)
                                        <option style="color: black;" value="{{ $district->id }}">{{ $district->district }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('id_province') <span class="text-red-500">{{ $message }}</span>@enderror
                                @error('id_canton') <span class="text-red-500">{{ $message }}</span>@enderror
                                @error('id_district') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- end form-group -->
                    <div class="form-group row m-b-10">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row row-space-12">
                                <div class="col-lg-12 col-xl-12">
                                    <input type="text" name="other_signs" data-parsley-required="true" wire:model="other_signs" class="form-control" placeholder="Otras señas" />
                                </div>
                                @error('other_signs') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Correo: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="emails" class="form-control" wire:model="emails" data-parsley-required="true" data-parsley-type="email" placeholder="Correo electronico para informacion (va en la factura)" />
                        </div>
                        @error('emails') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Telefono: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-6">
                                    <select class="default-select2 form-control" name="id_country_code" wire:model="id_country_code">
                                        @foreach($country_codes as $country_code)
                                        @if( $country_code->phone_code == "506")
                                        <option style="color: black;" selected="" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @else
                                        <option style="color: black;" value="{{ $country_code->id }}">{{ $country_code->phone_code." - ".$country_code->name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input data-toggle="number" data-placement="after" class="form-control" type="number" name="phone" wire:model="phone" placeholder="N. Telefonico" minlength="8" maxlength="8" />
                                </div>
                            </div>
                            @error('id_country_code') <span class="text-red-500">{{ $message }}</span>@enderror
                            @error('phone') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Asignar Inventario: </label>
                        <div class="col-lg-9 col-xl-9">
                            <div class="row row-space-6">
                                <div class="col-12">
                                    <select class="form-control" {{(Auth::user()->currentTeam->plan_id == 3 || !Auth::user()->currentTeam->bo_inventory)?'disabled':''}} name="id_count" id="id_count" wire:model="id_count">
                                        @foreach($allAcounts as $account)
                                        <option style="color: black;" value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary" data-dismiss="modal">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('boU_modal_hide', event => {
        $('#boUModal').modal('hide');
    });
</script>