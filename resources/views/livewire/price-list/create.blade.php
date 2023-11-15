<!-- Modal -->
<div wire:ignore.self class="modal fade" id="priceListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de listas de precios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Nombre de lista: <span class="text-danger"></span></label>

                        <div class="col-6">
                            <input data-toggle="text" data-placement="after" class="form-control" type="text" name="name" wire:model="name" placeholder="Nombre" data-parsley-required="true" />
                        </div>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Descripción: <span class="text-danger"></span></label>

                        <div class="col-6">
                            <input data-placement="after" class="form-control" type="text" name="description" wire:model="description" placeholder="Descripcion de la lista de precios" data-parsley-required="true" />
                            
                        </div>                        
                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" wire:click.prevent="cancel()" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('priceList_modal_hide', event => {
        $('#priceListModal').modal('hide');
    });
</script>