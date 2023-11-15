<!-- Modal -->
<div wire:ignore.self class="modal fade" id="terminalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de terminales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-6 col-xl-6">
                            <select class="form-control" name="id_branch_office" id="id_branch_office" wire:model="id_branch_office">
                                <option style="color: black;" value="0">Seleccionar</option>
                                @foreach($allBO as $bo)
                                <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="number" name="number" wire:model="number" placeholder="Número" data-parsley-required="true" />
                        </div>
                        @error('number') <span class="text-red-500">{{ $message }}</span>@enderror
                        @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('terminal_modal_hide', event => {
        $('#terminalModal').modal('hide');
    });
</script>