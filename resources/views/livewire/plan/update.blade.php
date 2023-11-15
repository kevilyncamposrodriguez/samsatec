
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="planUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualización de plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Nombre: <span class="text-danger"></span></label>
                        <input class="form-control" type="text" name="name" wire:model="name" step="2"/>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <input class="form-control" type="text" name="description" wire:model="description" />
                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">JSON Detalles: <span class="text-danger"></span></label>
                        <input class="form-control" type="text" name="descriptionJSON" wire:model="descriptionJSON" />
                        @error('descriptionJSON') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-check row m-b-10">
                        <label><input type="checkbox" wire:model="active" value="" name="active" @if ($active == true) checked @endif>  El plan está activo ...</label>
                        @error('active') <span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('planU_modal_hide', event => {
        $('#planUModal').modal('hide');
    });
</script>
