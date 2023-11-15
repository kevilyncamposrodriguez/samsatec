
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="lotUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                  
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Codigo: <span class="text-danger"></span></label>

                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="text"  name="code" wire:model="code"  placeholder="Codigo"  data-parsley-required="true"  />
                        </div>
                        @error('code') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Fecha de compra: <span class="text-danger"></span></label>
                        <input class="form-control" type="datetime-local" name="date_purchase" wire:model="date_purchase" step="2"/>
                        @error('date_purchase') <span class="text-red-500">{{ $message }}</span>@enderror

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
    window.addEventListener('lotU_modal_hide', event => {
        $('#lotUModal').modal('hide');
    });
</script>
