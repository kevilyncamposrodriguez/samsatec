<!-- Modal -->
<div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="width:980px;">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Cuotas de crédito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Número de Financiamiento: <span class="text-danger"></span></label>
                        <div class="col-3">
                            <input class="form-control" type="text" disabled name="credit_number" wire:model="credit_number" />
                            @error('credit_number') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Financiera: <span class="text-danger"></span></label>
                        <div class="col-5">
                            <input class="form-control" type="text" disabled name="financial_entity" wire:model="financial_entity" />
                            @error('financial_entity') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Fecha de pago: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input class="form-control" type="date" name="date_pay" wire:model="date_pay" />
                            @error('date_pay') <span class="text-red-500">{{ $message }}</span>@enderror
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
                        <label class="col-lg-2 col-form-label">Mes de pago: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <select class="form-control" name="month_payment" wire:model="month_payment">
                                <option style="color: black;" value="1">Enero</option>
                                <option style="color: black;" value="2">Febrero</option>
                                <option style="color: black;" value="3">Marzo</option>
                                <option style="color: black;" value="4">Abril</option>
                                <option style="color: black;" value="5">Mayo</option>
                                <option style="color: black;" value="6">Junio</option>
                                <option style="color: black;" value="7">Julio</option>
                                <option style="color: black;" value="8">Agosto</option>
                                <option style="color: black;" value="9">Setiembre</option>
                                <option style="color: black;" value="10">octubre</option>
                                <option style="color: black;" value="11">Noviembre</option>
                                <option style="color: black;" value="12">Diciembre</option>
                            </select>
                            @error('month_payment') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Aporte al Capital: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="capital_contribution" wire:model="capital_contribution" placeholder="Aporte al capital" />
                            @error('capital_contribution') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Intereses del préstamo: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="loan_interest" wire:model="loan_interest" placeholder="Intereses del prestamo" />
                            @error('loan_interest') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Otros Intereses: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="other_interest" wire:model="other_interest" placeholder="Otros Intereses" />
                            @error('other_interest') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Intereses por moratoria: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="interest_late_payment" wire:model="interest_late_payment" placeholder="Intereses por moratoria" />
                            @error('interest_late_payment') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Seguro: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="safe" wire:model="safe" placeholder="Seguros" />
                            @error('safe') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Aval: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="endorsement" wire:model="endorsement" placeholder="Aval" />
                            @error('endorsement') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Póliza: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="policy" wire:model="policy" placeholder="Poliza" />
                            @error('policy') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Ahorro: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="saving" wire:model="saving" placeholder="Ahorros" />
                            @error('saving') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <label class="col-lg-2 col-form-label">Otros Ahorros: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="other_saving" wire:model="other_saving" placeholder="Otros Ahorros" />
                            @error('other_saving') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row ">
                        <label class="col-lg-2 col-form-label">Total de Cuota: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input data-toggle="number" class="form-control" type="number" name="total_fee" wire:model="total_fee" placeholder="Otros Ahorros" />
                            @error('total_fee') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-3">
                            @if(!$isUpdatePay)
                            <button type="button" title="Nueva cuota" wire:click.prevent="storePay()" onclick="this.disabled=true;" class="btn btn-blue col-12">Agregar Cuota</button>
                            @else
                            <button type="button" title="Modificar Cuota" wire:click.prevent="updatePay()" onclick="this.disabled=true;" class="btn btn-blue col-12">Editar Cuota</button>
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
                            <h4 class="panel-title">Listado de cuotas</h4>
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
                                        <th data-orderable="false" width="1%">N</th>
                                        <th data-orderable="false" width="10%">Acciones</th>
                                        <th class="text-nowrap" width="10%">Fecha</th>
                                        <th width="15%">Cuenta Bancaria</th>
                                        <th width="15%">Mes pago</th>
                                        <th class="text-nowrap" width="5%">Aport Cap</th>
                                        <th width="15%">Intereses</th>
                                        <th class="text-nowrap" width="5%">Interes Moratorio</th>
                                        <th width="15%">Seguro</th>
                                        <th width="15%">Aval</th>
                                        <th class="text-nowrap" width="5%">Póliza</th>
                                        <th width="15%">Ahorros</th>
                                        <th class="text-nowrap" width="5%">Total Cuota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allFees as $index=>$fee)
                                    <tr class="gradeU">
                                        <td width="1%" class="text-center">{{ $index+1 }}</td>
                                        <td width="10%" class="text-center">
                                            <div class="btn-group btn-group-justified">
                                                <button wire:click="editPay({{ $fee->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                                <button wire:click="deletePay('{{ $fee->id}}' , '{{$fee->total_fee}}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                        <td width="10%" class="text-center">{{ substr($fee->date_pay,0,10) }}</td>
                                        <td width="12%" class="text-center">{{ $fee->count }}</td>
                                        <td width="15%" class="text-center">{{ $months[$fee->month_payment-1] }}</td>
                                        <td width="17%" class="text-center">{{ $fee->capital_contribution }}</td>
                                        <td width="8%" class="text-center">{{ ($fee->loan_interest+$fee->other_interest) }}</td>
                                        <td width="10%" class="text-center">{{ $fee->interest_late_payment }}</td>
                                        <td width="9%" class="text-center">{{ $fee->safe }}</td>
                                        <td width="9%" class="text-center">{{ $fee->endorsement }}</td>
                                        <td width="9%" class="text-center">{{ $fee->policy }}</td>
                                        <td width="9%" class="text-center">{{ ($fee->saving + $fee->other_saving) }}</td>
                                        <td width="9%" class="text-center">{{ ($fee->total_fee ) }}</td>
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