<!-- Modal -->


<div wire:ignore.self class="modal modal-message fade" id="paySytemPayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="overflow-y: scroll;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #E1E6E4;">
            <div class="modal-header" style="width:95%;">
                <h4 class="modal-title text-center" id="t_dView"> </h4>
                <h6 class="text-inverse"><b>Id Factura: {{ 'FS-' . str_pad($id_fact, 10, "0", STR_PAD_LEFT) }}</b></h6>
                <button type="button" class="close" data-dismiss="modal" wire:click="cleanInputs()" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="width:95%;">
                <form class="form-horizontal form-bordered">
                    <!-- begin invoice -->
                    <div class="invoice">
                        <!-- begin invoice-header -->
                        <div class="invoice-header">
                            <div class="invoice-from">
                                <address class="m-t-5 m-b-5">
                                    <h5 class="f-w-900 text-inverse"> <b>Emisor:</b></h5>
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse"><b>Cedula: </b>{{ $e_id_card }}</h6>
                                    <h6 class="text-inverse"><b>Nombre: </b>{{ $e_name }}</h6>
                                    <h6 class="text-inverse"><b>Tel: </b>{{ $e_phone }}</h6>
                                    <h6 class="text-inverse"><b>Correo: </b>{{ $e_mail }}</h6>
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-date">
                                <address class="m-t-5 m-b-5">
                                    <h5 id="consecutiveView" name="consecutiveView"> <b>Fecha:</b> {{ $date }}</h5>
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse"><b>Estado:</b> Pendiente</h6>
                                </address>
                            </div>
                        </div>
                        <!-- end invoice-header -->
                        <!-- begin invoice-content -->
                        <div class="invoice-content">
                            <!-- begin table-responsive -->
                            <div class="table-responsive bg-gray-400">
                                <table class="table table-invoice">
                                    <thead>
                                        <tr class="text-white" style="background-color:#192743;">
                                            <th class="text-center" width="1%">N°</th>
                                            <th class="text-center" width="54%">DETALLE</th>
                                            <th class="text-center" width="15%">CANTIDAD</th>
                                            <th class="text-center" width="15%">PRECIO</th>
                                            <th class="text-center" width="15%">SUBTOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td width="1%" class="text-center">1</td>
                                        <td width="54%" class="text-center">
                                            <select class="form-control" name="plan_id" id="plan_id" wire:model="plan_id">
                                                @foreach($allPlans as $p)
                                                <option style="color: black;" value="{{ $p->id }}">{{ $p->name }}</option>
                                                @endforeach
                                            </select></td>
                                        <td width="15%" class="text-center"><input type="number" id="qty" class="form-control" name="qty" wire:model="qty" /></td>
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
                        <div class="mt-4">
                            <h5 class="text-center text-black"><strong>Datos para pago</strong></h5>
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="text-black" for="type_idcard">{{ __('Tipo de Cédula') }}<span class="text-danger">*</span></label>
                                    <select class="form-control" name="type_idcard" id="type_idcard" wire:model="type_idcard">
                                        <option style="color: black;" value="0">Cédula nacional</option>
                                        <option style="color: black;" value="1">DIMEX</option>
                                        <option style="color: black;" value="2">Pasaporte</option>
                                        <option style="color: black;" value="3">Licencia de conducir</option>
                                        <option style="color: black;" value="4">Identificación del Gobierno</option>
                                        <option style="color: black;" value="5">Otro</option>                                      
                                    </select>
                                </div>
                                <div class="col-md-2">

                                    <label class="text-black" for="idcard_count">{{ __('Cédula del propietario de la cuenta') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="text" id="idcard_count" class="form-control" wire:model="idcard_count" name="idcard_count" placeholder="Cedula del propietario" />
                                            <x-input-error for="idcard_count"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class=" text-black" for="name_count">{{ __('Nombre completo del propietario de la cuenta') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-6">
                                            <input type="text" id="name_count" class="form-control" name="name_count" placeholder="Nombre " wire:model="name_count" />
                                            <x-input-error for="name_count"></x-input-error>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="lastname_count" class="form-control" name="lastname_count" placeholder="Apellidos" wire:model="lastname_count" />
                                            <x-input-error for="lastname_count"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label text-black" for="iban">{{ __('Numero de cuenta (IBAN)') }}<span class="text-danger">*</span></label>
                                    <div class="row m-b-15">
                                        <div class="col-md-12">
                                            <input type="text" id="iban" class="form-control" name="iban" placeholder="Cuenta IBAN" wire:model="iban" />
                                            <x-input-error for="iban"></x-input-error>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- begin invoice-footer -->
                        <div class="invoice-footer">
                            <p class="text-center m-b-5 f-w-600">
                                GRACIAS POR USAR NUESTROS SISTEMAS
                            </p>
                            <p class="text-center">
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i> samsatec.com</span>
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> T:8888-8888</span>
                                <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> info@samsatec.com</span>
                            </p>
                        </div>
                        <!-- end invoice-footer -->
                    </div>
                    <!-- end invoice -->
                </form>
            </div>
            <div class="modal-footer" style="width:90%;">
                <button type="submit" class="btn text-white" style="background-color:#010e2c;" wire:click.prevent="pay({{ $id_fact }})" onclick="this.disabled=true;">Pagar</button>
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>

            </div>
        </div>

    </div>

</div>
<script>
    window.addEventListener('viewVoucher_modal_hide', event => {
        $('#viewExpenseModal').modal('hide');
    });
</script>