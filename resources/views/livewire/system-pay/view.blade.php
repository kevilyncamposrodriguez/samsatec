<!-- Modal -->


<div wire:ignore.self class="modal modal-message fade" id="viewSytemPayModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="overflow-y: scroll;" aria-hidden="true">
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
                                    <h6 class="text-inverse"><b>Estado:</b> Pagado</h6>
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
                                        <td width="54%" class="text-center">{{ $plan_name }}</td>
                                        <td width="15%" class="text-center">{{ $qty }}</td>
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