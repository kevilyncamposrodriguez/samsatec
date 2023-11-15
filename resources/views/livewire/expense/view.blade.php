<!-- Modal -->
<div wire:ignore.self class="modal fade" id="viewExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" id="t_dView"> </h4>
                <h6 class="text-inverse">Comprobante electronico: {{ $key }}</h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">


                <!-- begin invoice -->
                <div class="invoice">
                   
                    <!-- begin invoice-header -->
                    <div class="invoice-header">
                        <div class="invoice-from">
                            <address class="m-t-5 m-b-5">
                                <h5> Emisor:</h5>
                                <!-- begin form-group -->
                                <h6 class="text-inverse">Cedula: {{ $id_card_e }}</h6>
                                <h6 class="text-inverse">Nombre: {{ $name_e }}</h6>
                                <h6 class="text-inverse">Tel: {{ $phone_e }}</h6>
                                <h6 class="text-inverse">Correo: {{ $mail_e }}</h6>
                            </address>
                            <!-- end form-group -->
                        </div>
                        <div class="invoice-to">
                            <address class="m-t-5 m-b-5">
                                <h5> Receptor:</h5>
                                <!-- begin form-group -->
                                <h6 class="text-inverse">Cedula: {{ $id_card_r }}</h6>
                                <h6 class="text-inverse">Nombre: {{ $name_r }}</h6>
                                <h6 class="text-inverse">Tel: {{ $phone_r }}</h6>
                                <h6 class="text-inverse">Correo: {{ $mail_r }}</h6>

                            </address>
                        </div>
                        <div class="invoice-date">
                            <address class="m-t-5 m-b-5">
                                <h5 id="consecutiveView" name="consecutiveView"> Consecutivo: {{ $consecutive_view }}</h5>
                                <!-- begin form-group -->

                                <h6 class="text-inverse">Fecha: {{ $date_view }}</h6>
                                <h6 class="text-inverse">Condicion de venta: {{ $sale_condition_view }}</h6>
                                <h6 class="text-inverse">Metodo de pago: {{ $payment_method_view }}</h6>
                                <h6 class="text-inverse">Moneda: {{ $currency_view }}</h6>
                                <h6 class="text-inverse">Tipo de Cambio: {{ $exchange_rate }}</h6>
                            </address>
                        </div>
                    </div>
                    <!-- end invoice-header -->
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        <!-- begin table-responsive -->
                        <div class="table-responsive">
                            <table class="table table-invoice">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">N°</th>
                                        <th class="text-center" width="10%">CABYS</th>
                                        <th class="text-center" width="25%">DESCRIPCION</th>
                                        <th class="text-center" width="7%">UNIDAD</th>
                                        <th class="text-center" width="7%">CANTIDAD</th>
                                        <th class="text-center" width="25%">PRECIO UNID.</th>
                                        <th class="text-center" width="7%">DECUENTO</th>
                                        <th class="text-center" width="7%">IMPUESTOS</th>
                                        <th class="text-center" width="7%">EXONERACION</th>
                                        <th class="text-center" width="7%">TOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($detail_view as $index=>$detail)
                                    <tr class="gradeU">
                                        <td width="1%">{{ $detail['line'] }}</td>
                                        <td width="10%" class="text-center">{{ $detail["code"] }}</td>
                                        <td width="25%" class="text-center">{{ $detail["description"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["sku"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["qty"] }}</td>
                                        <td width="25%" class="text-center">{{ $detail["price"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["discount"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["tax"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["exoneration"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["total"] }}</td>
                                    </tr>

                                    @endforeach
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
                                        <span class="text-inverse" id="sub_totalView">
                                            <h1><small class="f-w-400">{{$subtotal_view}}</small></h1>
                                        </span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-minus "></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>DESCUENTO</small>
                                        <span class="text-inverse" id="total_discountView">
                                            <h1><small class="f-w-400">{{$discount_view}}</small></h1>
                                        </span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-plus text-muted"></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>IMPUESTO</small>
                                        <span class="text-inverse" id="total_taxView">
                                            <h1><small class="f-w-400">{{$tax_view}}</small></h1>
                                        </span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-minus text-muted"></i>
                                    </div>
                                    <div class="sub-price text-small">
                                        <small>EXONERADO</small>
                                        <span class="text-inverse text-small" id="total_exonerationView">
                                            <h1 class="f-w-400"><small>{{$exoneration_view}}</small></h1>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="invoice-price-left">
                                <small>TOTAL</small> <span class="f-w-400" id="total_invoiceView"><small>{{$total_view}}</small></span>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <!-- begin invoice-note -->
                    <div class="invoice-note">
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">

                            <!-- begin panel-body -->
                            <div class="panel-body panel-form">
                                <div class="form-group row">

                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>
                        <!-- end panel -->
                    </div>
                    <!-- end invoice-note -->
                    <!-- begin invoice-footer -->
                    <div class="invoice-footer">
                        <p class="text-center m-b-5 f-w-600">
                            Vista Generada segun el xml del documento electrónico
                        </p>
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
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>

            </div>
        </div>
    </div>
</div>