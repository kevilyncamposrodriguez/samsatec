<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="default.css" rel="stylesheet" />
</head>

<body>


    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td>
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <table style="width:100%">
                                        <tr>
                                            <th>
                                                @if($msg['company']->logo_url != "")
                                                <img style="width:100px; height:100px" src="{{ asset($msg['company']->logo_url) }}">
                                                @endif
                                            </th>
                                            <th>
                                                <div id="typeDoc" align="center">
                                                    <h2>
                                                        {{ $msg['company']->name  }}<br>
                                                        {{ $msg['company']->id_card }}<br>
                                                        Tel: {{$msg['company']->phone_company }} Correo: {{ $msg['company']->email_company }}<br>
                                                    </h2>
                                                    <h2><strong>Reporte de facturas pendientes de pago</strong><br></h2>
                                                </div>
                                            </th>
                                            <th>Generado:<br>
                                                {{ date("Y-m-d H:i:s") }}
                                            </th>
                                        </tr>
                                    </table>
                                </tr>
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        A continuación le presentamos para su conocimiento el estado de facturas que se encuentran pendientes de pago al día de hoy;
                                        si ya realizó dichos pagos favor enviar el comprobante al correo o teléfono de la empresa, de lo contrario dejamos a su disposición las cuentas bancarias para la realización de estos.
                                        !Agradecemos su pronto pago, según las condiciones pactadas con nuestro departamento¡
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table class="footer" align="center" width="100%">
                                <tr>
                                    <td class="content-cell" align="center">

                                        <!-- begin panel -->
                                        <div class="panel panel-inverse">
                                            <!-- begin panel-heading -->
                                            <div class="panel-heading">
                                                <h1 class="panel-title font-medium uppercase">Facturas Pendientes de Pago</h1>
                                            </div>
                                            <!-- end panel-heading -->
                                            @if (session()->has('message'))
                                            <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                                                <div class="flex">
                                                    <div>
                                                        <p class="text-sm">{{ session('message') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            <!-- begin panel-body -->
                                            <div class="panel-body" wire:ignore>
                                                <table id="data-table-document" class="table table-striped">
                                                    <thead>
                                                        <tr style="background-color: #192743; color: aliceblue;">
                                                            <th width="10%">CONSECUTIVO</th>
                                                            <th width="10%" data-orderable="false">FECHA DE VENTA</th>
                                                            <th width="10%" class="text-nowrap">FECHA DE VENCIMIENTO</th>
                                                            <th width="7%" class="text-nowrap">DIAS VENCIDOS</th>
                                                            <th width="9%" class="text-nowrap">MONTO TOTAL</th>
                                                            <th width="9%" class="text-nowrap">AL DÍA</th>
                                                            <th width="9%" class="text-nowrap">0 A 15 DIAS</th>
                                                            <th width="9%" class="text-nowrap">15 A 30 DIAS</th>
                                                            <th width="9%" class="text-nowrap">30 A 60 DIAS</th>
                                                            <th width="9%" class="text-nowrap">60 A 90 DIAS</th>
                                                            <th width="9%" class="text-nowrap">MAS DE 90 DIAS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $total_saldo = 0;
                                                        $aldia = 0;
                                                        $de0a15 = 0;
                                                        $de15a30 = 0;
                                                        $de30a60 = 0;
                                                        $de60a90 = 0;
                                                        $de90s = 0;
                                                        ?>
                                                        @foreach($msg['cxcs'] as $cxc)
                                                        <?php
                                                        if ($cxc["dias_de_atraso"] == 'Al Dia') {
                                                            $aldia += $cxc["saldo_pendiente"];
                                                        } else if ($cxc["dias_de_atraso"] == '0 a 15 dias de atraso') {
                                                            $de0a15 += $cxc["saldo_pendiente"];
                                                        } else if ($cxc["dias_de_atraso"] == '15 a 30 dias de atraso') {
                                                            $de15a30 += $cxc["saldo_pendiente"];
                                                        } else if ($cxc["dias_de_atraso"] == '30 a 60 dias de atraso') {
                                                            $de30a60 += $cxc["saldo_pendiente"];
                                                        } else if ($cxc["dias_de_atraso"] == '60 a 90 dias de atraso') {
                                                            $de60a90 += $cxc["saldo_pendiente"];
                                                        } else {
                                                            $de90s += $cxc["saldo_pendiente"];
                                                        }
                                                        $total_saldo += $cxc["saldo_pendiente"];
                                                        ?>
                                                        <tr class="gradeU">
                                                            <td width="10%" align="center">{{ $cxc["consecutivo"] }}</td>
                                                            <td width="10%" align="center">{{ substr($cxc["fecha_de_venta"], 0, 10) }}</td>
                                                            <td width="10%" align="center">{{ substr($cxc["fecha_de_vencimiento"], 0, 10) }}</td>
                                                            <td width="7%" align="center" <?php if ($cxc["dias_vencidos"] > 0) { ?> style="color: red;" <?php } else { ?> style="color: blue;" <?php } ?>>{{ $cxc["dias_vencidos"] }}</td>
                                                            <td width="9%" align="center">{{ number_format($cxc["saldo_pendiente"],2,'.',',') }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == 'Al Dia') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == '0 a 15 dias de atraso') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == '15 a 30 dias de atraso') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == '30 a 60 dias de atraso') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == '60 a 90 dias de atraso') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                            <td width="9%" align="center">{{ ($cxc["dias_de_atraso"] == 'Mas de 90 dias de atraso') ? number_format($cxc["saldo_pendiente"], 2, '.', ',') : '0' }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr style="background-color: #192743; color: aliceblue;">
                                                            <th colspan="4">TOTALES</th>
                                                            <td align="center">{{ number_format($total_saldo,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($aldia,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($de0a15,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($de15a30,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($de30a60,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($de60a90,2,',','.') }}</td>
                                                            <td align="center">{{ number_format($de90s,2,',','.') }}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <!-- end panel-body -->
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-cell" align="center">
                                        <strong>
                                            Cuentas para depósitos:
                                        </strong>
                                        {{ $msg['company']->accounts }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="content-cell" align="center">
                                        <br>
                                        © {{ date('Y') }} Samsatec. @lang('Todos los derechos reservados.')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <br><br><br><br>
</body>

</html>