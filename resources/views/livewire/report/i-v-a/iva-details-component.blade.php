<div>
    <div class="text-center mb-3">
        <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
        <h2 class=""> Control Anual de IVA </h2>
        <h4 class=""> Año</h4>
        <div class="row justify-content-center">
            <select class="form-control col-xs-1" name="year" wire:model="year2">
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
            </select>
        </div>
        <h4 class=""> Actividad Economica</h4>
        <div class="row justify-content-center mb-3">
            <select class="form-control col-xs-4" name="ae" wire:model="ae">
                @foreach($allAE as $a_e)
                <option value="{{ $a_e->number}}">{{ $a_e->name_ea}}</option>
                @endforeach
            </select>
        </div>

        <button type="button" wire:click.prevent="exportExcel()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Exportar a Excel</button>
    </div>

    @foreach($categories as $category)
    @if(count($Ventas->where("Categoria_MH",$category->name))>0)
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">{{ $category->name }}</h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body" width="100%" style="font-size:14px">
            <!-- begin bienes-content -->
            <div class="invoice-content">
                <!-- begin table-responsive -->
                <div class="table-responsive bg-gray-400">
                    <table class="table table-invoice" id="{{ $category->name }}" name="$category->name">
                        <thead>
                            <tr class="text-white" style="background-color:#192743; white-space: nowrap;">
                                <th class="text-center">Ventas de {{ $category->name }}</th>
                                <th class="text-center" colspan="2">Enero {{ $year }}</th>
                                <th class="text-center" colspan="2">Febrero {{ $year }}</th>
                                <th class="text-center" colspan="2">Marzo {{ $year }}</th>
                                <th class="text-center" colspan="2">Abril {{ $year }}</th>
                                <th class="text-center" colspan="2">Mayo {{ $year }}</th>
                                <th class="text-center" colspan="2">Junio {{ $year }}</th>
                                <th class="text-center" colspan="2">Julio {{ $year }}</th>
                                <th class="text-center" colspan="2">Agosto {{ $year }}</th>
                                <th class="text-center" colspan="2">Setiembre {{ $year }}</th>
                                <th class="text-center" colspan="2">Octubre {{ $year }}</th>
                                <th class="text-center" colspan="2">Noviembre {{ $year }}</th>
                                <th class="text-center" colspan="2">Diciembre {{ $year }}</th>
                                <th class="text-center" colspan="2">Total</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                                <td class="text-center">Monto</td>
                                <td class="text-center">Impuesto</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $impuestoM = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                            $montoM = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; ?>
                            @foreach($taxes as $tax)
                            <?php $sumaMonto = 0;
                            $sumaImpuesto = 0; ?>
                            <tr class="gradeU">
                                <td width="1%" class="text-center">Venta {{ ($tax == 0 )?"Exenta":" al ".$tax."%" }}</td>
                                @foreach($months as $month)
                                @if(count($Ventas->where("Categoria_MH",$category->name)->where("Mes",$month))>0)
                                @if($line = $Ventas->where("Categoria_MH",$category->name)->where("Mes",$month)->where("Tarifa",$tax)->first())
                                <?php $nombre = ("Impuesto_" . $tax) ?>
                                <?php $sumaMonto += $line->monto;
                                $sumaImpuesto += $line->$nombre;
                                $montoM[$month] += $line->monto;
                                $impuestoM[$month] += $line->$nombre; ?>
                                <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($line->monto,2,'.',',') }}</td>
                                <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($line->$nombre,2,'.',',') }}</td>
                                @else
                                <td width="2%" class="text-center">0</td>
                                <td width="2%" class="text-center">0</td>
                                @endif
                                @else
                                <td width="2%" class="text-center">0</td>
                                <td width="2%" class="text-center">0</td>
                                @endif
                                @endforeach
                                <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($sumaMonto,2,'.',',')}} </td>
                                <td width="2%" class="text-center" style="white-space: nowrap;">{{ number_format($sumaImpuesto,2,'.',',') }}</td>
                            </tr>


                            @endforeach
                            <tr class="gradeU" style="background-color: #192743; color: aliceblue;">
                                <?php $montoT = 0;
                                $impuestoT = 0; ?>
                                @foreach($months as $month)
                                @if($month == 1)
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue;">Totales</td>
                                @endif
                                <td width="15" class="text-center" style="white-space: nowrap;">{{ number_format($montoM[$month],2,'.',',') }}</td>
                                <td width="15" class="text-center" style="white-space: nowrap;"> {{ number_format($impuestoM[$month],2,'.',',') }}</td>
                                <?php $montoT += $montoM[$month];
                                $impuestoT += $impuestoM[$month]; ?>
                                @endforeach
                                <td width="15" class="text-center" style="white-space: nowrap;">{{ number_format($montoT,2,'.',',') }}</td>
                                <td width="15" class="text-center" style="white-space: nowrap;"> {{ number_format($impuestoT,2,'.',',') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
            <!-- end bienes-content -->
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
    @endif
    @endforeach
</div>