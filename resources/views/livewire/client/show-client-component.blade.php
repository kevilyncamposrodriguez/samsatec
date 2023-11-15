<div>
    <x-banner-buton></x-banner-buton>
    <!-- begin page-header -->

    <div class="widget widget-stats row" style="background-color: #192743;">
        <div class="col-md-6">
            <h2 class="page-header">{{ $client->name_client }}</h2>
            <h6 class="">{{ $client->id_card }} | {{ $client->other_signs }}, {{ $client->district }}, {{ $client->canton }}, {{ $client->province }}
            </h6>
            <h5>
                <a href="#" class="f-s-4">
                    <i class="fa fa-phone text-white">  {{ $client->phone }}  |   </i>
                </a>
                <a href="#" data-toggle="dropdown" class="dropdown-toggle f-s-4">
                    <i class="fa fa-envelope text-white">  {{ $client->emails }}</i>
                </a>
            </h5>
        </div>
        <div class="row col-md-6">
            <div class="col-md-5 widget widget-stats" style="background-color: darkgoldenrod; margin-right: 10px;">
                <div class="stats-icon stats-icon-md"><i>{{$pending}}</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4> Pendientes <h4>
                    </div>
                </div>
            </div>
            <div class="col-md-5 widget widget-stats bg-red">
                <div class="stats-icon stats-icon-md"><i>{{$expired}}</i></div>
                <div class="stats-content">
                    <div class="stats-title text-white">
                        <h4>Vencidas</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    @endpush

    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Documentos Emitidos</h4>
                <div class="panel-heading-btn">
                </div>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <livewire:client.client-documents-table params="{{$id_client}}" />
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>
@livewire('document.edit-document-component')
@livewire('payment-invoice.pay-invoices-component')
@livewire('document.send-document-component')