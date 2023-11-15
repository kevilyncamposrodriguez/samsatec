<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="transformModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Transformar Producto</h5>
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
                                <select class="form-control" name="inventaryS" id="inventaryS" wire:change="updateInventaryS" wire:model="inventaryS">
                                    <option style="color: black;" value="" label="Seleccionar...">
                                        @foreach($allInventaries as $inventaryS)
                                    <option style="color: black;" value="{{ $inventaryS->id }}" label="{{ $inventaryS->name }}">
                                        @endforeach
                                </select>
                                <strong> Producto:</strong>
                                <input class="form-control" type="text" name="name_productS" id="name_productST" onchange="get_productST()" wire:change="updateProductS" list="productsST" placeholder="Ninguno" wire:model="name_productS">
                                <datalist id="productsST">
                                    @foreach($allProductsS as $productS)
                                    <option style="color: black;" data-id="{{ $productS->id }}" value="{{ $productS->description }}" label="{{ $productS->internal_code }}">
                                        @endforeach
                                </datalist>
                                <input type="text" name="productS" wire:model="productS" hidden>
                                @error('productS') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-2 m-t-40 text-center">
                                <strong><i class="fa fa-arrow-right"></i></strong><br>
                            </div>
                            <div class="col-lg-5">
                                <strong> Inventario de Entrada:</strong>
                                <select class="form-control" wire:change="updateInventaryE" name="inventaryE" wire:model="inventaryE">
                                    <option style="color: black;" value="" label="Seleccionar...">
                                        @foreach($allInventaries as $inventaryE)
                                    <option style="color: black;" value="{{ $inventaryE->id }}" label="{{ $inventaryE->name }}">
                                        @endforeach
                                </select>
                                <strong> Producto:</strong>
                                <input class="form-control" type="text" name="name_productE" id="name_productET" onchange="get_productET()" wire:change="updateProductE" list="productsET" placeholder="Ninguno" wire:model="name_productE">
                                <datalist id="productsET">
                                    @foreach($allProductsE as $productE)
                                    <option style="color: black;" data-id="{{ $productE->id }}" value="{{ $productE->description }}" label="{{ $productE->internal_code }}">
                                        @endforeach
                                </datalist>
                                <input type="text" name="productE" wire:model="productE" hidden>
                                @error('productE') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-2 text-center">
                                    <strong> Cantidad Actual:</strong>
                                    <input disabled type="text" name="qtyAS" class="form-control" wire:model="qtyAS" />
                                </div>
                                <div class="col-lg-2 text-center">
                                    <strong>Nueva Cantidad:</strong>
                                    <input disabled type="text" name="qtyNS" class="form-control" wire:model="qtyNS" />
                                </div>
                                <div class="text-center col-lg-2">
                                    <strong> Cant. Salida:</strong>
                                    <input type="text" name="qtyTransferS" wire:change="updateQtyTransferS" class="form-control" wire:model="qtyTransferS" />
                                </div>
                                <div class="text-center col-lg-2">
                                    <strong> Cant. Entrada:</strong>
                                    <input type="text" name="qtyTransferE" wire:change="updateQtyTransferE" class="form-control" wire:model="qtyTransferE" />
                                </div>
                                <div class="col-lg-2 text-center">
                                    <strong> Cantidad Actual:</strong>
                                    <input disabled type="text" name="qtyAE" class="form-control" wire:model="qtyAE" />
                                </div>
                                <div class="col-lg-2 text-center">
                                    <strong>Nueva Cantidad:</strong>
                                    <input disabled type="text" name="qtyNE" class="form-control" wire:model="qtyNE" />
                                </div>
                            </div>
                            <div class="col-lg-12 text-center">
                                <strong> Observaciones:</strong>
                                <textarea class="form-control" id="observation" name="observation" rows="3" class="col-lg-12" wire:model="observation"></textarea>
                                @error('observation') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="storeTranform()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Trasladar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function get_productST() {
        var val = $('#name_productST').val()
        var id_productS = $('#productsST option').filter(function() {
            return this.value == val;
        }).data('id');
        @this.set('productS', id_productS);
    }

    function get_productET() {
        var val = $('#name_productET').val()
        var id_productE = $('#productsET option').filter(function() {
            return this.value == val;
        }).data('id');
        @this.set('productE', id_productE);
    }
    window.addEventListener('transferI_modal_hide', event => {
        $('#transformModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $("#transformModal").on("hidden.bs.modal", function() {
            Livewire.emit('cleanInputsT')
        });
    });
</script>