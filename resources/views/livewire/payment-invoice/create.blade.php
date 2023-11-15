<!-- Modal -->
<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row ">

                        <label class="col-lg-4 col-form-label">Facturas Pendientes: <span class="text-danger"></span></label>
                        <div class="col-8">
                            <select class="form-control" name="id_document" wire:model="id_document" wire:change='changeDoc()'>
                                <option style="color: black;" value="">Ninguna</option>
                                @foreach($allDocuments as $document)
                                <option style="color: black;" value="{{ $document->id }}">{{ $document->consecutive.'-'.$document->name_client }}</option>
                                @endforeach
                            </select>
                            <span class="text-blue-500">Si no selecciona ninguna factura, el pago se aplicara a las facturas mas antiguas
                        </span>
                            @error('id_document') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        
                    </div>
                    <!-- end form-group -->
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
                            @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Referencia: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="text" name="reference" wire:model="reference" placeholder="Referencia" data-parsley-required="true" />
                            @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="number" name="mount" wire:model="mount" placeholder="Monto" data-parsley-required="true" />
                            @error('mount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-6 col-form-label">Observaciones: <span class="text-danger"></span></label>
                        <div class="col-6">
                            <input data-toggle="number" data-placement="after" class="form-control" type="textarea" name="observations" wire:model="observations" placeholder="Observaciones" data-parsley-required="true" />
                            @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
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
    window.addEventListener('payment_modal_hide', event => {
        $('#paymentModal').modal('hide');
    });
</script>