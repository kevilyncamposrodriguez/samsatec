<!-- #modal-addDocument -->
<div wire:ignore.self class="modal modal-message fade" id="sendReportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

            </div>
            <div id="typeDoc" align="center">
                @if($company->logo_url != "")
                <img src="{{ $logo_url }}" width="200">
                @else
                <img src="{{ $logo_url }}">
                @endif
                <br>
                <h3>
                    <strong>{{ $title }}</strong><br>
                    {{ $company->name }}<br>
                    {{ $company->id_card }}<br>
                    Tel: {{ $company->phone_company }} Correo: {{ $company->email_company }}
                </h3><br>
            </div>
            <div class="modal-body col-lg-12" style="background-color: #E5E4E3;">
                <div id="company">
                    <div class="name">
                        <h3><strong>!ATENCION¡ </strong> {{ $client }}</h3>
                    </div>
                    <div> A continuación le presentamos para su conocimiento el estado de facturas que se encuentran pendientes de pago al día de hoy;
                        si ya realizó dichos pagos favor enviar el comprobante al correo o teléfono de la empresa, de lo contrario dejamos a su disposición las cuentas bancarias para la realización de estos.
                        !Agradecemos su pronto pago, según las condiciones pactadas con nuestro departamento¡</div>
                </div>
                <br>
                <strong>
                    Cuentas para depósitos:
                </strong>
                <textarea class="form-control" class="col-lg-12" disabled id="accounts" rows="10" placeholder="Cuentas para mostrar en correos e informes de CXC" wire:model="accounts"></textarea>
                <br>
                <table id="data-table-document" class="table table-striped col-lg-12">
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
                            <td width="10%" align="center">{{ $cxc['consecutivo'] }}</td>
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

                <br><br>
                <form style="background-color: #A3AAB1;" action="" method="POST" name="send_document" id="send_document" class="form-horizontal form-bordered">
                    @csrf

                    <!-- begin form-group -->
                    <div class="form-group" align="center">
                        <h2 text-center><strong>Datos para envío</strong></h2>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Envia a: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="email" name="mail_send" id="mail_send" class="form-control" placeholder="Envia a esta direccion" wire:model="mail_send" />
                        </div>
                        <label class="col-lg-2 col-form-label">CC a: <span class="text-danger"></span></label>
                        <div class="col-lg-4 col-xl-4">
                            <input type="email" name="cc_mail" id="cc_mail" class="form-control" placeholder="Enviar copia a..." wire:model="cc_mail" />
                        </div>
                    </div>
                    <!-- begin form-group -->

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <a href="/downloadReportCXC/{{$client}}" onclick="this.disabled=true;" class="btn btn-primary text-white">Descargar PDF</a>
                <button type="button" wire:click.prevent="sendReportCXC()" onclick="this.disabled=true;" class="btn btn-btn btn-secondary close-btn">Enviar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<script>
    window.addEventListener('sendReport_modal_hide', event => {
        $('#sendReportModal').modal('hide');
    });
</script>