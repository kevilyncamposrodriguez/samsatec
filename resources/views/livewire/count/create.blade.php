<!-- Modal -->
<div wire:ignore.self class="modal fade" id="countModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de cuenta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf                    
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">

                        <label class="col-lg-12 col-form-label">Pertenece a: <strong> Bancos</strong><span class="text-danger"></span></label>

                     
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre de cuenta: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="text" name="name" wire:model="name" />
                            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="text" name="description" wire:model="description" />
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Saldo Inicial: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="number" name="initial_balance" wire:model="initial_balance" value="0"/>
                            @error('initial_balance') <span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('count_modal_hide', event => {
        $('#countModal').modal('hide');
    });
</script>