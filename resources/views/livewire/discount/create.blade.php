
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de descuentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-2">
                        <label class="col-lg-2 col-form-label">Naturaleza: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input class="form-control" type="text" name="nature" wire:model="nature" placeholder="Naturaleza del descuento"/>
                            @error('nature') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>  
                        <label class="col-lg-2 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-2">
                            <input class="form-control" type="text" name="amount" wire:model="amount" />
                            @error('amount') <span class="text-red-500">{{ $message }}</span>@enderror                        
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
    window.addEventListener('discount_modal_hide', event => {
        $('#discountModal').modal('hide');
    });
</script>
