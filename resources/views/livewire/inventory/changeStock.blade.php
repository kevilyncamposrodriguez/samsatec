<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="changeStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modificar Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_change_stock" class="form-horizontal form-bordered">
                        @csrf
                        <div class="form-group row bg-grey">
                            <div class="col-lg-8">
                                <strong> Producto:</strong>
                                <input disabled type="text" name="description" class="form-control" wire:model="description" />
                            </div>
                            <div class="col-lg-4">
                                <strong> Cantidad Actual:</strong>
                                <input type="number" name="stock" class="form-control" wire:model="stock" />
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="changeStock()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('changeStock_modal_hide', event => {
        $('#changeStockModal').modal('hide');
    });
</script>