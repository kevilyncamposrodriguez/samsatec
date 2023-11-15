<!-- Modal -->
<div  wire:ignore.self class="modal fade" id="productUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ ($type)?'Actualización de Producto':'Actualización de servicio' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Código Comercial: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <select class="form-control" data-size="10" data-live-search="true" name="type_code_product" id="type_code_product" wire:model="type_code_product">
                                <option style="color: black;" value="">Ninguno...</option>
                                @foreach($allTypeCodes as $typeCode)
                                <option style="color: black;" value="{{ $typeCode->id }}">{{ $typeCode->type }}</option>
                                @endforeach
                            </select>
                            @error('type_code_product') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Código: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input class="form-control" {{ ($type_code_product=='')?'disabled':''}} type="text" name="other_code" id="other_code" wire:model="other_code" placeholder="Codigo comercial" />
                            @error('other_code') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row" {{ $v=($type)?'':'hidden' }}>
                        <label class="col-lg-2 col-form-label">Clase: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <select class="form-control" wire:click="changeClass()" data-size="10" data-live-search="true" name="id_class" id="id_class" wire:model="id_class">
                                @foreach($allClasses as $class)
                                <option style="color: black;" value="{{ $class->id }}">{{ $class->symbol.'-'.$class->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-blue-500">Necesario para código interno</span>
                            @error('id_class') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Codigo interno: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input class="form-control" disabled type="text" name="internal_code" id="internal_code" wire:model="internal_code" placeholder="Codigo interno" />
                            @error('internal_code') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">CAByS: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="text" name="cabys" data-parsley-required="true" class="form-control" placeholder="Codigo cabys" wire:model="cabys" />
                            @error('cabys') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label" {{ $v=($type)?'':'hidden' }}>Categoría: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4" {{ $v=($type)?'':'hidden' }}>
                            <select class="form-control" data-size="10" data-live-search="true" name="id_category" id="id_category" wire:model="id_category">
                                @foreach($allCategories as $category)
                                <option style="color: black;" value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('id_category') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row " {{ $v=($type)?'':'hidden' }}>
                        <label class="col-lg-2 col-form-label">Familia: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <select class="form-control" data-size="10" data-live-search="true" name="id_family" id="id_family" wire:model="id_family">
                                <option style="color: black;" value="">Selecionar...</option>
                                @foreach($allFamilies as $family)
                                <option style="color: black;" value="{{ $family->id }}">{{ $family->name }}</option>
                                @endforeach
                            </select>
                            @error('id_family') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Cuenta de inventario: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <select class="form-control" data-size="10" data-live-search="true" name="id_count_inventory" id="id_count_inventory" wire:model="id_count_inventory">
                                @foreach($allCountInventary as $count)
                                <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                @endforeach
                            </select>
                            @error('id_count_inventory') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
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
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-3 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" name="description" data-parsley-required="true" class="form-control" placeholder="Descripcion del producto o servicio" wire:model="description" />
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Impuestos: </label>
                        <div class="col-lg-6 col-xl-6">
                            <div>
                                <select class="form-control" multiple="multiple" name="ids_taxes" id="ids_taxes" wire:model="ids_taxes">
                                    @foreach($allTaxes as $tax)
                                    <option style="color: black;" value="{{ $tax->id }}">{{ $tax->description }}</option>
                                    @endforeach
                                </select>
                                <span class="text-gray-500"> Marca multiple o desmarcar Ctrl + Click</span>
                                @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-lg-4 col-xl-4">
                            <label class="col-lg-12">Base Imponible:<span class="text-danger"></span></label>
                            <input {{ $disable = ($need_tax_base)?'':'disabled' }} data-toggle="number" class="form-control text-center" type="number" name="tax_base" id="tax_base" min="0" wire:model="tax_base" />
                            <span class="text-gray-500">IVA Cálculo especial</span>
                            @error('tax_base') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @if(Auth::user()->currentTeam->type_plan != 1)
                    <div class="form-group row " {{ $v=($type)?'':'hidden' }}>
                        <div class="col-lg-3 col-xl-4">
                            <label class="col-lg-12">Stock: <span class="text-danger"></span></label>
                            <input type="number" name="stock" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="stock" />
                            <span class="text-gray-500">Cantidad en bodega</span>
                            @error('stock') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-4">
                            <label class="col-lg-12">Stock Base: <span class="text-danger"></span></label>
                            <input type="number" name="stock_base" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="stock_base" />
                            @error('stock_base') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-4">
                            <label class="col-lg-12">Alerta de Stock: <span class="text-danger"></span></label>
                            <input type="number" name="alert_min" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="alert_min" />
                            @error('alert_min') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    @endif
                    <div class="form-group row">
                        <div class="col-lg-3 col-xl-3" wire:ignore>
                            <label class="col-lg-12">Unidad medida: <span class="text-danger"></span></label>
                            <select class="form-control skuUP-select2" onchange="changeSkuUP()" name="id_skuUP" id="id_skuUP">
                                @foreach($allSkuses as $sku)
                                @if($sku->symbol != 'Spe' && $sku->symbol != 'Sp')
                                <option style="color: black;" value="{{ $sku->id }}">{{ $sku->symbol.'-'.$sku->description }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('id_skuP') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Monto de exportación: <span class="text-danger"></span></label>
                            <input type="number" name="export_tax" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="export_tax" />
                            <span class="text-gray-500">Factura de exportación</span>
                            @error('export_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Descuento por unidad: <span class="text-danger"></span></label>
                            <select class="form-control" name="id_discount" id="id_discount" wire:model="id_discount">
                                <option style="color: black;" value="">Ninguno</option>
                                @foreach($allDiscounts as $discount)
                                <option style="color: black;" value="{{ $discount->id }}">{{ $discount->nature.'-'.$discount->amount }}</option>
                                @endforeach
                            </select>
                            @error('id_discount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Monto descuento: <span class="text-danger"></span></label>
                            <input type="number" name="discount" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="discount" />
                            @error('discount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row ">
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Costo por Unidad: <span class="text-danger"></span></label>
                            <input type="number" name="cost_unid" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="cost_unid" />
                            @error('cost_unid') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Precio por Unidad: <span class="text-danger"></span></label>
                            <input type="number" name="price_unid" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="price_unid" />
                            @error('price_unid') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Impuesto por Unidad: <span class="text-danger"></span></label>
                            <input type="number" disabled name="total_tax" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="total_tax" />
                            @error('total_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-lg-3 col-xl-3">
                            <label class="col-lg-12">Precio de venta: <span class="text-danger"></span></label>
                            <input type="number" disabled name="total_price" data-parsley-required="true" class="form-control text-center" placeholder="0" wire:model="total_price" />
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
    window.addEventListener('productU_modal_hide', event => {
        $('#productUModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('.modal').on('shown.bs.modal', function() {
            $(".skuUP-select2").select2({
                dropdownParent: $('#productUModal .modal-content')
            });
        });
    });
</script>
<script>
    function changeSkuUP() {
        window.livewire.emit('changeSku', document.getElementById("id_skuUP").value);
    }
    window.addEventListener('skuUP-updated', event => {
        $("#id_skuUP").select2().val(event.detail.newValue).trigger('change.select2');
    })
</script>