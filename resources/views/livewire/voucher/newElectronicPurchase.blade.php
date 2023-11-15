<!-- Modal -->
<div wire:ignore.self class="modal modal-message fade" id="electronicPurchaseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color:#E1E6E4;">
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
                                    <div>
                                        <strong>Factura de:</strong>
                                        <div class="input-group">
                                            <input class="form-control" wire:change="changeProvider($event.target.value)" type="text" name="name_provider" list="providers" placeholder="Ninguno" wire:model="name_provider">
                                            <datalist id="providers">
                                                @foreach($allProviders as $provider)
                                                <option style="color: black;" value="{{ $provider->name_provider }}" label="{{ $provider->name_provider }}">
                                                    @endforeach
                                            </datalist>
                                            <input type="text" name="id_provider" wire:model="id_provider" hidden>
                                            <a title="Nuevo Proveedor" data-toggle="modal" data-target="#providerModal" class="btn btn-primary btn-md"><i style="color: white;" class="fa fa-user-plus"></i></a>
                                        </div>
                                        @error('id_provider') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <br>
                                    <div>
                                        <strong>Moneda:</strong>
                                        <input class="form-control" type="text" name="code_currency" list="currencies" placeholder="Ninguno" wire:change="changeCurrency($event.target.value)" wire:model="code_currency">
                                        <datalist id="currencies">
                                            @foreach($allCurrencies as $currency)
                                            <option style="color: black;" value="{{ $currency->code }}" label="{{ $currency->currency }}">
                                                @endforeach
                                        </datalist>
                                        @error('code_currency') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <br>
                                    <strong> Medio de pago:</strong>
                                    <select class="form-control" name="code_payment_method" id="code_payment_method" wire:model="code_payment_method">
                                        @foreach($allPaymentMethods as $paymentMethod)
                                        <option style="color: black;" value="{{ $paymentMethod->code }}">{{ $paymentMethod->payment_method }}</option>
                                        @endforeach
                                    </select> @error('code_payment_method') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Clasificacion MH:</strong>
                                    <select class="form-control" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                        @foreach($allMHCategories as $mhCategory)
                                        <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                        @endforeach
                                    </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    @if($type_doc == 2)
                                    <div class="row">
                                        <label class="col-form-label col-sx-11" for="pendingCE"><strong>Pendiente de Comprobante Eléctronico:</strong></label>
                                        <input class="m-l-5 m-t-9" type="checkbox" id="pendingCE" style="border-radius: 20%; width: 30px; height: 30px; cursor: pointer;" wire:model="pendingCE">
                                    </div>
                                    <br>
                                    @endif
                                </address>
                                <!-- end form-group -->
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5 form-group">
                                    <strong> Condicion compra:</strong>
                                    <select class="form-control" name="code_sale_condition" id="code_sale_condition" wire:model="code_sale_condition">
                                        @foreach($allSaleConditions as $saleCondition)
                                        <option style="color: black;" value="{{ $saleCondition->code }}">{{ $saleCondition->sale_condition }}</option>
                                        @endforeach
                                    </select> @error('code_sale_condition') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Plazo credito:</strong>
                                    <input class="form-control" type="number" name="term" id="term" value="1" min="1" max="99" wire:model="term" />
                                    @error('term') <span class="text-red-500">{{ $message }}</span>@enderror
                                    <br>
                                    <strong> Tipo de cambio:</strong>
                                    <input class="form-control" type="number" name="exchange_rate" id="exchange_rate" wire:model="exchange_rate" />
                                    @error('exchange_rate') <span class="text-red-500">{{ $message }}</span>@enderror
                                    <br>
                                    <strong> Comprador:</strong>
                                    <select class="form-control" name="id_buyer" id="id_buyer" wire:model="id_buyer">
                                        <option style="color: black;" value="">Ninguno</option>
                                        @foreach($allBuyers as $buyer)
                                        <option style="color: black;" value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_buyer') <span class="text-red-500">{{ $message }}</span>@enderror<br>
                                </address>
                            </div>
                            <div class="invoice-to">
                                <address class="m-t-5 m-b-5">
                                    <strong> Consecutivo: </strong>
                                    <input disabled="true" class="form-control" type="text" name="consecutive" id="consecutive" wire:model="consecutive" />
                                    <br>
                                    <strong> Actividad Economica: </strong>
                                    <select class="form-control" name="number_ea" id="number_ea" wire:model="number_ea">
                                        @foreach($allEconomicActivities as $ea)
                                        <option style="color: black;" value="{{ $ea->number }}">{{ $ea->name_ea }}</option>
                                        @endforeach
                                    </select> @error('number_ea') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Sucursal:</strong>
                                    <select class="form-control" name="id_branch_office" id="id_branch_office" wire:click="changeBO()" wire:model="id_branch_office">
                                        @foreach($allBranchOffices as $bo)
                                        <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                        @endforeach
                                    </select> @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Terminal:</strong>
                                    <select class="form-control" name="id_terminal" id="id_terminal" wire:click="changeT()" wire:model="id_terminal">
                                        @foreach($allTerminals as $te)
                                        <option style="color: black;" value="{{ $te->id }}">{{ str_pad($te->number, 5, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select> @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    <strong> Fecha de entrega:</strong>
                                    <input class="form-control" type="date" name="possible_deliver_date" id="possible_deliver_date" wire:model="possible_deliver_date" />
                                    <br>
                                </address>
                            </div>
                        </div>
                        <!-- end invoice-header -->

                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <!-- begin panel-body -->
                            <div class="panel-body panel-form">
                                <div class="form-group row">
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Tipo linea</label>
                                        <input type="radio" id="compra" name="typeLine" value="Compra" wire:model="type_line">
                                        <label for="compra">Compra</label>
                                        <input type="radio" id="gasto" name="typeLine" value="Gasto" wire:model="type_line">
                                        <label for="gasto">Gasto</label>
                                        @error('typeLine') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-5" {{ ($type_line == 'Compra')?'':'hidden' }}>
                                        <label class="col-md-12 col-form-label">Producto o Servicio</label>
                                        <input class="form-control" type="text" name="name_product" wire:change="changeProduct()" list="products" placeholder="Ninguno" wire:model="name_product">
                                        <datalist id="products">
                                            @foreach($allProducts as $product)
                                            <option style="color: black;" value="{{ $product->description }}" label="{{ $product->internal_code }}">
                                                @endforeach
                                        </datalist>
                                        <input type="text" name="id_product" wire:model="id_product" hidden>
                                        @error('id_product') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="{{ ($type_doc == '2')?'col-lg-3':'col-lg-2' }}" {{ ($type_line != 'Compra' && Auth::user()->currentTeam->plan_id != '1')?'':'hidden' }}>
                                        <label class="col-md-12 col-form-label">Cuenta de Gasto</label>
                                        <div>
                                            <input type="text" name="id_count" hidden>
                                            <input class="form-control" type="text" name="name_count" list="counts" placeholder="Ninguna" wire:model="count">
                                            <datalist id="counts">
                                                @foreach($allCounts as $count)
                                                <option style="color: black;">{{ $count->name }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="{{ (Auth::user()->currentTeam->plan_id == 1)?'col-lg-5':'col-lg-3' }}" {{ ($type_line == 'Compra')?'hidden':'' }}>
                                        <label class="col-md-12 col-form-label">Producto o Servicio</label>
                                        <input class="form-control" type="text" name="description" placeholder="Ninguno" wire:model="description">
                                        @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-1" {{ ($type_line == 'Compra')?'':'hidden' }}>
                                        <label class="col-lg-12 col-form-label"></label>
                                        <br>
                                        <a title="Nuevo Producto" href="javascript:;" data-toggle="dropdown" class="btn btn-blue dropdown-toggle">
                                            <span><i style="color: white;" class="fa fa-plus"></i></span>
                                            <b class="caret"></b>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" title="Nuevo Producto" data-toggle="modal" data-target="#productModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Produto</a>
                                            <a class="dropdown-item" title="Nuevo Servicio" data-toggle="modal" data-target="#serviceModal" wire:click="$emit('refresh', 0)" class="btn btn-blue">Agregar Servicio</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-2" {{ ($type_line == 'Compra' || $type_doc == '2')?'hidden':'' }}>
                                        <label class="col-form-label">CAByS: <span class="text-danger"></span></label>
                                        <div>
                                            <input class="form-control" type="text" name="cabys" list="cabies" placeholder="Ninguno" wire:model="cabys">
                                            <datalist id="cabies">
                                                @foreach($allCabys as $ca)
                                                <option style="color: black;" value="{{ $ca->codigo }}" label="{{ $ca->descripcion }}">
                                                    @endforeach
                                            </datalist>
                                            @error('cabys') <span class="text-red-500">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="{{ ($type_line == 'Compra' || $type_doc == '2')?'col-lg-2':'col-lg-1' }}">
                                        <label class="col-lg-12 col-form-label">Cantidad</label>
                                        <input class="form-control" type="number" name="qty" id="qty" value="1" step='0.01' placeholder='0.00' wire:model="qty" />
                                        @error('qty') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="col-lg-12 col-form-label">Precio</label>
                                        <input class="form-control" type="number" name="price" id="price" step='0.00001' placeholder='0.000000' wire:model="price" />
                                        @error('price') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Partida Arancelaria</label>
                                        <input class="form-control" type="number" name="tariff_heading" id="tariff_heading" value="1" placeholder='0.00' wire:model="tariff_heading" />
                                        @error('tariff_heading') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-12 col-form-label">Unidad de Medida</label>
                                        <input class="form-control" type="text" name="sku" list="skusesD" placeholder="Ninguna" wire:model="sku">
                                        <datalist id="skusesD">
                                            @foreach($allSkuses as $sku)
                                            <option style="color: black;" value="{{ $sku->symbol }}" label="{{ $sku->description }}">
                                                @endforeach
                                        </datalist>
                                        @error('sku') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-4 col-form-label">Descuento</label>
                                        <input class="form-control" type="number" name="discount" id="discount" wire:model="discount" />
                                        @error('discount') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-3">
                                        <label class="col-lg-4 col-form-label">Impuestos</label>
                                        <select class="form-control" name="id_tax" id="id_tax" placeholder="Impuestos" wire:model="id_tax">
                                            @foreach($allTaxes as $tax)
                                            <option style="color: black;" value="{{ $tax->id }}">{{ $tax->description }}</option>
                                            @endforeach
                                        </select> @error('id_tax') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="button" wire:click.prevent="addLine()" class="btn btn-blue col-lg-12" style="white-space: nowrap;">Agregar Linea</button>
                                    </div>
                                </div>
                            </div>
                            <!-- end panel-body -->
                        </div>
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
                                            <th class="text-center" width="10%">CABYS</th>
                                            <th class="text-center" width="24%">DESCRIPCION</th>
                                            <th class="text-center" width="8%">UNIDAD</th>
                                            <th class="text-center" width="8%">CANTIDAD</th>
                                            <th class="text-center" width="8%">PRECIO</th>
                                            <th class="text-center" width="10%">DESCUENTO</th>
                                            <th class="text-center" width="10%">IMPUESTOS</th>
                                            <th class="text-center" width="10%">EXONERACION</th>
                                            <th class="text-center" width="10%">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allLines as $index => $line)
                                        <tr class="gradeU">
                                            <td width="2%" class="text-center btn-group btn-group-justified">
                                                <button wire:click.prevent="deleteLine({{ $index }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                                <button wire:click.prevent="editLine({{ $index }})" class="btn btn-primary btn-sm"><i class="fa fa-pen"></i></button>
                                            </td>
                                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                                            <td width="10%" class="text-center">{{ $line['cabys'] }}</td>
                                            <td width="25%" class="text-center">{{ $line['description'] }}</td>
                                            <td width="7%" class="text-center">{{ $line['sku'] }}</td>
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
                            <div class="col-lg-12">
                                <div class="invoice-price row">
                                    <div class="invoice-price-left col-8">
                                        <div class="invoice-price-row row">
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
                                    <div class="invoice-price-right col-4" style="background-color:#192743;">
                                        <small>TOTAL</small> <span class="f-w-600">{{ number_format($total,2,'.',',') }}</span>
                                    </div>
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
                                    <a class="btn btn-primary" wire:click="otherChargeChange()">Agregar otros cargos</a>
                                </div>
                            </div>
                            @if($otherCharge ==1)
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
                                                </select> @error('id_reference') <span class="text-red-500">{{ $message }}</span>@enderror <br>
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
                                <br>
                                <div class="row" style="float: right;">
                                    <input type="checkbox" class="m-r-5" name="gasper" wire:model="gasper" style="border-radius: 20%; width: 30px; height: 30px; cursor: pointer;">
                                    <label for="gasper" class="m-t-2" style="color: red;"> No tomar en cuenta para contabilidad</label>
                                </div>
                                <br>
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
                <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Procesar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('electronicPurchase_modal_hide', event => {
        $('#electronicPurchaseModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('body').on('hidden.bs.modal', function() {
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open');
            }
        });

    });
</script>