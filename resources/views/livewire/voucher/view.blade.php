<!-- Modal -->


<div wire:ignore.self class="modal modal-message fade" id="viewExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="overflow-y: scroll;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #E1E6E4;">
            <div class="modal-header" style="width:95%;">
                <h4 class="modal-title text-center" id="t_dView"> </h4>
                <h6 class="text-inverse"><b>Comprobante electronico: {{ $key }}</b></h6>
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
                                    <h6 class="text-inverse"><b>Cedula: </b>{{ $id_card }}</h6>
                                    <h6 class="text-inverse"><b>Nombre: </b>{{ $name }}</h6>
                                    <h6 class="text-inverse"><b>Tel: </b>{{ $phone }}</h6>
                                    <h6 class="text-inverse"><b>Correo: </b>{{ $mail }}</h6>
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-19 m-b-5">
                                    <div class="form-group row ">
                                        <label class="col-lg-4 col-form-label">Sucursal: <span class="text-danger"></span></label>
                                        <div class="col-lg-8">
                                            <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_branch_office" id="id_branch_office" wire:change='change_bo' wire:model="id_branch_office">
                                                @foreach($allBranchOffices as $bo)
                                                <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                                @endforeach
                                            </select> @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-lg-4 col-form-label">Terminal: <span class="text-danger"></span></label>
                                        <div class="col-lg-8">
                                            <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_terminal" id="id_terminal" wire:model="id_terminal">
                                                @foreach($allTerminals as $terminal)
                                                <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                                                @endforeach
                                            </select> @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-lg-4 col-form-label">Categoria MH: <span class="text-danger"></span></label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                                @foreach($allMHCategories as $mhCategory)
                                                <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                                @endforeach
                                            </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                        </div>
                                    </div>
                                    <div class="form-group row ">
                                        <label class="col-lg-4 m-b-2 col-form-label text-black">Actividad Economica: <span class="text-danger"></span></label>
                                        <div class="col-8">
                                            <select class="form-control" name="number_ea" id="number_ea" wire:model="number_ea">
                                                <option style="color: black;" value="">Ninguna</option>
                                                @foreach($allEconomicActivities as $ea)
                                                <option style="color: black;" value="{{ $ea->number }}">{{ $ea->number.'-'.$ea->name_ea }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </address>
                            </div>
                            <div class="invoice-date">
                                <address class="m-t-5 m-b-5">
                                    <h5 id="consecutiveView" name="consecutiveView"> <b>Consecutivo: </b>{{ $consecutive }}</h5>
                                    <!-- begin form-group -->
                                    <h6 class="text-inverse"><b>Fecha:</b> {{ $date }}</h6>
                                    <h6 class="text-inverse"><b>Condicion de venta:</b> {{ $sale_condition}}</h6>
                                    <h6 class="text-inverse"><b>Metodo de pago:</b> {{ $payment_method}}</h6>
                                    <h6 class="text-inverse"><b> Moneda: </b>{{ $currency}}</h6>
                                    <h6 class="text-inverse"><b> Tipo de Cambio: </b>{{ $exchange_rate }}</h6>
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
                                            @if(Auth::user()->currentTeam->plan_id != 1)
                                            <th class="text-center" width="1%">CUENTA GASTO</th>
                                            <th class="text-center" width="1%">COD. INTERNO</th>
                                            @else
                                            <th class="text-center" width="10%">Tipo</th>
                                            @endif
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
                                        @foreach($details as $index => $detail)
                                        <tr class="gradeU">
                                            <td width="1%">{{ $detail->line }}</td>
                                            @if(Auth::user()->currentTeam->plan_id != 1)
                                            <td width="25%" class="text-center">
                                                <div>
                                                    <input type="text" name="id_count" hidden>
                                                    <input class="form-control" {{  (isset($productsUpdate[$index]) )?(($productsUpdate[$index] == "")?'':'disabled' ):'' }} type="text" name="name_count" list="counts" placeholder="Ninguna" wire:model="countsUpdate.{{ $index }}">
                                                    <datalist id="counts">
                                                        @foreach($allCounts as $count)
                                                        <option style="color: black;">{{ $count->name }}</option>
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                            </td>
                                            <td width="25%" class="text-center">
                                                <div>
                                                    <input type="text" name="id_product" hidden>
                                                    <input {{  (isset($countsUpdate[$index]) )?(($countsUpdate[$index] == "")?'':'disabled' ):'' }} class="form-control" type="text" name="name_product" list="products" placeholder="Ninguno" wire:model="productsUpdate.{{ $index }}">
                                                    <datalist id="products">
                                                        @foreach($allProducts as $product)
                                                        <option style="color: black;" selected data-id="{{ $product->id }}">{{ $product->description }}</option>
                                                        @endforeach
                                                    </datalist>
                                                </div>
                                            </td>
                                            @else
                                            <td width="25%" class="text-center">
                                                <input type="radio" id="compra.{{ $index }}" name="typeLine.{{ $index }}" value="Compra" wire:model="type_line.{{ $index }}">
                                                <label for="compra.{{ $index }}">Compra</label>
                                                <input type="radio" id="gasto.{{ $index }}" name="typeLine.{{ $index }}" value="Gasto" wire:model="type_line.{{ $index }}">
                                                <label for="gasto.{{ $index }}">Gasto</label>
                                            </td>
                                            @endif
                                            <td width="10%" class="text-center">{{ $detail->cabys }}</td>
                                            <td width="25%" class="text-center">{{ $detail->detail }}</td>
                                            <td width="7%" class="text-center">{{ $detail->sku }}</td>
                                            <td width="7%" class="text-center">{{ $detail->qty }}</td>
                                            <td width="25%" class="text-center">{{ $detail->price }}</td>
                                            <td width="7%" class="text-center">{{ (isset($detail->discounts))?$detail->discounts:0 }}</td>
                                            <td width="7%" class="text-center">{{ (isset(json_decode($detail->taxes)->mount) && json_decode($detail->taxes)->mount != "" )?json_decode($detail->taxes)->mount:0 }}</td>
                                            <td width="7%" class="text-center">{{ $exo= (isset(json_decode($detail->taxes)->exoneration) && json_decode($detail->taxes)->exoneration != "" )?json_decode($detail->taxes)->exoneration->AmountExoneration:0 }}</td>
                                            <td width="7%" class="text-center">{{ $detail->total_amount_line }}</td>
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
                                            <span class="text-inverse">{{$subtotal}}</span>
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
                                            <span class="text-inverse">{{$tax}}</span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price">
                                            <small>EXONERADO</small>
                                            <span class="text-inverse">{{$exoneration}}</span>
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

                        @if(count($allPayments)>0)
                        <!-- begin invoice-note -->
                        <div class="invoice-price">
                            <div class="invoice-price-right" style="background-color:#192743;">
                                <small>PENDIENTE</small> <span class="f-w-900">{{$pending_amount}}</span>
                            </div>
                        </div>
                        @endif
                        <!-- otherchages -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <div class="panel-heading">
                                <h4 class="panel-title">Otros Cargos</h4>
                                <div class="panel-heading-btn" {{ $result = ($type_doc == '1')?'hidden':''}}>
                                    <a class="btn btn-primary" wire:click="otherChargeChange()">Agregar otros cargos</a>
                                </div>
                            </div>
                            @if($otherCharge ==1)
                            <!-- begin panel-body -->
                            <div class="panel-body panel-form" style="background-color: darkgrey;">
                                <!-- begin invoice-content -->
                                <div class="invoice-content">
                                    <!-- begin table-responsive -->
                                    <div class="table-responsive bg-gray-400">
                                        <table class="table table-other" id="DetalleOtherServicio" name="DetalleOtherServicio">
                                            <thead>
                                                <tr class="text-white" style="background-color: #192743;">
                                                    <th class="text-center" width="1%">N°</th>
                                                    <th class="text-center" width="20%">TIPO CARGO</th>
                                                    <th class="text-center" width="15%">CEDULA TERCERO</th>
                                                    <th class="text-center" width="24%">NOMBRE TERCERO</th>
                                                    <th class="text-center" width="25%">DESCRIPCION CARGO</th>
                                                    <th class="text-center" width="10%">MONTO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allOtherCharges as $index => $charge)
                                                <tr class="gradeU">
                                                    <td width="1%" class="text-center">{{ $index+1 }}</td>
                                                    <td width="20%" class="text-center">{{ $charge['type_document_other'] }}</td>
                                                    <td width="15%" class="text-center">{{ $charge['idcard_other'] }}</td>
                                                    <td width="24%" class="text-center">{{ $charge['name_other'] }}</td>
                                                    <td width="25%" class="text-center">{{ $charge['detail_other_charge'] }}</td>
                                                    <td width="10%" class="text-center">{{ number_format($charge['amount_other'],2,'.',',') }}</td>
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
                                                    <small>TOTAL OTROS CARGOS</small>
                                                    <span class="text-inverse" id="sub_total">
                                                        <h5>{{ number_format($total_other_charges,2,'.',',') }}</h5>
                                                    </span>
                                                </div>
                                                <div class="sub-price">
                                                    <i class="fa fa-plus text-muted"></i>
                                                </div>
                                                <div class="sub-price">
                                                    <small>PRODUCTOS O SERVICIOS</small>
                                                    <span class="text-inverse" id="total_discount">
                                                        <h5>{{ number_format($total ,2,'.',',') }}</h5>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invoice-price-right" style="background-color: #192743;">
                                            <small>TOTAL DOCUMENTO</small> <span class="f-w-600" id="total_invoice">{{ number_format(($total_other_charges+$total),2,'.',',') }}</span>
                                        </div>
                                    </div>
                                    <!-- end invoice-price -->
                                </div>
                                <!-- end invoice-content -->
                            </div>
                            <!-- end panel-body -->
                            @endif
                        </div>
                        <!-- end otherchages -->
                        <!-- begin invoice-note -->
                        <div class="invoice-note">
                            <!-- begin panel -->
                            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                                <div class="col-lg-12">
                                    <label class="col-lg-4 col-form-label">Notas</label>
                                    <textarea class="form-control" name="note" id="note" wire:model="note" rows="5"></textarea>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body panel-form">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Tipo de documento de referencia</label>
                                        <div class="col-lg-8">
                                            <select disabled class="form-control" name="id_type_reference" id="id_type_reference" wire:model="id_type_reference">
                                                <option style="color: black;" value="">No posee referencia...</option>
                                                @foreach($allTypeReferences as $typeReference)
                                                <option style="color: black;" value="{{ $typeReference->code }}">{{ $typeReference->document }}</option>
                                                @endforeach
                                            </select> @error('id_type_reference') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                        </div>
                                    </div>
                                    <div id="reference" {{ $result = ($id_type_reference == '')?'hidden':''}}>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Clave o consecutivo</label>
                                            <div class="col-lg-8">
                                                <input disabled class="form-control" type="text" name="number_reference" id="number_reference" wire:model="number_reference" />
                                                @error('number_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Fecha de Emisión</label>
                                            <div class="col-lg-8">
                                                <input disabled type="datetime-local" class="form-control" id="date_reference" name="date_reference" placeholder="Fecha de emisión" wire:model="date_reference" />
                                                @error('date_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Codigo Referencia</label>
                                            <div class="col-lg-8">
                                                <select disabled class="form-control" name="id_reference" id="id_reference" wire:model="id_reference">
                                                    @foreach($allReferences as $reference)
                                                    <option style="color: black;" value="{{ $reference->code }}">{{ $reference->description }}</option>
                                                    @endforeach
                                                </select> @error('id_reference') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Razón</label>
                                            <div class="col-lg-8">
                                                <input disabled class="form-control" type="text" name="reason" id="reason" wire:model="reason" />
                                                @error('reason') <span class="text-red-500">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end panel-body -->
                                <br>
                                <div class="row" style="float: right;">
                                    <input type="checkbox" class="m-r-5" name="gasper" wire:model="gasper">
                                    <label for="gasper" style="color: red;"> No tomar en cuenta para contabilidad</label>
                                </div>
                                <br>
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
                </form>
            </div>
            <div class="modal-footer" style="width:90%;">
                <button type="submit" class="btn text-white" style="background-color:#010e2c;" wire:click.prevent="updateVoucher()" onclick="this.disabled=true;">Actualizar</button>
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