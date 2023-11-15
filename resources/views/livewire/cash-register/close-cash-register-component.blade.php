<div wire:ignore.self class="modal modal-message fade" id="CCRModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body" style="background-color:#E1E6E4; width: 90%;">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <div class="text-center center">
                        @if(Auth::user()->currentTeam->logo_url != "")
                        <span>
                            <img src="{{ Auth::user()->currentTeam->logo_url }}" style="margin: auto;" width="100em">
                        </span>
                        @endif
                        <h3>{{ Auth::user()->currentTeam->name }}</h3>
                        <h4>Cierre de Caja</h4>
                    </div>
                    <!-- begin invoice-header -->
                    <div class="invoice-header p-t-10 p-b-10 row">
                        <div class="col-md-4">
                            <strong>Sucursal: </strong>
                            <select class="form-control" name="id_bo" id="id_bo" disabled wire:model="id_bo">
                                @foreach($allBOs as $bo)
                                <option style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <strong>Terminal: </strong>
                            <select class="form-control" name="id_terminal" id="id_terminal" disabled wire:model="id_terminal">
                                @foreach($allTerminals as $terminal)
                                <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <strong> Fecha de Apertura: </strong>
                            <input disabled class="form-control" type="datetime-local" name="start_date" id="start_date" wire:model="start_date" />
                        </div>
                        <div class="col-md-3">
                            <strong> Fecha de Cierre: </strong>
                            <input disabled class="form-control" type="datetime-local" name="end_date" id="end_date" wire:model="end_date" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel panel-inverse">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <strong class="uppercase">Totales</strong>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                        <tbody>
                                            <tr>
                                                <td>Venta de contado</td>
                                                <td>{{ number_format($ventas_contado,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Venta a crédito</td>
                                                <td>{{ number_format($ventas_credito,2,'.',',') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #010e2c;" class="text-white">
                                                <td class="text-right"> <strong>Total de Venta</strong></td>
                                                <td><strong>{{ number_format($total_ventas,2,'.',',') }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- end panel-body -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-inverse">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <strong class="uppercase">Entradas de Dinero</strong>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                        <tbody>
                                            <tr>
                                                <td>Saldo Inicial</td>
                                                <td>{{ number_format($start_balance,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Venta Contado</td>
                                                <td>{{ number_format($ventas_contado,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Ingresos por CXC</td>
                                                <td>{{ number_format($cxcs,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Otras Entradas</td>
                                                <td>{{ number_format($ventas_otros,2,'.',',') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #010e2c;" class="text-white">
                                                <td class="text-right"> <strong>Total</strong></td>
                                                <td><strong>{{ number_format($totalEntradas,2,'.',',') }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- end panel-body -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="panel panel-inverse">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <strong class="uppercase">Salidas de Dinero</strong>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                        <tbody>
                                            <tr>
                                                <td>Compras/Gastos</td>
                                                <td>{{ number_format($compras,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Pagos a CXP</td>
                                                <td>{{ number_format($cxps,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Otras Salidas</td>
                                                <td>{{ number_format($totalSalidas,2,'.',',') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #010e2c;" class="text-white">
                                                <td class="text-right"> <strong>Total</strong></td>
                                                <td><strong>{{ number_format($total_compras,2,'.',',') }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- end panel-body -->
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="panel panel-inverse">
                                <!-- begin panel-heading -->
                                <div class="panel-heading">
                                    <strong class="uppercase">Cierre</strong>
                                </div>
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                        <tbody>
                                            <tr>
                                                <td>Efectivo Real</td>
                                                <td>{{ number_format($totalCaja,2,'.',',') }}</td>
                                            </tr>
                                            <tr>
                                                <td>Bancos</td>
                                                <td>{{ number_format($total_banco,2,'.',',') }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr style="background-color: #010e2c;" class="text-white">
                                                <td class="text-right"> <strong>Total</strong></td>
                                                <td><strong>{{ number_format($totalCaja+$total_banco,2,'.',',') }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- end panel-body -->
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                <tbody>
                                    <tr>
                                        <td>EFECTIVO ESTIMADO</td>
                                        <td><strong>{{ number_format($efectivo_estimado,2,'.',',') }}</strong></td>
                                        <td>DIFERENCIA </td>
                                        <td><strong>{{ number_format($efectivo_estimado-($totalCaja+$total_banco),2,'.',',') }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-inverse">
                        <!-- begin panel-heading -->
                        <div class="panel-heading">
                            <strong class="uppercase ">Distribución Dinero Ingresado</strong>
                        </div>
                        <!-- begin panel-body -->
                        <div class="panel-body row">
                            <div class="col-lg-4">
                                <table id="data-table-bancos" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="4">BANCOS</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">METODO DE PAGO</th>
                                            <th class="text-nowrap">EN SISTEMA</th>
                                            <th class="text-nowrap">MONTO REAL</th>
                                            <th class="text-nowrap">DIFERENCIA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>TRANSFERENCIA</td>
                                            <td>{{number_format($transferencia,2,'.',',')}}</td>
                                            <td><input class="form-control" type="number" name="transferencia" wire:model="transferenciaR"></td>
                                            <td>{{number_format(($transferenciaR)?$transferencia-$transferenciaR:0,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>SINPE</td>
                                            <td>{{number_format($sinpe,2,'.',',')}}</td>
                                            <td><input class="form-control" type="number" name="sinpe" wire:model="sinpeR"></td>
                                            <td>{{number_format(($sinpe)?$sinpe-$sinpeR:0,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>TARJETA</td>
                                            <td>{{number_format($tarjeta,2,'.',',')}}</td>
                                            <td><input class="form-control" type="number" name="tarjeta" wire:model="tarjetaR"></td>
                                            <td>{{number_format(($tarjeta)?$tarjeta-$tarjetaR:0,2,'.',',')}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td colspan="3" class="text-right"> <strong>TOTAL DINERO EN BANCOS</strong></td>
                                            <td><strong>{{ number_format($total_banco,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-3">
                                <table id="data-table-efectivo" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="2">EFECTIVO</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">METODO DE PAGO</th>
                                            <th class="text-nowrap">EN SISTEMA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>EFECTIVO</td>
                                            <td>{{number_format($efectivo,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>CHEQUE</td>
                                            <td>{{number_format($cheque,2,'.',',')}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td class="text-right"> <strong>TOTAL DINERO EN EFECTIVO</strong></td>
                                            <td><strong>{{ number_format($total_efectivo,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-5">
                                <table id="data-table-bancos" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="4">OTROS MOVIMIENTOS DE EFECTIVO</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">DETALLE</th>
                                            <th class="text-nowrap">ENTRADA</th>
                                            <th class="text-nowrap">SALIDA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>ENTRADAS POR CXC</td>
                                            <td><input class="form-control" type="number" name="otras_entradas_cxc" wire:model="otras_entradas_cxc"></td>
                                            <td>0.00</td>
                                        </tr>
                                        <tr>
                                            <td>OTRAS ENTRADAS</td>
                                            <td><input class="form-control" type="number" name="otras_entradas_otras" wire:model="otras_entradas_otras"></td>
                                            <td>0.00</td>
                                        </tr>
                                        <tr>
                                            <td>SALIDAS POR CXP</td>
                                            <td>0.00</td>
                                            <td><input class="form-control" type="number" name="otras_salidas_cxp" wire:model="otras_salidas_cxp"></td>
                                        </tr>
                                        <tr>
                                            <td>OTRAS SALIDAS</td>
                                            <td>0.00</td>
                                            <td><input class="form-control" type="number" name="otras_salidas_otras" wire:model="otras_salidas_otras"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td colspan="2" class="text-right"> <strong>TOTAL</strong></td>
                                            <td><strong>{{ number_format($total_otros_movimientos,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- end panel-body -->
                    </div>
                    <div class="panel panel-inverse">
                        <!-- begin panel-heading -->
                        <div class="panel-heading">
                            <strong class="uppercase ">Arqueo Efectivo y Valores</strong>
                        </div>
                        <!-- begin panel-body -->
                        <div class="panel-body row">
                            <div class="col-lg-3">
                                <table id="data-table-valores" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="3">BILLETES</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">DENOMINACIÓN</th>
                                            <th class="text-nowrap">CANTIDAD</th>
                                            <th class="text-nowrap">MONTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1,000</td>
                                            <td><input class="form-control" type="number" name="cant_1000" wire:model="cant_1000"></td>
                                            <td>{{number_format($total_1000,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>2,000</td>
                                            <td><input class="form-control" type="number" name="cant_2000" wire:model="cant_2000"></td>
                                            <td>{{number_format($total_2000,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>5,000</td>
                                            <td><input class="form-control" type="number" name="cant_5000" wire:model="cant_5000"></td>
                                            <td>{{number_format($total_5000,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>10,000</td>
                                            <td><input class="form-control" type="number" name="cant_10000" wire:model="cant_10000"></td>
                                            <td>{{number_format($total_10000,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>20,000</td>
                                            <td><input class="form-control" type="number" name="cant_20000" wire:model="cant_20000"></td>
                                            <td>{{number_format($total_20000,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>50,000</td>
                                            <td><input class="form-control" type="number" name="cant_50000" wire:model="cant_50000"></td>
                                            <td>{{number_format($total_50000,2,'.',',')}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td colspan="2" class="text-right"> <strong>Total</strong></td>
                                            <td><strong>{{ number_format($total_billetes,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-3">
                                <table id="data-table-valores" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="3">MONEDAS</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">DENOMINACIÓN</th>
                                            <th class="text-nowrap">CANTIDAD</th>
                                            <th class="text-nowrap">MONTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>5</td>
                                            <td><input class="form-control" type="number" name="cant_5" wire:model="cant_5"></td>
                                            <td>{{number_format($total_5,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td><input class="form-control" type="number" name="cant_10" wire:model="cant_10"></td>
                                            <td>{{number_format($total_10,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>25</td>
                                            <td><input class="form-control" type="number" name="cant_25" wire:model="cant_25"></td>
                                            <td>{{number_format($total_25,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>50</td>
                                            <td><input class="form-control" type="number" name="cant_50" wire:model="cant_50"></td>
                                            <td>{{number_format($total_50,2,'.',',')}}</td>
                                        </tr>
                                        <tr>
                                            <td>100</td>
                                            <td><input class="form-control" type="number" name="cant_100" wire:model="cant_100"></td>
                                            <td>{{number_format($total_100,2,'.',',')}}
                                        <tr>
                                            <td>500</td>
                                            <td><input class="form-control" type="number" name="cant_500" wire:model="cant_500"></td>
                                            <td>{{number_format($total_500,2,'.',',')}}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td colspan="2" class="text-right"> <strong>Total</strong></td>
                                            <td><strong>{{ number_format($total_monedas,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                    <thead>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap text-center" colspan="3">CHEQUES</th>
                                        </tr>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <th class="text-nowrap">NOMBRE</th>
                                            <th class="text-nowrap">REFERENCIA</th>
                                            <th class="text-nowrap">MONTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i=0; $i < $linesCheques; $i++) <tr>
                                            <td><input class="form-control" type="text" wire:model="chequeN.{{$i}}"></td>
                                            <td><input class="form-control" type="text" wire:model="chequeR.{{$i}}"></td>
                                            <td><input class="form-control" type="number" wire:model="chequeT.{{$i}}"></td>
                                            </tr>
                                            @endfor
                                    </tbody>
                                    <tfoot>
                                        <tr style="background-color: #010e2c;" class="text-white">
                                            <td colspan="2" class="text-right"> <strong>Total</strong></td>
                                            <td><strong>{{ number_format($totalCheques,2,'.',',') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                                    <tbody>
                                        <tr>
                                            <td>Total en caja #{{$number_terminal}} </td>
                                            <td><input disabled class="form-control" type="number" wire:model="totalCaja"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- end panel-body -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Realizar Cierre</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('ccr_modal_hide', event => {
        $('#CCRModal').modal('hide');
    });
</script>