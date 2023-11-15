<!-- Modal -->
<div wire:ignore.self class="modal fade" id="paymentUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Cliente: <span class="text-danger"></span></label>
                        <div class="col-10">
                            <input disabled data-placement="after" class="form-control" type="text" name="name" wire:model="name" placeholder="Cliente" data-parsley-required="true" />
                        </div>
                        @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-4 col-form-label">Consecutivo: <span class="text-danger"></span></label>
                        <div class="col-8">
                            <input data-placement="after" disabled class="form-control" type="text" name="consecutive" wire:model="consecutive" placeholder="Consecutivo" data-parsley-required="true" />
                        </div>
                        @error('consecutive') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Fecha de pago: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input class="form-control" type="datetime-local" name="date" wire:model="date" />
                            @error('date') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Cuenta Bancaria: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <select class="form-control" name="id_count" wire:model="id_count">
                                <option style="color: black;" value="">Ninguna</option>
                                @foreach($allAcounts as $count)
                                @if($count != null)
                                <option style="color: black;" value="{{ $count['id'] }}">{{ $count['name'] }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Referencia: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="text" name="reference" wire:model="reference" placeholder="Referencia" data-parsley-required="true" />
                        </div>
                        @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="number" name="mount" wire:model="mount" placeholder="Monto" data-parsley-required="true" />
                        </div>
                        @error('mount') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Observaciones: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="textarea" name="observations" wire:model="observations" placeholder="Observaciones" data-parsley-required="true" />
                        </div>
                        @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('paymentU_modal_hide', event => {
        $('#paymentUModal').modal('hide');
    });
</script>