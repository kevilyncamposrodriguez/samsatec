<!-- Modal -->
<div wire:ignore.self class="modal fade " id="newChargeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo Cobro de Sistema</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin invoice-header -->
                    <div class="invoice-header p-t-10 p-b-10 row">

                        <!-- begin invoice -->
                        <div class="invoice" style="width: 100%;">

                            <div class="row">
                                <div class="col-md-8">
                                    <strong>Facturar a:</strong>
                                    <div class="input-group">
                                        <input class="form-control" wire:change="changeCompany($event.target.value)" type="text" name="name_company" list="companies" placeholder="Ninguno" wire:model="name_company">
                                        <datalist id="companies">
                                            @foreach($allCompanies as $company)
                                            <option style="color: black;" value="{{ $company->name }}" label="{{ $company->id_card }}">
                                                @endforeach
                                        </datalist>
                                    </div>
                                    @error('name_company') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    <strong>Estado de facturación:</strong>
                                    <div class="input-group">
                                        <label for="state" style="color: red;" class="mt-2">Pagado?</label>
                                        <input type="checkbox" class="mt-2 ml-4" name="state" wire:model="state">
                                    </div>
                                    @error('state') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Fecha de Inicio:</strong>
                                    <div class="input-group">
                                        <input type="date" class="form-control" name="start_pay" wire:model="start_pay">
                                    </div>
                                    @error('start_pay') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6" {{ ($state)?'':'hidden' }}>
                                    <strong>id de pago:</strong>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="id_pay" wire:model="id_pay">
                                    </div>
                                    @error('id_pay') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <!-- begin invoice-content -->
                            <div class="invoice-content">
                                <!-- begin table-responsive -->
                                <div class="table-responsive bg-gray-400">
                                    <table class="table table-invoice">
                                        <thead>
                                            <tr class="text-white" style="background-color:#192743;">
                                                <th class="text-center" width="1%">N°</th>
                                                <th class="text-center" width="54%">DETALLE</th>
                                                <th class="text-center" width="15%">MES(ES)</th>
                                                <th class="text-center" width="15%">MENSUALIDAD</th>
                                                <th class="text-center" width="15%">SUBTOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <td width="1%" class="text-center">1</td>
                                            <td width="54%" class="text-center">
                                                <select class="form-control" wire:change="changePlan()" name="plan" wire:model="id_plan">
                                                    @foreach($allPlans as $p)
                                                    <option style="color: black;" value="{{ $p->id }}">{{ $p->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="15%" class="text-center"><input type="number" id="qty" wire:change="changeQTY()" class="form-control" name="qty" wire:model="qty" /></td>
                                            <td width="15%" class="text-center">{{ $price }}</td>
                                            <td width="15%" class="text-center">{{ $monto }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                                <!-- begin invoice-price -->
                                <div class="invoice-price">
                                    <div class="invoice-price-left">
                                        <div class="invoice-price-row">
                                            <div class="sub-price">
                                                <small>SUBTOTAL</small>
                                                <span class="text-inverse">{{$monto}}</span>
                                            </div>
                                            <div class="sub-price">
                                                <i class="fa fa-minus text-muted"></i>
                                            </div>
                                            <div class="sub-price">
                                                <small>DESCUENTO</small>
                                                <span class="text-inverse">{{$discount}}</span>
                                            </div>
                                            <div class="sub-price">
                                                <i class="fa fa-plus text-muted"></i>
                                            </div>
                                            <div class="sub-price">
                                                <small>IMPUESTO</small>
                                                <span class="text-inverse">{{$iva}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-price-right" style="background-color:#192743;">
                                        <small>TOTAL</small> <span class="f-w-600">{{$total}}</span>
                                    </div>
                                </div>
                                <!-- end invoice-price -->
                            </div>
                            <!-- end invoice-content -->
                        </div>
                        <!-- end invoice -->

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('newCharge_modal_hide', event => {
        $('#newChargeModal').modal('hide');
    });
</script>