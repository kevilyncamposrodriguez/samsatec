<!-- Modal -->
<div wire:ignore.self class="modal fade" id="payMethodInformationUModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualización de método de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                @csrf
                <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Número de cuenta: <span
                                class="text-danger"></span></label>
                        <input class="form-control" type="text" name="count_number" wire:model="count_number"/>
                        @error('count_number') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Titular de la cuenta: <span
                                class="text-danger"></span></label>
                        <input class="form-control" type="text" name="count_owner" wire:model="count_owner"/>
                        @error('count_owner') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Correo de notificación: <span class="text-danger"></span></label>
                        <input class="form-control" type="text" name="email_notification"
                               wire:model="email_notification"/>
                        @error('email_notification') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-check row m-b-10">
                        <label><input type="checkbox" wire:model="use_by_default" value="" name="use_by_default"
                                      @if ($use_by_default == true) checked @endif> Método por defecto ...</label>
                        @error('use_by_default') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-check row m-b-10">
                        <label><input type="checkbox" wire:model="active" value="" name="active"
                                      @if ($active == true) checked @endif> El método de pago está activo ...</label>
                        @error('active') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;"
                        class="btn btn-primary" data-dismiss="modal">Actualizar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('payMethodInformationU_modal_hide', event => {
        $('#payMethodInformationUModal').modal('hide');
    });
</script>
