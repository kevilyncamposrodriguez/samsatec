<?php
$medidaTicket = 340;

?>
<!DOCTYPE html>
<html>

<head>

    <style>
        * {
            font-size: 14px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 14px;
        }

        h6 {
            font-size: 9px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
            page-break-inside: unset;
        }

        td.precio {
            text-align: right;
            font-size: 14px;
        }

        td.cantidad {
            font-size: 14px;
        }

        td.producto {
            text-align: left;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: <?php echo $medidaTicket ?>px;
            max-width: <?php echo $medidaTicket ?>px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
            height: 100%;
            text-align: center;
            align-content: center;

        }
    </style>
</head>

<body style="margin-left: 5px;">
    <div class="ticket centrado">
        <h2>{{ $date }}</h2>
        <h1>{{ $typeDoc }}</h1>
        <h1>{{ $name_compania }}</h1>
        <h1>Sucursal: {{ $emisor->name_branch_office }}</h1>
        <h1>Terminal: {{ $terminal }}</h1>
        @if($type_doc != '00' && $type_doc != '11' && $type_doc != '99')
        <h1>Ced:{{ $ced_emisor }}</h1>
        @endif
        <h6>{{ $emisor->emails }} - {{ $emisor->phone }}</h6>
        <h2>Consecutivo</h2>
        <h6>{{ $consecutive }}</h6>
        @if($type_doc != '00' && $type_doc != '11' && $type_doc != '99')
        <h2>Clave</h2>
        <h6>{{ $key }}</h6>
        @endif
        -------------------------------------------------------------------------
        <h6>Cliente: {{ $receptor->name_client }} - {{ $receptor->id_card }}</h6>
        <h6>{{ $receptor->emails }} - {{ $receptor->phone }}</h6>
        <br>
        <table style="width: 100%;">
            <thead>
                <tr class="centrado">
                    <th class="cantidad" style="width: 25%;">DESCRIPCIÓN </th>
                    <th class="producto" style="width: 25%;">CANT. </th>
                    <th class="precio" style="width: 25%;">PRECIO </th>
                    <th class="total" style="width: 25%;">TOTAL </th>
                </tr>
            </thead>
        </table>
        <table style="width: 100%;">
            <tbody>
                @foreach ($detail as $detail)
                <tr >
                    <td style="width: 30%;"> {{ $detail->detail }}</td>
                    <td class="centrado"  style="width: 20%;">{{ $detail->qty_dispatch }}</td>
                    <td class="centrado"  style="width: 20%;">{{ $detail->price_unid }} </td>
                    <td class="centrado"  style="width: 30%;">{{ $detail->total_amount_line }} </td>
                </tr>
                @endforeach
            </tbody>
            <tr>
                <td class="cantidad"></td>
                <td class="producto">
                <td class="precio">
                    <strong>SUBTOTAL</strong><br>
                    <strong>DESCUENTO</strong><br>
                    <strong>IMPUESTOS</strong><br>
                    <strong>TOTAL</strong>
                </td>
                <td class="precio">
                    {{ number_format($subTotal, 2) }}<br>
                    {{ number_format($desc, 2) }}<br>
                    {{ number_format($tax, 2) }}<br>
                    {{ number_format($total, 2) }}
                </td>

            </tr>
        </table>
        <br>
        <p class="centrado">¡GRACIAS POR SU COMPRA!</p>
        <br>
        <p class="centrado">SISTEMA UTILIZADO
            <br>Web: samsatec.com
            <br>Teléfono: 6326-6384
            <br>Correo: info@samsatec.com
            <br>
            <br>
            -------------------------------------------------------------------------
            <br>
            @if($type_doc != '00' && $type_doc != '11' && $type_doc != '99')
            "Autorizada mediante resolución N° DGT-R-033-2019 del 20-06-2019"
            @endif
        </p>
        <br>
        -------------------------------------------------------------------------
    </div>
</body>

</html>