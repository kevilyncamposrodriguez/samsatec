@if($this->id_client)
<div>
    <div style="text-align: right; margin-top: -21px;">
        <div class="btn-group btn-group-justified uppercase">
            <button type="button" title="Factura" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','11')" class="btn btn-primary btn-sm text-white m-t-4 m-b-4">Factura</button>
            <button type="button" title="Factura Electronica" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','01')" class="btn btn-secondary btn-sm text-white m-t-4 m-b-4">Factura Electronica</button>
            <button type="button" title="Tiquete Electronico" data-toggle="modal" data-target="#newDocumentModal" wire:click="$emit('newDocument','04')" class="btn btn-primary btn-sm m-t-4 m-b-4">Tiquete Electronico</button>
        </div>
    </div>
    @livewire('document.new-document-component')
    @include('livewire.accounts-receivable.pays')
    @include('livewire.accounts-receivable.sendReport')
    @livewire('client.create-client-component')
    @livewire('product.create-product-component')
    @livewire('product.create-service-component')
    @livewire('document.edit-document-component')
    @livewire('payment-invoice.pay-invoices-component')
    @include('livewire.accounts-receivable.notes')
    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats bg-green-600" style="background-color: #277C4C;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>Al Día</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango0,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #527C27;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>0 a 15 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango0a15,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #7B7C27;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>15 a 30 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango15a30,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C28122;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>30 a 60 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango30a60,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C24B22;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>60 a 90 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango60a90,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C22222;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>Más de 90 días</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rangoMas90,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
    <div class="col-md-12">
        <div class="widget widget-stats row" style="background-color: #192743;">

            <div class="col-md-3 text-center">
                <h2 class="text-center">
                    Plazo crédito:
                </h2>
                <h3 class="text-center">
                    {{ $clientfind->time }} @if($clientfind->time == 1)dia @else días @endif
                </h3>
                <h2 class="text-center">
                    Crédito asignado:
                </h2>
                <h3 class="text-center">
                    ₡ {{ $clientfind->total_credit }}
                </h3>
            </div>
            <div class="col-md-6 text-center">
                <div class="text-white">
                    <h2>{{$clientfind->name_client}}</h2>
                    <h3>CED: {{$clientfind->id_card}}</h3>
                </div>
                <h6>
                    <p class="text-center">
                        <span class="m-r-5"><i class="fa fa-fw fa-lg fa-map-marker-alt"></i>{{ $clientfind->other_signs }}, {{ $clientfind->district }}, {{ $clientfind->canton }}, {{ $clientfind->province }}</span>
                    </p>
                    <p class="text-center">
                        <span class="m-r-10"><i class="fa fa-fw fa-lg fa-phone-volume"></i> ({{$clientfind->phone_code}}) {{$clientfind->phone}}</span>
                        <span class="m-r-10"><i class="fa fa-fw fa-lg fa-envelope"></i> {{ $clientfind->emails }}</span>
                    </p>
                </h6>
            </div>
            <div class="col-md-3 text-center">
                <h2 class="text-center">
                    Total cuentas por cobrar:
                </h2>
                <h3 class="text-center">
                    ₡ {{ number_format($total,2,'.',',') }}
                </h3>
                <h2 class="text-center">
                    Crédito disponible:
                </h2>
                <h3 class="text-center {{ (($clientfind->total_credit-$total)<0)?'text-red':'' }}">
                    ₡ {{ number_format($clientfind->total_credit-$total,2,'.',',') }}
                </h3>
            </div>
        </div>
    </div>
    @if (($clientfind->total_credit-$total) < 0) <div class="border-t-2 border-white rounded-b text-white px-1 py-1 shadow-md my-3 text-center" style="background-color: #C22222;" role="alert">
        <div class="">
            <div>
                <p class="text-sm text-center">Este cliente ha llegado a su limite de crédito, favor tomar las consideraciones del caso.</p>
            </div>
        </div>
</div>
@endif
<div>
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <a title="Vista Resumida" href="/cxc" class="btn  btn-lg btn-blue m-r-5"><i style="color: white;" class="fa fa-arrow-left"></i> Atras</a>
            <h2 class="panel-title" class="m-l-5"> </h2>
            <div class="panel-heading-btn">

                <div class="btn-group m-r-8 m-b-1">
                    <button class="btn btn-lg  btn-blue" title="Notas" data-toggle="modal" data-target="#notesModal" wire:click="chargeNotes()"> Notas</button>
                    <button class="btn btn-lg  btn-secondary" title="Enviar reporte por correo electrónico" data-toggle="modal" data-target="#sendReportModal" wire:click="preSendReport()">Enviar Reporte</button>
                    <button class="btn btn-lg  btn-blue" title="Procesar Seleccionados" data-toggle="modal" {{ (count($selects)>0)?'':"disabled" }} data-target="#paysModal" wire:click="proccess()"> Procesar</button>
                </div>
            </div>
        </div>
        <!-- end panel-heading -->

        <!-- begin panel-body -->
        <div class="panel-body" width="100%" style="font-size:10px">
            @livewire('accounts-receivable.c-x-c-client-table',['client' => $client])
        </div>
        <!-- end panel-body -->
    </div>
    <!-- end panel -->
</div>

</div>
<script>
    window.addEventListener('pays_modal_hide', event => {
        $('#paysModal').modal('hide');
    });
</script>
@else
<div>
    <x-banner-buton></x-banner-buton>
    <!-- begin row -->
    <div class="row">
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats bg-green-600" style="background-color: #277C4C;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>Al Día</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango0,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #527C27;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>0 a 15 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango0a15,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #7B7C27;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>15 a 30 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango15a30,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C28122;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>30 a 60 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango30a60,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C24B22;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>60 a 90 días de atraso</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rango60a90,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
        <!-- begin col-3 -->
        <div class="col-md-2">
            <div class="widget widget-stats" style="background-color: #C22222;">
                <div class="stats-icon stats-icon-lg"><i class="">₡</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h5>Más de 90 días</h5>
                    </div>
                    <div class="stats-number">₡ {{ number_format($rangoMas90,0,'.',',') }}</div>
                </div>
            </div>
        </div>
        <!-- end col-3 -->
    </div>
    <!-- end row -->
    <div class="col-md-12">
        <div class="widget widget-stats" style="background-color: #192743;">
            <div class="stats-icon stats-icon-lg"><i class=""></i></div>
            <div class="stats-content text-center">
                <div class="stats-number">MONTO TOTAL DE CUENTAS POR COBRAR: ₡ {{ number_format($total,2,'.',',') }}</div>
            </div>
        </div>
    </div>
    <div>
        <!-- begin panel -->
        <div class="panel panel-inverse" data-sortable-id="form-plugins-1" width="100%">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h2 class="panel-title"> Detalle de cuantas por cobrar </h2>

            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body" width="100%" style="font-size:10px">
                <livewire:accounts-receivable.c-x-c-table />
            </div>
            <!-- end panel-body -->
        </div>
        <!-- end panel -->
    </div>
</div>
@endif