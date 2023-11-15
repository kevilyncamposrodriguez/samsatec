<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="transferIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Traslado de Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_newAI" class="form-horizontal form-bordered">
                        @csrf
                        <div class="form-group row bg-grey">
                            <div class="col-lg-5">
                                <strong> Inventario de Salida:</strong>
                                <select class="form-control" name="inventaryS" wire:change="updateInventaryS" wire:model="inventaryS">
                                    <option style="color: black;" value="" label="Seleccionar...">
                                        @foreach($allInventariesS as $inventaryS)
                                    <option style="color: black;" value="{{ $inventaryS->id }}" label="{{ $inventaryS->name }}">
                                        @endforeach
                                </select>
                                <strong> Producto:</strong>
                                <input class="form-control" type="text" name="name_productS" id="name_productS" onchange="get_productS()" wire:change="updateProductS" list="productsS" placeholder="Ninguno" wire:model="name_productS">
                                <datalist id="productsS">
                                    @foreach($allProductsS as $productS)
                                    <option style="color: black;" data-id="{{ $productS->id }}" value="{{ $productS->description }}" label="{{ $productS->internal_code }}">
                                        @endforeach
                                </datalist>
                                <input type="text" name="productS" wire:model="productS" hidden>
                                @error('productS') <span class="text-red-500">{{ $message }}</span>@enderror

                                <div class="row">
                                    <div class="m-t-15 col-lg-6 text-center">
                                        <strong> Cantidad Actual:</strong>
                                        <input disabled type="text" name="qtyAS" class="form-control" wire:model="qtyAS" />
                                    </div>
                                    <div class="m-t-15 col-lg-6 text-center">
                                        <strong>Nueva Cantidad:</strong>
                                        <input disabled type="text" name="qtyNS" class="form-control" wire:model="qtyNS" />
                                    </div>
                                </div>
                                @error('productS') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-2 m-t-20 text-center">
                                <strong><i class="fa fa-arrow-right"></i></strong><br>
                                <div class="m-t-15 text-center">
                                    <strong> Cantidad:</strong>
                                    <input type="text" name="qtyTransfer" wire:change="updateQtyTransfer" class="form-control" wire:model="qtyTransfer" />
                                    @error('qtyTransfer') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="m-t-15 text-center">
                                    <strong> Costo:</strong>
                                    <input disabled type="text" name="cost" class="form-control" wire:model="cost" />
                                    @error('cost') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <strong> Inventario de Entrada:</strong>
                                <select class="form-control" wire:change="updateInventaryE" name="inventaryE" wire:model="inventaryE">
                                    <option style="color: black;" value="" label="Seleccionar...">
                                        @foreach($allInventariesE as $inventaryE)
                                    <option style="color: black;" value="{{ $inventaryE->id }}" label="{{ $inventaryE->name }}">
                                        @endforeach
                                </select>
                                <strong> Producto:</strong>

                                <input class="form-control" type="text" name="name_productE" id="name_productE" onchange="get_productE()" wire:change="updateProductE" list="productsE" placeholder="Ninguno" wire:model="name_productE">
                                <datalist id="productsE">
                                    @foreach($allProductsE as $productE)
                                    <option style="color: black;" data-id="{{ $productE->id }}" value="{{ $productE->description }}" label="{{ $productE->internal_code }}">
                                        @endforeach
                                </datalist>
                                <input type="text" name="productE" wire:model="productE" hidden>
                                @error('productE') <span class="text-red-500">{{ $message }}</span>@enderror
                                <div class="row">
                                    <div class="m-t-15 col-lg-6 text-center">
                                        <strong> Cantidad Actual:</strong>
                                        <input disabled type="text" name="qtyAEt" class="form-control" wire:model="qtyAE" />
                                    </div>
                                    <div class="m-t-15 col-lg-6 text-center">
                                        <strong>Nueva Cantidad:</strong>
                                        <input disabled type="text" name="qtyNEt" class="form-control" wire:model="qtyNE" />
                                    </div>
                                </div>
                                @error('productE') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>

                        </div>
                        <div class="m-t-6 m-b-6">
                            <button type="button" wire:click.prevent="addLineT()" onclick="this.disabled=true;" class="form-control btn btn-primary"><strong>Agragar Linea</strong></button>
                        </div>
                        <br>
                    </form>
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        @error('allLinesT') <span class="text-red-500">Debes agregar al menos una linea</span>@enderror
                        <!-- begin table-responsive -->
                        <div class="table-responsive bg-gray-400">
                            <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                <thead>
                                    <tr class="text-white" style="background-color:#192743;">
                                        <th class="text-center" width="1%"></th>
                                        <th class="text-center" width="1%">N°</th>
                                        <th class="text-center" width="48%">PRODUCT SALIDA</th>
                                        <th class="text-center" width="10%">CANT. ACTUAL</th>
                                        <th class="text-center" width="10%">CANT FINAL</th>
                                        <th class="text-center" width="48%">PRODUCT ENTRADA</th>
                                        <th class="text-center" width="10%">CANT. ACTUAL</th>
                                        <th class="text-center" width="10%">CANT FINAL</th>
                                        <th class="text-center" width="10%">COSTO</th>
                                        <th class="text-center" width="10%">TOTAL TRASLADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLinesT as $index => $line)
                                    <tr class="gradeU">
                                        <td width="1%" class="text-center btn-group btn-group-justified">
                                            <button wire:click.prevent="deleteLineT({{ $index }})" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                            <button wire:click.prevent="editLineT({{ $index }})" class="btn btn-primary btn-xs"><i class="fa fa-pen"></i></button>
                                        </td>
                                        <td width="1%" class="text-center">{{ $index+1 }}</td>
                                        <td width="48%" class="text-center">{{ $line['name_productS'] }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyAS'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyNS'],2,'.',',') }}</td>
                                        <td width="48%" class="text-center">{{ $line['name_productE'] }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyAE'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyNE'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['cost'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['total_line'],2,'.',',') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- begin invoice-price -->
                        <div class="col-lg-12">
                            <div class="invoice-price row">
                                <div class="invoice-price-left col-8">
                                    <div class="invoice-price-row row">

                                    </div>
                                </div>
                                <div class="invoice-price-right col-4" style="background-color:#192743;">
                                    <h3 class="text-white">TOTAL</h3>
                                    <h3 class="f-w-600 text-white text-right"> {{ number_format($total,2,'.',',') }}</h3>
                                </div>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <div class="col-lg-12">
                        <strong> Observaciones:</strong>
                        <textarea class="form-control" name="observation" rows="3" class="col-lg-12" wire:model="observation"></textarea>
                        @error('observation') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="storeT()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Trasladar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function get_productS() {
        var val = $('#name_productS').val()
        var id_productS = $('#productsS option').filter(function() {
            return this.value == val;
        }).data('id');
        @this.set('productS', id_productS);
    }

    function get_productE() {
        var val = $('#name_productE').val()
        var id_productE = $('#productsE option').filter(function() {
            return this.value == val;
        }).data('id');
        @this.set('productE', id_productE);
    }
    window.addEventListener('transferI_modal_hide', event => {
        $('#transferIModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $("#transferIModal").on("hidden.bs.modal", function() {
            Livewire.emit('cleanInputsT')
        });

    });
</script>