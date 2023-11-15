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
            font-size: 1.1em;
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
            text-align: center;
        }

        #logoC img {
            width: 80px;
            height: 80px;
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

        #invoice h3 {
            color: #010e2c;
            font-size: 1.1em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 0 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 5px 2px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
            font-size: 0.7em;
        }

        table td {
            text-align: right;
        }

        table .no {
            color: #FFFFFF;
            font-size: 0.8em;
            background: #192743;
            text-align: center;
        }

        table .desc {
            text-align: center;
        }

        table .unit,
        table .price,
        table .tax {
            text-align: center;
            background: #DDDDDD;
        }

        table .qty,
        table .discount,
        table .exo {
            text-align: center;
        }

        table .total {
            text-align: center;
            background: #192743;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.discount,
        table td.exo,
        table td.price,
        table td.tax,
        table td.total,
        table td.desc {
            font-size: 0.6em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 5px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #010e2c;
            font-size: 1em;
            border-top: 1px solid #010e2c;

        }

        table tfoot tr td:first-child {
            border: none;
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
    </style>
</head>

<body>
    <div id="logoC">
        @if($company->logo_url != "")
        <img src="{{ $company->logo_url }}">
        @endif
    </div>
    <div id="typeDoc">
        {{ $title }} <br> N° {{ $document->consecutive }} <br>@if($document->key) Clave: {{ $document->key }} @endif
        <br>
    </div>

    <header class="clearfix">

        <div id="company">
            <div class="name">{{ $company->name }}</div>
            <div>{{ $company->id_card }}</div>
            <div>DIR: {{ $bo->name_province.', '.$bo->name_canton.', '.$bo->name_district }}</div>
            <div>{{ $bo->other_signs }}</div>
            <div>TEL: {{ $bo->phone }}</div>
            <div><a href="mailto:{{ $bo->emails }}">{{ $bo->emails }}</a></div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">PROVEEDOR:</div>
                <div class="name">{{ $client->name_provider }}</div>
                <div class="phone">{{ $client->id_card }}</div>
                <div class="address">DIR: {{ $client->name_province.', '.$client->name_canton.', '.$client->name_district }}</div>
                <div class="address">{{ $client->other_signs }}</div>
                <div class="phone">{{ $client->phone }}</div>
                <div class="email"><a href="mailto:{{ $client->emails }}">{{ $client->emails }}</a></div>
            </div>
            <div id="invoice">
                <h3>{{ $document->date_issue }}</h3>
                <div class="date">Medio de pago: {{ $document->paymentMethod }}</div>
                <div class="date">Condición venta: {{ $document->saleConditions }}</div>
                <div class="date">Plazo de credito: {{ $document->term }} días</div>
                <div class="date">Moneda: {{ $document->currency }}</div>
                @if(isset($reference))
                <div class="date">Referencia: {{ $reference->number }}</div>
                @endif
            </div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no">N°</th>
                    <th class="desc">CODIGO</th>
                    <th class="desc">DESCRIPCIÓN</th>
                    <th class="unit">UNID</th>
                    <th class="qty">CANTIDAD</th>
                    <th class="price">PRECIO</th>
                    <th class="discount">DESCUENTO</th>
                    <th class="tax">IMPUESTO</th>
                    <th class="exo">EXONERADO</th>
                    <th class="total">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $detail)
                <tr>
                    <td class="no">{{ $index++ }}</td>
                    <td class="code">{{ $detail->cabys }}</td>
                    <td class="desc">{{ $detail->detail }}</td>
                    <td class="unit">{{ $detail->sku }}</td>
                    <td class="qty">{{ $detail->qty }}</td>
                    <td class="price">{{ $detail->price }}</td>
                    <td class="discount">{{ ($detail->discounts)?$detail->discounts:0 }}</td>
                    <td class="tax">{{ (isset(json_decode($detail->taxes)->rate))?json_decode($detail->taxes)->rate:0 }}</td>
                    <td class="exo">{{ (isset(json_decode($detail->taxes)->exoneration->AmountExoneration))?json_decode($detail->taxes)->exoneration->AmountExoneration:0 }}</td>
                    <td class="total">{{ $detail->total_amount_line }}</td>
                </tr>
                @endforeach

            </tbody>
            @if(!$otherCharge)
            <tfoot>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">SUBTOTAL</td>
                    <td>{{ number_format($document->total_net_sale,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">DECUENTO TOTAL</td>
                    <td>{{ number_format($document->total_discount,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">IMPUESTO TOTAL</td>
                    <td>{{ number_format($document->total_tax,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">EXONERACIÓN TOTAL</td>
                    <td>{{ number_format($document->total_exoneration,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">OTROS CARGOS</td>
                    <td>{{ number_format($document->total_other_charges,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="7"></td>
                    <td colspan="2">TOTAL</td>
                    <td>{{ number_format(($document->total_document+$document->total_other_charges),2,'.',',') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
        @if($otherCharge)
        <br>
        <table border="0" cellspacing="0" cellpadding="0">
            <caption class="center" style="background: #010e2c;">Otros Cargos</caption>
            <thead>
                <tr>
                    <th class="no">N°</th>
                    <th class="price">TIPO CARGO</th>
                    <th class="desc">CEDULA TERCERO</th>
                    <th class="unit">NOMBRE TERCERO</th>
                    <th class="qty">DESCRIPCION CARGO</th>
                    <th class="total">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($otherCharges as $index => $charge)
                <tr>
                    <td class="no">{{ $index+1 }}</td>
                    <td class="price">{{ $charge->typeDocument }}</td>
                    <td class="desc">{{ $charge->idcard }}</td>
                    <td class="unit">{{ $charge->name }}</td>
                    <td class="qty">{{ $charge->detail }}</td>
                    <td class="total">{{ number_format($charge->amount,2,'.',',') }}</td>
                </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">SUBTOTAL</td>
                    <td>{{ number_format($document->total_net_sale,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">DESCUENTO TOTAL</td>
                    <td>{{ number_format($document->total_discount,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">IMPUESTO TOTAL</td>
                    <td>{{ number_format($document->total_tax,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">EXONERACIÓN TOTAL</td>
                    <td>{{ number_format($document->total_exonerated,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">OTROS CARGOS</td>
                    <td>{{ number_format($document->total_other_charges,2,'.',',') }}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1">TOTAL</td>
                    <td>{{ number_format(($document->total_document),2,'.',',') }}</td>
                </tr>
            </tfoot>
        </table>
        @endif
        <div id="notes">
            @if($document->note != null)
            Notas: {{ $document->note }}
            @endif
        </div>
        <br>
        <div id="notices">
            <div>Información de recibido:</div><br>
            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-globe"></i>Fecha: ________________________________</span><br><br>
            <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> Firma: ________________________________</span><br>
        </div>
    </main><br>
    <div style="text-align: center;">GRACIAS POR SU PREFERENCIA</div>
    <div style="text-align: center;">Web: samsatec.com Teléfono: 6326-6384 Correo: info@samsatec.com</div>
    <footer>
        "Autorizada mediante resolución N° DGT-R-033-2019 del 20-06-2019"
    </footer>
</body>

</html>