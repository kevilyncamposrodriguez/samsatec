<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="serviceUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualización de servicio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-bordered">
                        @csrf
                        @if(Auth::user()->currentTeam->bo_inventory == 1)
                        <!-- begin form-group -->
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Inventario de Sucursal: <span class="text-danger"></span></label>
                            <div class="col-lg-8 col-xl-8">
                                <select {{ (isset($teamUser->bo))?'disabled':''}} class="form-control" data-size="10" data-live-search="true" name="id_bo" id="id_bo" wire:model="id_bo">
                                    <option style="color: black;" value="">Ninguno...</option>
                                    @foreach($allBranchOffices as $bo)
                                    <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                    @endforeach
                                </select>
                                @error('id_bo') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        @endif
                        <!-- begin form-group -->
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Codigo Comercial: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <select class="form-control" data-size="10" data-live-search="true" name="type_code_product" id="type_code_product" wire:model="type_code_product">
                                    <option style="color: black;" value="">Ninguno...</option>
                                    @foreach($allTypeCodes as $typeCode)
                                    <option style="color: black;" value="{{ $typeCode->id }}">{{ $typeCode->type }}</option>
                                    @endforeach
                                </select>
                                @error('type_code_product') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <label class="col-lg-2 col-form-label">Codigo: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <input class="form-control" {{ ($type_code_product=='')?'disabled':''}} type="text" name="other_code" id="other_code" wire:model="other_code" placeholder="Codigo comercial" />
                                @error('other_code') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- end form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-2 col-form-label">CAByS: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <input class="form-control" type="text" name="cabys" list="cabies" placeholder="Ninguno" wire:model="cabys">
                                <datalist id="cabies" data-size="100">
                                    @foreach($allCabys as $ca)
                                    <option style="color: black;" value="{{ $ca->codigo }}" label="{{ $ca->descripcion }}">
                                        @endforeach
                                </datalist>
                                @error('cabys') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @if(Auth::user()->currentTeam->plan_id > 3)
                        <div class="form-group row ">
                            <label class="col-lg-2 col-form-label">Cuenta de ingresos: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <select class="form-control" data-size="10" data-live-search="true" name="id_count_income" id="id_count_income" wire:model="id_count_income">
                                    @foreach($allCountIncomes as $count)
                                    <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                    @endforeach
                                </select>
                                @error('id_count_income') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <label class="col-lg-2 col-form-label">Cuenta de ventas: <span class="text-danger"></span></label>
                            <div class="col-lg-4 col-xl-4">
                                <select class="form-control" data-size="10" data-live-search="true" name="id_count_expense" id="id_count_expense" wire:model="id_count_expense">
                                    @foreach($allCountExpenses as $count)
                                    <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                    @endforeach
                                </select>
                                @error('id_count_expense') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @endif
                        <!-- begin form-group -->
                        <div class="form-group row ">
                            <label class="col-lg-3 col-form-label">Descripcion: <span class="text-danger"></span></label>
                            <div class="col-lg-9 col-xl-9">
                                <input type="text" name="description" data-parsley-required="true" class="form-control" placeholder="Descripcion del producto o servicio" wire:model="description" />
                                @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Impuestos: </label>
                            <div class="col-lg-4 col-xl-6">
                                <label class="col-lg-12">IVA:<span class="text-danger"></span></label>
                                <select class="form-control" name="id_tax" id="id_tax" wire:model="id_tax">
                                    @foreach($allTaxes as $tax)
                                    <option style="color: black;" value="{{ $tax->id }}">{{ $tax->description }}</option>
                                    @endforeach
                                </select>
                                @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-4 col-xl-4">
                                <label class="col-lg-12">Base Imponible:<span class="text-danger"></span></label>
                                <input {{ $disable = ($need_tax_base)?'':'disabled' }} data-toggle="number" class="form-control text-center" type="number" name="tax_base" id="tax_base" min="0" wire:model="tax_base" />
                                <span class="text-gray-500">IVA Cálculo especial</span>
                                @error('tax_base') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-4 col-xl-4">
                                <label class="col-lg-12">Unidad medida: <span class="text-danger"></span></label>
                                <input class="form-control" type="text" name="name_sku" list="skusesU" placeholder="Ninguna" wire:model="name_sku">
                                <datalist id="skusesU">
                                    @foreach($allSkuses as $sku)
                                    <option style="color: black;">{{ $sku->symbol.'-'.$sku->description }}</option>
                                    @endforeach
                                </datalist>
                                <input type="text" name="id_sku" wire:model="id_sku" hidden>
                                @error('id_sku') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-4 col-xl-4">
                                <label class="col-lg-12">Monto de exportacion: <span class="text-danger"></span></label>
                                <input type="number" name="export_tax" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="export_tax" />
                                <span class="text-gray-500">Factura de exportación</span>
                                @error('export_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-4 col-xl-4">
                                <label class="col-lg-12">Costo por Unidad: <span class="text-danger"></span></label>
                                <input type="number" name="cost_unid" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="cost_unid" />
                                @error('cost_unid') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-group row ">
                            <div class="col-lg-3 col-xl-3">
                                <label class="col-lg-12">IVAI ? : <span class="text-danger"></span></label>
                                <input type="checkbox" name="ivai" class="form-control text-center" placeholder="0" wire:model="ivai" wire:change="changeIvai()" />
                            </div>
                            <div class="col-lg-3 col-xl-3">
                                <label class="col-lg-12">Precio por Unidad: <span class="text-danger"></span></label>
                                <input {{ $disable = ($ivai)?'disabled':'' }} type="number" name="price_unid" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="price_unid" />
                                @error('price_unid') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-3 col-xl-3">
                                <label class="col-lg-12">Impuesto por Unidad: <span class="text-danger"></span></label>
                                <input type="number" disabled name="total_tax" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="total_tax" />
                                @error('total_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>

                            <div class="col-lg-3 col-xl-3">
                                <label class="col-lg-12">Precio de venta: <span class="text-danger"></span></label>
                                <input type="number" {{ $disable = ($ivai)?'':'disabled' }} name="total_price" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="total_price" />
                                @error('total_price') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
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
        window.addEventListener('serviceU_modal_hide', event => {
            $('#serviceUModal').modal('hide');
        });
    </script>
</div>