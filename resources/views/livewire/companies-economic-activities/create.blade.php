<!-- Modal -->
<div wire:ignore.self class="modal fade" id="eaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de Actividades Economicas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Actividad economica: <span class="text-danger"></span></label>
                        <div wire:ignore class="col-lg-9 col-xl-9">                            
                            <select onchange="ea_change()" class="form-control ea-select2" name="id_ea" id="id_ea" data-parsley-required="true">
                                @foreach($allAEs as $ea)
                                <option style="color: black;" value="{{ $ea->id }}" >{{ $ea->number.' - '.$ea->name_ea }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_ea')<span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('ea_modal_hide', event => {
        $('#eaModal').modal('hide');
    });

    function ea_change() {
        window.livewire.emit('changeEA',document.getElementById("id_ea").value);
    }
</script>