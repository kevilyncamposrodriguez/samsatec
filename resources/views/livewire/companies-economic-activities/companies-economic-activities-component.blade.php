<div>
    @if(Auth::user()->isMemberOfATeam())
    
    <!-- end page-header -->
    @endif
    @include('livewire.companies-economic-activities.create')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Actividades Economicas</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nueva actividad economica" data-toggle="modal" data-target="#eaModal"><i class="fa fa-plus"> Nueva Actividad economica</i></button>
                </div>
            </div>

            <div class="panel-body">
                <table id="data-table-sucursa" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>

                            <th data-orderable="false">Acciones</th>
                            <th width="1%">Numero</th>
                            <th class="text-nowrap">Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allAECompany as $ae)
                        <tr class="gradeU">
                            <td class="text-center" width="15%">
                                <div class="btn-group btn-group-justified">
                                    <button wire:click="delete({{ $ae->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                            <td class="text-center" width="15%">{{ $ae->number }}</td>
                            <td class="text-center">{{ $ae->name_ea}}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>