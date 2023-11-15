
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="consecutivesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Administración de consecutivos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Facturas Electrónicas: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_fe" type="number" class="form-control" min="1" name="c_fe"  wire:model="c_fe" >
                        </div>
                        @error('c_fe') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Notas de crédito: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_nc" type="number" class="form-control" min="1" name="c_nc"  wire:model="c_nc">
                        </div>
                        @error('c_nc') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Notas de débito: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_nd" type="number" class="form-control" min="1" name="c_nd"  wire:model="c_nd">
                        </div>
                        @error('c_nd') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Facturas de compra: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_fc" type="number" class="form-control" min="1" name="c_fc"  wire:model="c_fc">
                        </div>
                        @error('c_fc') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Facturas de exportación: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_fex" type="number" class="form-control" min="1" name="c_fex" wire:model="c_fex">
                        </div>
                        @error('c_fex') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Tiquetes Electrónicos: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_te" type="number" class="form-control" min="1" name="c_te" wire:model="c_te">
                        </div>
                        @error('c_te') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Documentos Aceptados: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_mra" type="number" class="form-control" min="1" name="c_mra" wire:model="c_mra">
                        </div>
                        @error('c_mra') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Documentos Rechazados: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <input id="c_mrr" type="number" class="form-control" min="1" name="c_mrr" wire:model="c_mrr">
                        </div>
                        @error('c_mrr') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="storeConsecutives()" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('consecutives_modal_hide', event => {
        $('#consecutivesModal').modal('hide');
    });
</script>
