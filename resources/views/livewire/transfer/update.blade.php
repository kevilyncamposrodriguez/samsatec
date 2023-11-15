<!-- Modal -->
<div wire:ignore.self class="modal fade " id="transferUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de Vales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-2 col-form-label">Consecutivo: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input class="form-control" disabled type="text" name="consecutive" id="consecutive" wire:model="consecutive" />
                            @error('consecutive') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Fecha: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="date" name="date_issue" class="form-control" placeholder="Fecha" wire:model="date_issue" />
                            @error('date_issue') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Usuario: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="text" disabled name="user" class="form-control" placeholder="Nombre usuario" wire:model="user" />
                            @error('user') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Referencia: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="text" name="reference" class="form-control" placeholder="referencia" wire:model="reference" />
                            @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Detalle: <span class="text-danger"></span></label>
                        <div class="col-lg-10 col-xl-10">
                            <input type="text" name="detail" class="form-control" placeholder="Detalle de lvale" wire:model="detail" />
                            @error('detail') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Debitar de: </label>
                        <div class="col-4" wire:ignore>
                            <select class="form-control " name="id_count_d" id="id_count_dU">
                                @foreach($allCounts as $count)
                                <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                @endforeach
                            </select>
                            @error('id_count_d') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Acreditar a: </label>
                        <div class="col-4" wire:ignore>
                            <select class="form-control " name="id_count_c" id="id_count_cU">
                                @foreach($allCounts as $count)
                                <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                @endforeach
                            </select>
                            @error('id_count_c') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="number" name="mount" class="form-control" placeholder="Monto del Vale" wire:model="mount" />
                            @error('mount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" wire:click.prevent="resetInputFields()" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('transferU_modal_hide', event => {
        $('#transferUModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('#id_count_cU').select2();
        $('#id_count_cU').on('change', function(e) {
            livewire.emit('changeCountC', $('#id_count_cU').select2("val"))
        });
        $('#id_count_dU').select2();
        $('#id_count_dU').on('change', function(e) {
            livewire.emit('changeCountD', $('#id_count_d').select2("val"))
        });
    });
    //manejo de clientes
    window.addEventListener('countC-updated', event => {
        $("#id_count_cU").select2().val(event.detail.newValue).trigger('change.select2');
    })
    window.addEventListener('countD-updated', event => {
        $("#id_count_dU").select2().val(event.detail.newValue).trigger('change.select2');
    })
</script>