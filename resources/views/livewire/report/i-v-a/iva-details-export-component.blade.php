<div>


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
                                <th class="text-center text-white" style="background-color:#192743; color: white;">Ventas de {{ $category->name }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Enero {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Febrero {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Marzo {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Abril {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Mayo {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Junio {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Julio {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Agosto {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Setiembre {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Octubre {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Noviembre {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Diciembre {{ $year }}</th>
                                <th class="text-center" style="background-color:#192743; color: white;" colspan="2">Total</th>
                            </tr>
                            <tr>
                                <td style="background-color:aliceblue; color: #192743;"></td>
                                <td style="background-color:aliceblue; color: #192743;" class="text-center">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                                <td style="background-color:aliceblue; color: #192743;">Monto</td>
                                <td style="background-color:aliceblue; color: #192743;">Impuesto</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $impuestoM = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                            $montoM = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; ?>
                            @foreach($taxes as $tax)
                            <?php $sumaMonto = 0;
                            $sumaImpuesto = 0; ?>
                            <tr class="gradeU">
                                <td width="20" class="text-center" style="background-color: #192743; color: aliceblue;">Venta {{ ($tax == 0 )?"Exenta":" al ".$tax."%" }}</td>
                                @foreach($months as $month)
                                @if(count($Ventas->where("Categoria_MH",$category->name)->where("Mes",$month))>0)
                                @if($line = $Ventas->where("Categoria_MH",$category->name)->where("Mes",$month)->where("Tarifa",$tax)->first())
                                <?php $nombre = ("Impuesto_" . $tax) ?>
                                <?php $sumaMonto += $line["monto"];
                                $sumaImpuesto += $line[$nombre];
                                $montoM[$month] += $line['monto'];
                                $impuestoM[$month] += $line[$nombre]; ?>
                                <td width="15" class="text-center">{{ number_format($line['monto'],2,'.',',') }}</td>
                                <td width="15" class="text-center">{{ number_format($line[$nombre],2,'.',',') }}</td>
                                @else
                                <td width="15" class="text-center">0</td>
                                <td width="15" class="text-center">0</td>
                                @endif
                                @else
                                <td width="15" class="text-center">0</td>
                                <td width="15" class="text-center">0</td>
                                @endif
                                @endforeach
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue;">{{ number_format($sumaMonto,2,'.',',') }}</td>
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue;">{{ number_format($sumaImpuesto,2,'.',',') }}</td>
                            </tr>
                            @endforeach
                            <tr class="gradeU" style="background-color: #192743; color: aliceblue;">
                                <?php $montoT = 0;
                                $impuestoT = 0; ?>
                                @foreach($months as $month)
                                @if($month == 1)
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue; white-space: nowrap;">Totales</td>
                                @endif
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue; white-space: nowrap;">{{ number_format($montoM[$month],2,'.',',') }}</td>
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue; white-space: nowrap;"> {{ number_format($impuestoM[$month],2,'.',',') }}</td>
                                <?php $montoT += $montoM[$month];
                                $impuestoT += $impuestoM[$month]; ?>
                                @endforeach
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue; white-space: nowrap;">{{ number_format($montoT,2,'.',',') }}</td>
                                <td width="15" class="text-center" style="background-color: #192743; color: aliceblue; white-space: nowrap;"> {{ number_format($impuestoT,2,'.',',') }}</td>
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