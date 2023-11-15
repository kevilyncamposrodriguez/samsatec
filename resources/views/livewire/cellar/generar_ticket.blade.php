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
        <h2>{{ $emisor->emails }} - {{ $emisor->phone }}</h2>
        <h2>Consecutivo</h2>
        <h6>{{ $consecutive }}</h6>
        -------------------------------------------------------------------------
        <h6>Cliente: {{ $receptor->name_client }} - {{ $receptor->id_card }}</h6>
        <h6>{{ $receptor->emails }} - {{ $receptor->phone }}</h6>
        <br>
        <table style="width: 100%;">
            <thead>
                <tr class="centrado">
                    <th class="cantidad" style="width: 40%;">DESCRIPCIÓN </th>
                    <th class="C" style="width: 30%;">CANT. SOLICITADA</th>
                    <th class="precio" style="width: 30%;">CANT. DESPACHADA</th>
                </tr>
            </thead>
        </table>
        <table style="width: 100%;">
            <tbody>
                @foreach ($detail as $detail)
                <tr>
                    <td style="width: 40%;"> {{ $detail->detail }}</td>
                    <td class="centrado" style="width: 30%;">{{ $detail->qty }}</td>
                    <td class="centrado" style="width: 30%;"></td>
                </tr>
                @endforeach
            </tbody>
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
    </div>
</body>

</html>