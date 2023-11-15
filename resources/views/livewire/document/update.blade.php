<!-- Modal -->
<div wire:ignore.self class="modal modal-message fade" id="documentUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #E1E6E4;">
            <div class="modal-header text-center" style="width:80%;">
                <h4 class="modal-title text-center"><strong class="text-center">
                        <font size=5>{{$type_document}}</font>
                    </strong></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="width:80%;">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin invoice -->
                    <div class="invoice">
                        <!-- begin invoice-header -->
                        <div class="invoice-header">
                            <div class="invoice-from">
                                <address class="m-t-5 m-b-5">
                                    <!-- begin form-group -->
                                    <div wire:ignore>
                                        <strong>Facturar a:</strong>
                                        <div class="row m-1">
                                            <!-- begin form-group -->
                                            <select class="form-control client-select2 col-lg-10" name="id_clientU" id="id_clientU" onchange="changeClientU()">
                                                <option style="color: black;" value="">Seleccionar...</option>
                                                @foreach($allClients as $client)
                                                <option style="color: black;" value="{{ $client->id }}">{{ $client->name_client }}</option>
                                                @endforeach
                                            </select>
                                            <a title="Nuevo Cliente" data-toggle="modal" data-target="#clientModal" class="btn btn-primary btn-md  col-lg-2" wire:click="$emit('refresh', 0)"><i style="color: white;" class="fa fa-user-plus"></i></a>
                                            @error('id_clientU') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                        </div>
                                    </div>
                                    <br>
                                    <div wire:ignore>
                                        <strong>Moneda:</strong>
                                        <select onchange="changeCurrencyU()" class="form-control currency-select2" data-parsley-required="true" name="code_currencyU" id="code_currencyU">
                                            @foreach($allCurrencies as $currency)
                                            <option style="color: black;" value="{{ $currency->code }}">{{ $currency->code.' - '.$currency->currency }}</option>
                                            @endforeach
                                        </select> @error('code_currencyU') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    </div>
                                    <strong> Medio de pago:</strong>
                                    <select class="form-control" data-parsley-required="true" name="code_payment_method" id="code_payment_method" wire:model="code_payment_method">
                                        @foreach($allPaymentMethods as $paymentMethod)
                                        <option style="color: black;" value="{{ $paymentMethod->code }}">{{ $paymentMethod->payment_method }}</option>
                                        @endforeach
                                    </select> @error('code_payment_method') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Clasificación MH:</strong>
                                    <select class="form-control" data-parsley-required="true" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                        @foreach($allMHCategories as $mhCategory)
                                        <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                        @endforeach
                                    </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5">

                                    <strong> Condición compra:</strong>
                                    <select class="form-control" data-parsley-required="true" name="code_sale_condition" id="code_sale_condition" wire:model="code_sale_condition">
                                        @foreach($allSaleConditions as $saleCondition)
                                        <option style="color: black;" value="{{ $saleCondition->code }}">{{ $saleCondition->sale_condition }}</option>
                                        @endforeach
                                    </select> @error('code_sale_condition') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Plazo crédito:</strong>
                                    <input class="form-control" type="number" name="term" id="term" value="1" min="1" max="99" data-parsley-required="true" wire:model="term" />
                                    @error('term') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Tipo de cambio:</strong>
                                    <input class="form-control" type="number" name="exchange_rate" id="exchange_rate" data-parsley-required="true" wire:model="exchange_rate" />
                                    @error('exchange_rate') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Lista de precios:</strong>
                                    <select class="form-control" name="price_list_id" id="price_list_id" onchange="changeList()" wire:model="price_list_id">
                                        <option style="color: black;" value="">Por Defecto</option>
                                        @foreach($allPriceList as $pList)
                                        <option style="color: black;" value="{{ $pList->id }}">{{ $pList->name }}</option>
                                        @endforeach
                                    </select>@error('price_list_id') <span class="text-red-500">{{ $message }}</span>@enderror
                                    <br>
                                    <strong> Vendedor:</strong>
                                    <select class="form-control" name="id_seller" id="id_seller" wire:model="id_seller">
                                        <option style="color: black;" value="">Ninguno</option>
                                        @foreach($allSellers as $seller)
                                        <option style="color: black;" value="{{ $seller->id }}">{{ $seller->name_seller }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_seller') <span class="text-red-500">{{ $message }}</span>@enderror<br>
                                </address>
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5">

                                    <strong> Consecutivo: </strong>
                                    <input disabled="true" class="form-control" type="text" name="consecutive" id="consecutive" data-parsley-required="true" wire:model="consecutive" />
                                    <br>
                                    <strong> Actividad Economica: </strong>
                                    <select class="form-control" data-parsley-required="true" name="id_ea" id="id_ea" wire:model="id_ea">
                                        @foreach($allEAs as $ea)
                                        <option style="color: black;" value="{{ $ea->number }}">{{ $ea->name_ea }}</option>
                                        @endforeach
                                    </select> @error('id_ea') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Sucursal:</strong>
                                    <select class="form-control" data-parsley-required="true" name="id_branch_office" id="id_branch_office" wire:click="changeBO()" wire:model="id_branch_office">
                                        @foreach($allBranchOffices as $bo)
                                        <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                        @endforeach
                                    </select> @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Fecha de entrega:</strong>
                                    <input class="form-control" type="date" name="delivery_date" id="delivery_date" data-parsley-required="true" wire:model="delivery_date" />
                                    <br>
                                    <strong> Prioridad:</strong>
                                    <select class="form-control" data-parsley-required="true" name="priority" id="priority" wire:model="priority">
                                        <option style="color: black;" value="1">ALTA</option>
                                        <option style="color: black;" value="2">MEDIA</option>
                                        <option style="color: black;" value="3">BAJA</option>
                                    </select> @error('priority') <span class="text-red-500">{{ $message }}</span>@enderror <br>


                                </address>
                            </div>
                        </div>
                        <!-- end invoice-header -->
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">

                            <!-- begin panel-body -->
                            <div class="panel-body panel-form">
                                <div class="form-group row">
                                    <div class="form-group row col-lg-8" wire:ignore>
                                        <label class="col-md-12 col-form-label">Producto o Servicio</label>
                                        <select class="col-lg-10 form-control product-select2" onchange="changeProductU()" name="id_productU" id="id_productU">
                                            <option style="color: black;" value="">Seleccionar...</option>
                                            @foreach($allProducts as $product)
                                            <option style="color: black;" value="{{ $product->id }}">{{ $product->internal_code.'-'.$product->description }}</option>
                                            @endforeach
                                        </select>
                                        <a title="Nuevo Producto" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-togglecol-lg-2">
                                            <span><i style="color: white;" class="fa fa-plus"></i></span>
                                            <b class="caret"></b>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" title="Nuevo Producto" data-toggle="modal" data-target="#productModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Produto</a>
                                            <a class="dropdown-item" title="Nuevo Servicio" data-toggle="modal" data-target="#serviceModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Servicio</a>
                                        </div>
                                        @error('id_product') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Cantidad</label>
                                        <input class="form-control" type="number" name="qty" id="qty" value="1" step='0.01' placeholder='0.00' data-parsley-required="true" wire:model="qty" />
                                        @error('qty') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Precio Unidad</label>
                                        <input class="form-control" type="number" name="price" id="price" step='0.00001' placeholder='0.000000' data-parsley-required="true" wire:model="price" />
                                        @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Partida Arancelaria</label>
                                        <input class="form-control" type="number" name="tariff_heading" id="tariff_heading" value="1" placeholder='0.00' data-parsley-required="true" wire:model="tariff_heading" />
                                        @error('tariff_heading') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-3" wire:ignore>
                                        <label class="col-lg-12 col-form-label">Unidad de Medida</label>
                                        <select onchange="changeSku()" class="form-control sku-select2" data-parsley-required="true" name="skuU" id="skuU">
                                            @foreach($allSkuses as $sku)
                                            <option style="color: black;" value="{{ $sku->symbol }}">{{ $sku->description }}</option>
                                            @endforeach
                                        </select> @error('sku') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-4 col-form-label">Descuento</label>
                                        <input class="form-control" type="number" name="discount" id="discount" wire:model="discount" />
                                        @error('discount') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-4 col-form-label">Impuestos</label>
                                        <select class="form-control" name="id_tax" id="id_tax" placeholder="Impuestos" wire:model="id_tax">
                                            @foreach($allTaxes as $tax)
                                            <option style="color: black;" value="{{ $tax->id }}">{{ $tax->description }}</option>
                                            @endforeach
                                        </select> @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-4 col-form-label"></label>
                                        <br>
                                        <button type="button" wire:click.prevent="addLine()" class="btn btn-blue col-lg-12">Agregar Linea</button>
                                    </div>
                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>
                        <!-- end panel -->
                        <!-- begin invoice-content -->
                        <div class="invoice-content">
                            <!-- begin table-responsive -->
                            <div class="table-responsive bg-gray-400">
                                <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                    <thead>
                                        <tr class="text-white" style="background-color: #192743;">
                                            <th class="text-center" width="1%"></th>
                                            <th class="text-center" width="1%">N°</th>
                                            <th class="text-center" width="10%">CABYS</th>
                                            <th class="text-center" width="24%">DESCRIPCION</th>
                                            <th class="text-center" width="8%">UNIDAD</th>
                                            <th class="text-center" width="8%">CANT.SOL</th>
                                            <th class="text-center" width="8%">CANT.DESP</th>
                                            <th class="text-center" width="8%">PRECIO</th>
                                            <th class="text-center" width="10%">DESCUENTO</th>
                                            <th class="text-center" width="10%">IMPUESTOS</th>
                                            <th class="text-center" width="10%">EXONERACIÓN</th>
                                            <th class="text-center" width="10%">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allLines as $index => $line)
                                        <tr class="gradeU">
                                            <td width="2%" class="text-center">
                                                <div class="btn-group btn-group-justified">
                                                    <button wire:click.prevent="deleteLine({{ $index }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                    <button wire:click.prevent="editLine({{ $index }})" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i></button>
                                                </div>
                                            </td>
                                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                                            <td width="10%" class="text-center">{{ $line['cabys'] }}</td>
                                            <td width="25%" class="text-center">{{ $line['description'] }}</td>
                                            <td width="7%" class="text-center">{{ $line['sku'] }}</td>
                                            <td width="7%" class="text-center">{{ number_format($line['qty_dispatch'],2,'.',',') }}</td>
                                            <td width="7%" class="text-center">{{ number_format($line['qty'],2,'.',',') }}</td>
                                            <td width="7%" class="text-center">{{ number_format($line['price'],2,'.',',') }}</td>
                                            <td width="7%" class="text-center">{{ number_format($line['discount'],2,'.',',') }}</td>
                                            <td width="7%" class="text-center">{{ number_format((isset($line['tax']['mount']))?$line['tax']['mount']:0 ,2,'.',',')}}</td>
                                            <td width="7%" class="text-center">{{ number_format((isset($line['tax']['exoneration']['AmountExoneration']))?$line['tax']['exoneration']['AmountExoneration']:0  ,2,'.',',')}}</td>
                                            <td width="7%" class="text-center">{{ number_format($line['totalLine']  ,2,'.',',')}}</td>
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
                                            <span class="text-inverse" id="sub_total">
                                                <h5>{{ number_format($subtotal,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price">
                                            <small>DESCUENTO</small>
                                            <span class="text-inverse" id="total_discount">
                                                <h5>{{ number_format($totalDiscount ,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-plus text-muted"></i>
                                        </div>
                                        <div class="sub-price">
                                            <small>IMPUESTO</small>
                                            <span class="text-inverse" id="total_tax">
                                                <h5>{{ number_format($totalTax,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price text-small">
                                            <small>EXONERADO</small>
                                            <span class="text-inverse text-small" id="total_exoneration">
                                                <h5>{{ number_format($totalExoneration ,2,'.',',')}}</h5>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-price-right" style="background-color: #192743;">
                                    <small>TOTAL</small> <span class="f-w-600" id="total_invoice">{{ number_format($total,2,'.',',') }}</span>
                                </div>
                            </div>
                            <!-- end invoice-price -->
                        </div>
                        <!-- end invoice-content -->
                        <!-- otherchages -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <div class="panel-heading">
                                <h4 class="panel-title">Otros Cargos</h4>
                                <div class="panel-heading-btn">
                                    <a class="btn btn-primary" wire:click="$emit('otherChargeChange')">Agregar otros cargos</a>
                                </div>
                            </div>
                            @if($otherCharge)
                            <!-- begin panel-body -->
                            <div class="panel-body panel-form" style="background-color: darkgrey;">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="col-md-12 col-form-label">Tipo de documento </label>
                                        <select class="form-control" name="id_type_document_other" id="id_type_document_other" wire:model="id_type_document_other">
                                            <option style="color: black;" value="">Seleccionar...</option>
                                            @foreach($allTypeDocumentOtherCharges as $typedoc)
                                            <option style="color: black;" value="{{ $typedoc->id }}">{{ $typedoc->type_document }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_type_document_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Cedula de Tercero</label>
                                        <input class="form-control" type="number" name="idcard_other" id="idcard_other" placeholder='de 9 a 12 digitos' wire:model="idcard_other" />
                                        @error('idcard_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-5">
                                        <label class="col-lg-12 col-form-label">Nombre de Tercero</label>
                                        <input class="form-control" type="text" name="name_other" id="name_other" placeholder='Nombre de Tercero' wire:model="name_other" />
                                        @error('name_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-8">
                                        <label class="col-lg-12 col-form-label">Detalle del cargo</label>
                                        <input class="form-control" type="text" name="detail_other_charge" id="detail_other_charge" placeholder='Detalle del cargo adicional' wire:model="detail_other_charge" />
                                        @error('detail_other_charge') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Monto</label>
                                        <input class="form-control" type="number" name="amount_other" id="amount_other" value="0" wire:model="amount_other" />
                                        @error('amount_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-4 col-form-label"></label>
                                        <br>
                                        <button type="button" wire:click.prevent="addLineOther()" class="btn btn-blue col-lg-12">Agregar cargo</button>
                                    </div>
                                </div>
                                <!-- begin invoice-content -->
                                <div class="invoice-content">
                                    <!-- begin table-responsive -->
                                    <div class="table-responsive bg-gray-400">
                                        <table class="table table-other" id="DetalleOtherServicio" name="DetalleOtherServicio">
                                            <thead>
                                                <tr class="text-white" style="background-color: #192743;">
                                                    <th class="text-center" width="5%"></th>
                                                    <th class="text-center" width="1%">N°</th>
                                                    <th class="text-center" width="20%">TIPO CARGO</th>
                                                    <th class="text-center" width="15%">CÉDULA TERCERO</th>
                                                    <th class="text-center" width="24%">NOMBRE TERCERO</th>
                                                    <th class="text-center" width="25%">DESCRIPCION CARGO</th>
                                                    <th class="text-center" width="10%">MONTO</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allOtherCharges as $index => $charge)
                                                <tr class="gradeU">
                                                    <td width="5%" class="text-center">
                                                        <button wire:click.prevent="deleteLineOther({{ $index }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                    </td>
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
                                    <textarea class="form-control" name="note" id="note" value="" wire:model="note" rows="5"></textarea>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body panel-form">
                                    <div class="form-group row">
                                        <label class="col-lg-4 col-form-label">Tipo de documento de referencia</label>
                                        <div class="col-lg-8">
                                            <select class="form-control" name="id_type_reference" id="id_type_reference" wire:model="id_type_reference">
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
                                                <input class="form-control" type="text" name="number_reference" id="number_reference" wire:model="number_reference" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Fecha de Emisión</label>
                                            <div class="col-lg-8">
                                                <input type="datetime-local" class="form-control" id="date_reference" name="date_reference" placeholder="Fecha de emisión" wire:model="date_reference" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Codigo Referencia</label>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="id_reference" id="id_reference" wire:model="id_reference">
                                                    @foreach($allReferences as $reference)
                                                    <option style="color: black;" value="{{ $reference->code }}">{{ $reference->description }}</option>
                                                    @endforeach
                                                </select> @error('id_reference') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-4 col-form-label">Razón</label>
                                            <div class="col-lg-8">
                                                <input class="form-control" type="text" name="reason" id="reason" wire:model="reason" />
                                            </div>
                                        </div>
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
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('documentU_modal_hide', event => {
        $('#documentUModal').modal('hide');
    });
</script>