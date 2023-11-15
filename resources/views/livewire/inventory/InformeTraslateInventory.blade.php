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

        #invoice h1 {
            color: #010e2c;
            font-size: 1.5em;
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
    <div id="invoice">
        Traslado Realizado: {{ $date }}
        <br>
    </div>
    <div id="logoC">
        @if($company->logo_url != "")
        <img src="{{ $company->logo_url }}">
        @endif
    </div>
    <div id="typeDoc">
        {{ $title }}
        <br>
        {{ $company->name }}
        <br>
    </div>
    <main>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr class="text-white" style="background-color:#192743;">
                    <th class="text-center" width="1%">N°</th>
                    <th class="text-center" width="20%">PRODUCTO</th>
                    <th class="text-center" width="9%">CANT. ACTUAL</th>
                    <th class="text-center" width="9%">CANT. TRASLADO</th>
                    <th class="text-center" width="10%">CANT FINAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allLinesT as $index => $line)
                <tr class="gradeU text-center">
                    <td width="1%" class="no">{{ $index+1 }}</td>
                    <td width="20%" class="desc">{{ $line['name_productE'] }}</td>
                    <td width="10%" class="price">{{ number_format($line['qtyAE'],2,'.',',') }}</td>
                    <td width="10%" class="desc">{{ number_format($line['qtyTransferE'],2,'.',',') }}</td>
                    <td width="10%" class="total">{{ number_format($line['qtyNE'],2,'.',',') }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>

        <div id="notes">
            Observaciones: {{ $observation }}
        </div>
        <br>
    </main>

</body>

</html>