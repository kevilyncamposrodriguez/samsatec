<div>
    <div wire:ignore.self class="modal modal-message fade" style="width: 100%; margin-left: -10px;" id="newDocumentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center" style="background-color:#E1E6E4; width: 90%;">
                    <h4 class="modal-title text-center"><strong class="text-center">
                            {{$type_document}}
                        </strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="background-color:#E1E6E4; width: 90%;">
                    <div class="form-horizontal form-bordered">
                        @csrf
                        <!-- begin invoice -->
                        <div class="invoice">
                            <!-- begin invoice-header -->
                            <div class="invoice-header p-t-10 p-b-10 row">
                                <br>
                                <div class="col-md-4">
                                    <strong>Facturar a:</strong>
                                    <div class="input-group">
                                        <input class="form-control" wire:change="changeClient($event.target.value)" type="text" name="name_client" list="clients" placeholder="Ninguno" wire:model="name_client">
                                        <datalist id="clients">
                                            @foreach($allClients as $client)
                                            <option style="color: black;" value="{{ $client->name_client }}" label="{{ $client->name_client }}">
                                                @endforeach
                                        </datalist>
                                        <input type="text" name="id_client" wire:model="id_client" hidden>
                                        <a title="Nuevo Cliente" data-toggle="modal" data-target="#clientModal" class="btn btn-primary btn-md" wire:click="$emit('refresh', 0)"><i style="color: white;" class="fa fa-user-plus"></i></a>
                                    </div>
                                    @error('name_client') <span class="text-red-500">{{ $message }}</span>@enderror
                                    @error('id_client') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    <strong> Actividad Economica: </strong>
                                    <select class="form-control" name="number_ea" id="number_ea" wire:model="number_ea">
                                        @foreach($allEconomicActivities as $ea)
                                        <option style="color: black;" value="{{ $ea->number }}">{{ $ea->name_ea }}</option>
                                        @endforeach
                                    </select> @error('number_ea') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    <strong> Consecutivo: </strong>
                                    <input disabled="true" class="form-control" type="text" name="consecutive" id="consecutive" wire:model="consecutive" />
                                    @error('consecutive') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    <strong> Clasificacion MH:</strong>
                                    <select class="form-control" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                        @foreach($allMHCategories as $mhCategory)
                                        <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                        @endforeach
                                    </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror

                                </div>
                                <!-- end form-group -->
                                <div class="col-md-4">
                                    <strong> Sucursal:</strong>
                                    <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_branch_office" id="id_branch_office" wire:click="changeBO()" wire:model="id_branch_office">
                                        @foreach($allBranchOffices as $bo)
                                        <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                        @endforeach
                                    </select> @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    <strong> Terminal:</strong>
                                    <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_terminal" id="id_terminal" wire:click="changeT()" wire:model="id_terminal">
                                        @foreach($allTerminals as $te)
                                        <option style="color: black;" value="{{ $te->id }}">{{ str_pad($te->number, 5, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select> @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>

                                <div class="col-md-4">
                                    <strong> Vendedor:</strong>
                                    <select class="form-control" name="id_seller" id="id_seller" wire:model="id_seller">
                                        <option style="color: black;" value="">Ninguno</option>
                                        @foreach($allSellers as $seller)
                                        <option style="color: black;" value="{{ $seller->id }}">{{ $seller->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_seller') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-4">
                                    @if(Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2)
                                    <strong> Lista de precios:</strong>
                                    <select class="form-control" name="price_list_id" id="price_list_id" wire:click="changeList()" wire:model="price_list_id">
                                        <option style="color: black;" value="">Por Defecto</option>
                                        @foreach($allPriceList as $pList)
                                        <option style="color: black;" value="{{ $pList->id }}">{{ $pList->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('price_list_id') <span class="text-red-500">{{ $message }}</span>@enderror
                                    @endif
                                </div>
                                @if(Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2)
                                <div class="col-md-4">
                                    <strong> Fecha de entrega:</strong>
                                    <input class="form-control" type="datetime-local" name="delivery_date" id="delivery_date" wire:model="delivery_date" />
                                </div>  @error('delivery_date') <span class="text-red-500">{{ $message }}</span>@enderror
                                @endif
                                @if(Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2)
                                <div class="col-md-4">
                                    <strong> Prioridad:</strong>
                                    <select class="form-control" name="priority" id="priority" wire:model="priority">
                                        <option style="color: black;" value="1">ALTA</option>
                                        <option style="color: black;" value="2">MEDIA</option>
                                        <option style="color: black;" value="3">BAJA</option>
                                    </select> @error('priority') <span class="text-red-500">{{ $message }}</span>@enderror
                                </div>
                                @endif
                                <br>
                            </div>

                            <fieldset class="m-t-10 p-t-10 p-b-10">
                                <!-- begin panel-body -->
                                <div class="panel-body panel-form">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <strong>Codigo</strong>
                                            <input class="form-control" wire:change="changeCodeProduct()" type="text" name="code_product" placeholder="Ninguno" wire:model="code_product">
                                        </div>
                                        <div class="col-lg-8">
                                            <strong>Producto o Servicio</strong>
                                            <div class="input-group">
                                                <input class="form-control" type="text" name="name_product" id="name_product" wire:change="changeProduct()" list="products" placeholder="Ninguno" wire:model="name_product">
                                                <datalist id="products" style="width: 200px;">
                                                    @foreach($allProducts as $product)
                                                    <option style="color: black; width: 200px;" value="{{ $product->description }}" label="{{ $product->internal_code }}">
                                                        @endforeach
                                                </datalist>
                                                <input type="text" name="id_product" wire:model="id_product" hidden>
                                                <a title="Nuevo Producto" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-toggle">
                                                    <span><i style="color: white;" class="fa fa-plus"></i></span>
                                                    <b class="caret"></b>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" title="Nuevo Producto" data-toggle="modal" data-target="#productModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Produto</a>
                                                    <a class="dropdown-item" title="Nuevo Servicio" data-toggle="modal" data-target="#serviceModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Servicio</a>
                                                </div>
                                            </div>
                                            @error('id_product') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                </div>
                                <!-- end panel-body -->
                            </fieldset>
                            <!-- end panel -->
                            <!-- begin invoice-content -->
                            <div class="invoice-content">
                                @error('allLines') <span class="text-red-500">Debes agregar al menos una linea</span>@enderror
                                <!-- begin table-responsive -->
                                <div class="table-responsive bg-gray-400">
                                    <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                        <thead>
                                            <tr class="text-white" style="background-color:#192743;">
                                                <th class="text-center" width="1%"></th>
                                                <th class="text-center" width="1%">N°</th>
                                                <th class="text-center" width="7%">CABYS</th>
                                                <th class="text-center" width="24%">DESCRIPCION</th>
                                                <th class="text-center" width="8%">UNIDAD</th>
                                                <th class="text-center" width="8%">CANTIDAD</th>
                                                <th class="text-center" width="8%">COSTO</th>
                                                <th class="text-center" width="8%">PRECIO</th>
                                                <th class="text-center" width="7%">DESCUENTO</th>
                                                <th class="text-center" width="7%">IMPUESTOS</th>
                                                <th class="text-center" width="7%">EXONERACION</th>
                                                <th class="text-center" width="10%">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allLines as $index => $line)
                                            <tr class="gradeU">
                                                <td width="1%" class="text-center btn-group btn-group-justified">
                                                    <button wire:click="deleteLine({{ $index }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                </td>
                                                <td width="1%" class="text-center">{{ $index+1 }}</td>
                                                <td width="7%" class="text-center">{{ $line['cabys'] }}</td>
                                                <td width="24%" class="text-center">{{ $line['description'] }}</td>
                                                <td width="7%" class="text-center">
                                                    <input class="form-control" type="text" wire:change="changeSKULine({{$index}},$event.target.value)" list="skus" placeholder="Ninguno" value="{{ $line['sku'] }}">
                                                    <datalist id="skus">
                                                        @foreach($allSkuses as $sku)
                                                        <option style="color: black;" value="{{ $sku->symbol }}" label="{{ $sku->description }}">
                                                            @endforeach
                                                    </datalist>
                                                </td>
                                                <td width="10%" class="text-center"><input class="form-control" type="number" name="qty.{{$index}}" wire:change='changeQty({{ $index }},$event.target.value)' placeholder="Ninguno" min="0" value="{{$line['qty_dispatch']}}"> </td>
                                                <td width="15%" class="text-center"><input class="form-control" type="number" name="cost.{{$index}}" wire:change='changeCost({{ $index }},$event.target.value)' placeholder="Ninguno" min="0" value="{{$line['cost']}}"> </td>
                                                <td width="15%" class="text-center"><input class="form-control" type="number" name="price.{{$index}}" wire:change='changePrice({{ $index }},$event.target.value)' placeholder="Ninguno" min="0" value="{{$line['price']}}"> </td>
                                                <td width="7%" class="text-center"><a title="Cambiar Descuento" data-toggle="modal" data-target="#changeDiscountModal" wire:click="editDiscount({{$index}})" class="btn btn-primary text-white">{{ number_format($line['discount'],2,'.',',') }}</a></td>
                                                <td width="7%" class="text-center"><a title="Cambiar Descuento" data-toggle="modal" data-target="#changeTaxModal" wire:click="editTax({{$index}})" class="btn btn-primary text-white">{{ number_format(((isset($line['tax']['mount']))?$line['tax']['mount']:0),2,'.',',')}}</a></td>
                                                <td width="7%" class="text-center"><a title="Cambiar Descuento" data-toggle="modal" data-target="#changeTaxModal" wire:click="editTax({{$index}})" class="btn btn-primary text-white">{{ number_format(((isset($line['tax']['exoneration']['AmountExoneration']))?$line['tax']['exoneration']['AmountExoneration']:0)  ,2,'.',',')}}</a></td>
                                                <td width="7%" class="text-center">{{ number_format($line['totalLine']  ,2,'.',',')}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->

                            </div>
                            <!-- end invoice-content -->
                            <!-- otherchages -->
                            <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Otros Cargos</h4>
                                    <div class="panel-heading-btn">
                                        <a class="btn btn-primary" wire:click="otherChargeChange()">Agregar otros cargos</a>
                                    </div>
                                </div>
                                @if($otherCharge ==1)
                                <!-- begin panel-body -->
                                <div class="panel-body panel-form" style="background-color: darkgrey;">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <strong>Tipo de documento </strong>
                                            <select class="form-control" name="id_type_document_other" id="id_type_document_other" wire:model="id_type_document_other">
                                                <option style="color: black;" value="">Seleccionar...</option>
                                                @foreach($allTypeDocumentOtherCharges as $typedoc)
                                                <option style="color: black;" value="{{ $typedoc->id }}">{{ $typedoc->type_document }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_type_document_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <strong>Cedula de Tercero</strong>
                                            <input class="form-control" type="number" name="idcard_other" id="idcard_other" placeholder='de 9 a 12 digitos' wire:model="idcard_other" />
                                            @error('idcard_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-lg-5">
                                            <strong>Nombre de Tercero</strong>
                                            <input class="form-control" type="text" name="name_other" id="name_other" placeholder='Nombre de Tercero' wire:model="name_other" />
                                            @error('name_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-8">
                                            <strong>Detalle del cargo</strong>
                                            <input class="form-control" type="text" name="detail_other_charge" id="detail_other_charge" placeholder='Detalle del cargo adicional' wire:model="detail_other_charge" />
                                            @error('detail_other_charge') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <strong>Monto</strong>
                                            <input class="form-control" type="number" name="amount_other" id="amount_other" value="0" wire:model="amount_other" />
                                            @error('amount_other') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-lg-2">
                                            <button type="button" wire:click.prevent="addLineOther()" class="btn btn-blue m-t-10 col-lg-12">Agregar cargo</button>
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
                                                        <th class="text-center" width="15%">CEDULA TERCERO</th>
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
                            <!-- end invoice-header -->
                            <fieldset style="background-color:#E1E6E4;" class="p-t-10 p-b-10">
                                <h4 class="text-center">Datos de pago</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Moneda:</strong>
                                        <input class="form-control" type="text" name="code_currency" list="currencies" placeholder="Ninguno" wire:change="changeCurrency($event.target.value)" wire:model="code_currency">
                                        <datalist id="currencies">
                                            @foreach($allCurrencies as $currency)
                                            <option style="color: black;" value="{{ $currency->code }}" label="{{ $currency->currency }}">
                                                @endforeach
                                        </datalist>
                                        @error('code_currency') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <strong> Tipo de cambio:</strong>
                                        <input class="form-control" type="number" name="exchange_rate" id="exchange_rate" wire:model="exchange_rate" />
                                        @error('exchange_rate') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <strong> Medio de pago:</strong>
                                        <select class="form-control" name="code_payment_method" id="code_payment_method" wire:model="code_payment_method">
                                            @foreach($allPaymentMethods as $paymentMethod)
                                            <option style="color: black;" value="{{ $paymentMethod->code }}">{{ $paymentMethod->payment_method }}</option>
                                            @endforeach
                                        </select> @error('code_payment_method') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-3">
                                        <strong> Condicion compra:</strong>
                                        <select class="form-control" name="code_sale_condition" id="code_sale_condition" wire:model="code_sale_condition">
                                            @foreach($allSaleConditions as $saleCondition)
                                            <option style="color: black;" value="{{ $saleCondition->code }}">{{ $saleCondition->sale_condition }}</option>
                                            @endforeach
                                        </select> @error('code_sale_condition') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    @if(Auth::user()->currentTeam->plan_id != 1 && Auth::user()->currentTeam->plan_id != 2)
                                    <div class="col-md-3" {{(($type_doc != '00' || $type_doc != '99') && $code_sale_condition == '01')?'':'hidden'}}>
                                        <strong> Cuenta a Recibir:</strong>
                                        <select class="form-control" name="id_count" id="id_count" wire:model="id_count">
                                            @foreach($allAcounts as $acount)
                                            <option style="color: black;" value="{{ $acount->code }}">{{ $acount->name }}</option>
                                            @endforeach
                                        </select> @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-3" {{(($type_doc != '00' || $type_doc != '99') && $code_sale_condition == '01' && ($code_payment_method == '02' || $code_payment_method == '03' || $code_payment_method == '04'))?'':'hidden'}}>
                                        <strong>Referencia: </strong>
                                        <input data-toggle="number" data-placement="after" class="form-control" type="text" name="reference" wire:model="reference" placeholder="Referencia" data-parsley-required="true" />
                                        @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-3" {{(($type_doc != '00' || $type_doc != '99') && $code_sale_condition == '01' && $code_payment_method != '01')?'':'hidden'}}>
                                        <strong>Observaciones: </strong>
                                        <input class="form-control" type="textarea" name="observations" wire:model="observations" placeholder="Observaciones" />
                                        @error('observations') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    @endif
                                    <div class="col-md-3" {{($code_sale_condition != '01')?'':'hidden'}}>
                                        <strong> Plazo credito:</strong>
                                        <input class="form-control" type="number" name="term" id="term" value="1" min="1" max="99" wire:model="term" />
                                        @error('term') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </fieldset>
                            <!-- begin panel -->
                            <!-- begin invoice-price -->
                            <div class="invoice-price">
                                <div class="invoice-price-left">
                                    <div class="row">
                                        <div class="sub-price col-md-2">
                                            <small>SUBTOTAL</small>
                                            <span class="text-inverse">
                                                <h5>{{ number_format($subtotal,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price col-md-1">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price col-md-2">
                                            <small>DESCUENTO</small>
                                            <span class="text-inverse">
                                                <h5>{{ number_format($totalDiscount ,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price col-md-1">
                                            <i class="fa fa-plus text-muted"></i>
                                        </div>
                                        <div class="sub-price col-md-2">
                                            <small>IMPUESTO</small>
                                            <span class="text-inverse">
                                                <h5>{{ number_format($totalTax,2,'.',',') }}</h5>
                                            </span>
                                        </div>
                                        <div class="sub-price col-md-1">
                                            <i class="fa fa-minus text-muted"></i>
                                        </div>
                                        <div class="sub-price text-small col-md-2">
                                            <small>EXONERADO</small>
                                            <span class="text-inverse text-small">
                                                <h4>{{ number_format($totalExoneration ,2,'.',',')}}</h4>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-price-right" style="background-color:#192743;">
                                    <div>
                                        <small>
                                            <h3 class="text-white">TOTAL A PAGAR</h3>
                                        </small>
                                        <input class="form-control m-t-40" type="text" disabled style="font-size: 17pt; width: 200px; text-align:right" value="{{ number_format(($total_other_charges+$total),2,'.',',') }}" />
                                    </div>
                                </div>
                            </div>
                            @if($code_payment_method == '01' && $code_sale_condition == '01')
                            <div class="invoice-price" style="margin-top: -20px;">
                                <div class="invoice-price-right" style="background-color:#192743;">
                                    <div>
                                        <small>
                                            <h3 class="text-white text-left">INGRESO DE EFECTIVO</h3>
                                        </small>
                                        <input class="form-control m-t-40" style="font-size: 17pt; width: 200px; text-align:right" type="number" wire:model="cash" />
                                        @error('cash') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-price" style="margin-top: -20px;">
                                <div class="invoice-price-right" style="background-color:#192743;">
                                    <div>
                                        <small>
                                            <h3 class="text-white text-left">VUELTO</h3>
                                        </small>
                                        <input class="form-control m-t-40" type="text" disabled style="font-size: 17pt; width: 200px; text-align:right" size="20" name="vuelto" id="vuelto" value="{{ number_format((($cash != '')?$cash-$total:0),2,'.',',') }}" />
                                    </div>
                                </div>
                            </div>
                            <!-- end invoice-price -->
                            @endif
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
                                                </select> @error('id_type_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div id="reference" {{ $result = ($id_type_reference == '')?'hidden':''}}>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Clave o consecutivo</label>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" name="number_reference" id="number_reference" wire:model="number_reference" />
                                                    @error('number_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Fecha de Emisión</label>
                                                <div class="col-lg-8">
                                                    <input type="datetime-local" class="form-control" id="date_reference" name="date_reference" placeholder="Fecha de emisión" wire:model="date_reference" />
                                                    @error('date_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Codigo Referencia</label>
                                                <div class="col-lg-8">
                                                    <select class="form-control" name="id_reference" id="id_reference" wire:model="id_reference">
                                                        @foreach($allReferences as $reference)
                                                        <option style="color: black;" value="{{ $reference->code }}">{{ $reference->description }}</option>
                                                        @endforeach
                                                    </select> @error('id_reference') <span class="text-red-500">{{ $message }}</span>@enderror
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-4 col-form-label">Razón</label>
                                                <div class="col-lg-8">
                                                    <input class="form-control" type="text" name="reason" id="reason" wire:model="reason" />
                                                    @error('reason') <span class="text-red-500">{{ $message }}</span>@enderror
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

                    </div>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                    <button type="button" wire:click.prevent="store(0)" onclick="this.disabled=true;" class="btn text-white" accesskey="g" style="background-color:#010e2c;">Procesar</button>
                    <button type="button" wire:click.prevent="store(1)" onclick="this.disabled=true;" class="btn btn-primary text-white" accesskey="i">Procesar e imprimir</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('name_product_focus', event => {
            setTimeout(function() {
                $("#name_product").focus();
                $("#name_product").mousedown();
            }, 1000);
        });
        window.addEventListener('imprimir', event => {
            window.open('ticketPDF/' + event.detail.messageData, '_blank');
        });
        window.addEventListener('document_modal_hide', event => {
            $('#newDocumentModal').modal('hide');
        });
        window.addEventListener('change_discount_modal_hide', event => {
            $('#changeDiscountModal').modal('hide');
        });
        window.addEventListener('change_tax_modal_hide', event => {
            $('#changeTaxModal').modal('hide');
        });
        document.addEventListener('DOMContentLoaded', function() {
            $('body').on('hidden.bs.modal', function() {
                if ($('.modal.show').length > 0) {
                    $('body').addClass('modal-open');
                }
            });
            $("#newDocumentModal").on("hidden.bs.modal", function() {
                Livewire.emit('cleanInputs')
            });
            $('#newDocumentModal').on('shown.bs.modal', function() {
                $('#code_product').focus();
            });


        });
    </script>
    @include('livewire.document.changeDiscount')
    @include('livewire.document.changeTax')
</div>