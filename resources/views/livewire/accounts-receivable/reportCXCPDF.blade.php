<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @font-face {
            font-family: SourceSansPro;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        #typeDoc {
            color: #010e2c;
            font-size: 1.5em;
            line-height: 1em;
            font-weight: normal;
            text-align: center;
        }

        a {
            color: #010e2c;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: 100%;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 40px;
            margin-right: 10px;
        }

        #logo img {
            height: 80px;
            width: 80px;
        }

        #typeDoc img {
            height: 80px;
            width: 80px;
        }

        #company {
            margin-top: 10px;
            padding-left: 6px;
            border-left: 6px solid #010e2c;
            float: left;
            text-align: left;
        }

        #logoC {
            width: 100%;
            position: relative;
            margin-top: 20px;
            float: right;
            text-align: right;
        }

        #logoC img {
            height: 50px;
        }

        #details {
            margin-top: 10px;
            margin-bottom: 20px;
            width: 100%;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #010e2c;
            float: left;
        }

        #client .to {
            color: #777777;
            font-size: 0.8em;
        }

        div.name {
            font-size: 0.8em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            width: 100%;
            position: relative;
            float: right;
            text-align: right;
        }

        #logoCompany {
            width: 100%;
            position: fixed;
            text-align: center;
        }

        #invoice h1 {
            color: #010e2c;
            font-size: 1.5em;
            line-height: 1em;
            font-weight: normal;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }



        #thanks {
            color: #010e2c;
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #010e2c;
            margin-bottom: 20px;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        #notes {
            font-size: 1.2em;
            padding-left: 6px;
            border-left: 6px solid #010e2c;
            margin-bottom: 20px;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            text-align: center;
        }

        #date {
            text-align: right;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <table style="width:100%">
            <tr>
                <th style="width:20%">
                    <div id="typeDoc" align="center">
                        @if($logo_url != "")
                        <img style="width:200px; height:200px" src="{{ $logo_url }}">
                        @endif
                    </div>
                </th>
                <th style="width:80%">
                    <div id="typeDoc" align="center">
                        <h5>
                            {{ $company->name }}<br>
                            {{ $company->id_card }}<br>
                            Tel: {{ $company->phone_company }} Correo: {{ $company->email_company }}<br>
                        </h5>
                        <h5><strong>{{ $title }}</strong><br></h5>
                    </div>
                </th>
                <th style="width:20%">
                    Generado:<br>
                    {{ date("Y-m-d H:i:s") }}
                </th>
            </tr>
        </table>

    </header>
    <main>
        <p>
        <div class="name">
            <h3><strong>!ATENCION¡ </strong> {{ $client }}</h3>
        </div>
        <div> A continuación le presentamos para su conocimiento el estado de facturas que se encuentran pendientes de pago al día de hoy;
            si ya realizó dichos pagos favor enviar el comprobante al correo o teléfono de la empresa, de lo contrario dejamos a su disposición las cuentas bancarias para la realización de estos.
            !Agradecemos su pronto pago, según las condiciones pactadas con nuestro departamento¡</div>
        </p>
        <p>
        <table id="data-table-document" class="table table-striped" style="width: 100%;">
            <thead>
                <tr style="background-color: #192743; color: aliceblue;">
                    <th width="10%">CONSECUTIVO</th>
                    <th width="10%" class="text-nowrap">VENTA</th>
                    <th width="10%" class="text-nowrap">VENCIMIENTO</th>
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

                @foreach($cxcs as $cxc)
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
                    <td width="10%" align="center">{{ $cxc->consecutivo }}</td>
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
        </p>
        <br>
        <div id="notices">
            !GRACIAS POR SU PREFERENCIA¡
        </div>
    </main>
    <br><br>
    <footer align="justify">
        <strong>
            Cuentas para depósitos:
        </strong>
        {{ $company->accounts }}
    </footer>
</body>

</html>