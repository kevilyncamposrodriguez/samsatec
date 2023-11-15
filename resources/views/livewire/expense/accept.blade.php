<!-- Modal -->
<div wire:ignore.self class="modal modal-message fade" id="acceptExpenseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" style="overflow-y: scroll;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #E1E6E4;">
            <div class="modal-header" style="width:95%;">
                <h4 class="modal-title text-center" id="t_dView"> </h4>
                <h6 class="text-inverse"><b>Comprobante electronico: {{ $key }}</b></h6>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style="width:95%;">
                <!-- begin invoice -->
                <div class="invoice">
                    <!-- begin invoice-header -->
                    <div class="invoice-header">
                        <div class="invoice-from">
                            <address class="m-t-5">
                                <h5 class="f-w-900 text-inverse"> <b>Emisor:</b></h5>
                                <!-- begin form-group -->
                                <h6 class="text-inverse">Cedula: {{ $id_card_e }}</h6>
                                <h6 class="text-inverse">Nombre: {{ $name_e }}</h6>
                                <h6 class="text-inverse">Tel: {{ $phone_e }}</h6>
                                <h6 class="text-inverse">Correo: {{ $mail_e }}</h6>
                            </address>
                            <!-- end form-group -->
                        </div>
                        <div class="invoice-to">
                            <address class="m-t-19">
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label">Sucursal: <span class="text-danger"></span></label>
                                    <div class="col-lg-9">
                                        <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_branch_office" id="id_branch_office" wire:change='change_bo' wire:model="id_branch_office">
                                            @foreach($allBO as $bo)
                                            <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                            @endforeach
                                        </select> @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label">Terminal: <span class="text-danger"></span></label>
                                    <div class="col-lg-9">
                                        <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_terminal" id="id_terminal" wire:model="id_terminal">
                                            @foreach($allTerminals as $terminal)
                                            <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                                            @endforeach
                                        </select> @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label">Categoria MH: <span class="text-danger"></span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                            @foreach($allMHCategories as $mhCategory)
                                            <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                            @endforeach
                                        </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="form-group row ">
                                    <label class="col-lg-3 col-form-label text-black">Actividad Economica: <span class="text-danger"></span></label>
                                    <div class="col-lg-9">
                                        <select class="form-control" name="number_ea" id="number_ea" wire:model="number_ea">
                                            @foreach($all_eas as $ea)
                                            <option style="color: black;" value="{{ $ea->number }}">{{ $ea->number.'-'.$ea->name_ea }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if(Auth::user()->currentTeam->plan_id > 1)
                                <div class="form-group row ">
                                    <label title="Facturas pendientes de comprobante eléctronico" class="col-lg-12 col-form-label text-black">Facts pendientes de CE: <span class="text-danger"></span></label>
                                    <div class="col-lg-12">
                                        <select class="form-control" multiple name="pending" id="pending" wire:model="pendingCE">
                                            @foreach($this->allPendings as $pending)
                                            <option style="color: black;" value="{{ $pending->key }}">{{ $pending->consecutive.'-'.$pending->name_provider.'-'.number_format($pending->total_document,2,'.',',') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </address>
                        </div>
                        <div class="invoice-date">
                            <address class="m-t-5">
                                <h5 id="consecutiveView" name="consecutiveView"> <b>Consecutivo: </b>{{ $consecutive_view }}</h5>
                                <!-- begin form-group -->
                                <h6 class="text-inverse"><b>Fecha:</b> {{ $date_view }}</h6>
                                <h6 class="text-inverse"><b>Condicion de venta:</b> {{ $sale_condition_view }}</h6>
                                <h6 class="text-inverse"><b>Metodo de pago:</b> {{ $payment_method_view }}</h6>
                                <h6 class="text-inverse"><b> Moneda: </b>{{ $currency_view }}</h6>
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
                                        @if(Auth::user()->currentTeam->plan_id > 2)
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
                                    @foreach($detail_view as $index => $detail)
                                    <tr class="gradeU">
                                        <td width="1%">{{ $detail['line'] }}</td>
                                        @if(Auth::user()->currentTeam->plan_id > 2)
                                        <td width="25%" class="text-center">
                                            <div>
                                                <input type="text" name="id_count" hidden>
                                                <input {{  (isset($productsUpdate[$index]) )?(($productsUpdate[$index] == "")?'':'disabled' ):'' }} class="form-control"  type="text" name="name_count" list="counts" placeholder="Ninguna" wire:change="changeAccount({{$index}})" wire:model="countsUpdate.{{ $index }}">
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
                                                <input {{  (isset($countsUpdate[$index]) )?(($countsUpdate[$index] == "")?'':'disabled' ):'' }} class="form-control" type="text" name="name_product" list="products" placeholder="Ninguno" wire:change="changeProduct({{$index}})" wire:model="productsUpdate.{{ $index }}">
                                                    @foreach($allProducts as $product)
                                                    <option style="color: black;" data-id="{{ $product->id }}">{{ $product->description }}</option>
                                                    @endforeach                                                <datalist id="products" >

                                                </datalist>
                                            </div>
                                        </td>
                                        @else
                                        <td width="25%" class="text-center">
                                            <input type="radio" id="compr.{{ $index }}" name="typeLin.{{ $index }}" value="0" wire:model="type_line.{{ $index }}">
                                            <label for="compr.{{ $index }}">Compra</label>
                                            <input type="radio" id="gast.{{ $index }}" name="typeLin.{{ $index }}" value="1" wire:model="type_line.{{ $index }}">
                                            <label for="gast.{{ $index }}">Gasto</label>
                                        </td>
                                        @endif
                                        <td width="10%" class="text-center">{{ $detail["code"] }}</td>
                                        <td width="25%" class="text-center">{{ $detail["description"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["sku"] }}</td>
                                        <td width="7%" class="text-center">{{ $detail["qty"] }}</td>
                                        <td width="25%" class="text-center">{{ number_format($detail["price"],2,'.',',') }}</td>
                                        <td width="7%" class="text-center">{{ number_format($detail["discount"],2,'.',',') }}</td>
                                        <td width="7%" class="text-center">{{ number_format($detail["tax"],2,'.',',') }}</td>
                                        <td width="7%" class="text-center">{{ number_format($detail["exoneration"],2,'.',',') }}</td>
                                        <td width="7%" class="text-center">{{ number_format($detail["total"],2,'.',',') }}</td>
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
                                        <span class="text-inverse">{{number_format($subtotal_view,2,'.',',')}}</span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-minus text-muted"></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>DESCUENTO</small>
                                        <span class="text-inverse">{{number_format($discount_view,2,'.',',') }}</span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-plus text-muted"></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>IMPUESTO</small>
                                        <span class="text-inverse">{{number_format($tax_view,2,'.',',')}}</span>
                                    </div>
                                    <div class="sub-price">
                                        <i class="fa fa-minus text-muted"></i>
                                    </div>
                                    <div class="sub-price">
                                        <small>EXONERADO</small>
                                        <span class="text-inverse">{{number_format($exoneration_view)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="invoice-price-right" style="background-color:#192743;">
                                <small>TOTAL</small> <span class="f-w-600">{{number_format($total_view,2,'.',',')}}</span>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <div class="invoice-note">
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
                            <!-- begin panel-body -->
                            <div class="panel-body panel-form">
                                <div class="form-group row">

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
            <div class="modal-footer" style="width:90%;">
                <button type="button" wire:click.prevent="saveVoucher()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Acceptar</button>
                <a href="javascript:;" class="btn btn-dark" data-dismiss="modal">Cerrar</a>
            </div>
        </div>
    </div>
</div>