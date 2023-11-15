<!-- Modal -->
<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pagos de Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Factura a pagar: <span class="text-danger"></span></label>
                        <div class="col-3">
                            <input class="form-control" type="text" disabled name="keyDoc" wire:model="keyDoc" />
                            @error('keyDoc') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Cliente: <span class="text-danger"></span></label>
                        <div class="col-5">
                            <input class="form-control" type="text" disabled name="client" wire:model="client" />
                            @error('client') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Referencia: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" data-placement="after" class="form-control" type="text" name="reference" wire:model="reference" placeholder="Referencia" data-parsley-required="true" />
                            @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                        <label class="col-lg-2 col-form-label">Cuenta Bancaria: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <select class="form-control" name="id_count" wire:model="id_count">
                                <option style="color: black;" value="">Ninguna</option>
                                @foreach($allCounts as $count)
                                <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                @endforeach
                            </select>
                            @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>

                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Fecha de pago: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input class="form-control" type="date" name="date" wire:model="date" />
                            @error('date') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Monto: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="mountPay" wire:model="mountPay" placeholder="Monto" />
                            @error('mountPay') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Observaciones: <span class="text-danger"></span></label>
                        <div class="col-7">
                            <input class="form-control" type="textarea" name="observations" wire:model="observations" placeholder="Observaciones" />
                            @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-3">
                            @if(!$isUpdatePay)
                            <button type="button" {{ ($mountPay<=0)?'disabled':'' }} title="Nuevo Pago" wire:click.prevent="storePay()" onclick="this.disabled=true;" class="btn btn-blue col-12">Agregar Pago</button>
                            @else
                            <button type="button" title="Modificar Pago" wire:click.prevent="updatePay()" onclick="this.disabled=true;" class="btn btn-blue col-12">Editar Pago</button>
                            @endif
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
                <!-- begin panel -->
                <br>
                <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                    <div class="panel panel-inverse">
                        <!-- begin panel-heading -->
                        <div class="panel-heading">
                            <h4 class="panel-title">Listado de pagos</h4>
                            <div class="panel-heading-btn">

                            </div>
                        </div>
                        <!-- end panel-heading -->
                        @if (session()->has('message'))
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                            <div class="flex">
                                <div>
                                    <p class="text-sm">{{ session('message') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!-- begin panel-body -->
                        <div class="panel-body">
                            <table id="data-table-pay" class="table table-striped table-bordered table-td-valign-middle">
                                <thead>
                                    <tr>
                                        <th data-orderable="false" width="10%">Acciones</th>
                                        <th class="text-nowrap" width="10%">Fecha</th>
                                        <th width="15%">Cuenta Bancaria</th>
                                        <th width="15%">Referencia</th>
                                        <th class="text-nowrap" width="5%">Monto</th>
                                        <th width="50%">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allPayments as $payment)
                                    <tr class="gradeU">
                                        <td width="15%" class="text-center">
                                            <div class="btn-group btn-group-justified">
                                                <button wire:click="editPay({{ $payment->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                                <button wire:click="deletePay('{{ $payment->id}}' , '{{$payment->mount}}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                        <td width="15%" class="text-center">{{ date("Y-m-d", strtotime($payment->date)) }}</td>
                                        <td class="text-center">{{ ($payment->counts)?$payment->counts:0 }}</td>
                                        <td class="text-center">{{ $payment->reference }}</td>
                                        <td class="text-center">{{ $payment->mount }}</td>
                                        <td class="text-center">{{ $payment->observations }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </div>
                <!-- begin invoice-note -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('payment_modal_hide', event => {
        $('#paymentModal').modal('hide');
    });
</script>