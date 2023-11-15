<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.tax.create')
    @include('livewire.tax.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Impuestos</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nuevo Impuesto" data-toggle="modal" data-target="#taxModal"><i class="fa fa-plus"> Nuevo impuesto</i></button>
                </div>
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
                <table id="data-table-tax" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>

                            <th data-orderable="false" width="8%">Acciones</th>
                            <th class="text-nowrap" width="9%">Descripción</th>
                            <th class="text-nowrap" width="7%">Código Impuesto</th>
                            <th class="text-nowrap" width="7%">Código Tarifa</th>
                            <th class="text-nowrap" width="5%">Tarifa</th>
                            <th class="text-nowrap" width="7%">Monto Exoneración</th>
                            <th class="text-nowrap" width="7%">% impuesto neto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTaxes as $tax)
                        <tr class="gradeU">

                            <td class="text-center">
                                <div class="btn-group btn-group-justified">
                                    <button data-toggle="modal" data-target="#taxUModal" data-toggle="modal" wire:click="edit({{ $tax->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                    <button wire:click="delete({{ $tax->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                            <td class="text-center">{{ $tax->description }}</td>
                            <td class="text-center">{{ $tax->taxCode }}</td>
                            <td class="text-center">{{ $tax->rateCode }}</td>
                            <td class="text-center">{{ $tax->rate }}</td>
                            <td class="text-center">{{ $tax->exoneration_amount }}</td>
                            <td class="text-center">{{ $tax->tax_net }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>