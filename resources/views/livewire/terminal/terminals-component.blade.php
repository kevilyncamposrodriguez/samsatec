<div>
    @if(Auth::user()->isMemberOfATeam())
   
    <!-- end page-header -->
    @endif
    @include('livewire.terminal.create')
    @include('livewire.terminal.consecutives')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Terminales</h4>
                <div class="panel-heading-btn">
                    <button class="btn btn-md  btn-blue" title="Nueva Terminal" data-toggle="modal" data-target="#terminalModal"><i class="fa fa-plus"> Nueva Terminal</i></button>
                </div>
            </div>

            <div class="panel-body">
                <table id="data-table-termina" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>

                            <th data-orderable="false">Acciones</th>
                            <th width="1%">Número</th>
                            <th class="text-nowrap">Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTerminals as $terminal)
                        <tr class="gradeU">

                            <td width="15%" class="text-center">
                                <div class="btn-group btn-group-justified">
                                    <button type="button" title="Cambio de consecutivos" data-toggle="modal" data-target="#consecutivesModal" class="btn" style="background-color: #192743;" wire:click="editConsecutives({{ $terminal->id }})"><i class="text-white">Consecutivos</i></button>
                                    <button wire:click="delete({{ $terminal->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </div>
                            </td>
                            <td width="15%" class="text-center">{{ str_pad($terminal->number, 5, "0", STR_PAD_LEFT) }}</td>
                            <td class="text-center">{{ $terminal->name_branch_office }}</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>