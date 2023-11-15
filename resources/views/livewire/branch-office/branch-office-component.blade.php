<div>
    @if(Auth::user()->isMemberOfATeam())
    <!-- end page-header -->
    @endif
    @include('livewire.branch-office.create')
    @include('livewire.branch-office.update')

    <x-slot name="title">
        {{ __('Datos de compañía') }}
    </x-slot>
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h4 class="panel-title">Listado de Sucursales</h4>
            <div class="panel-heading-btn">
                <button class="btn btn-md  btn-blue" title="Nueva sucursal" data-toggle="modal" data-target="#boModal"><i class="fa fa-plus"> Nueva Sucursal</i></button>
            </div>
        </div>

        <div class="panel-body">
            <table id="data-table-sucursa" class="table table-striped table-bordered table-td-valign-middle">
                <thead>
                    <tr>

                        <th data-orderable="false">Acciones</th>
                        <th width="1%">Numero</th>
                        <th class="text-nowrap">Razon Social</th>
                        <th class="text-nowrap">Provincia</th>
                        <th class="text-nowrap">Cantón</th>
                        <th class="text-nowrap">Distrito</th>
                        <th class="text-nowrap">Telefono</th>
                        <th class="text-nowrap">Correo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allBO as $branch_office)
                    <tr class="gradeU">

                        <td>
                            <div class="btn-group btn-group-justified">
                                <button data-toggle="modal" data-target="#boUModal" wire:click="edit({{ $branch_office->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                <button wire:click="delete({{ $branch_office->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                            </div>
                        </td>
                        <td width="1%" class="text-center">{{ str_pad($branch_office->number, 3, "0", STR_PAD_LEFT) }}</td>
                        <td class="text-center">{{ $branch_office->name_branch_office }}</td>
                        <td class="text-center">{{ $branch_office->nameProvince}}</td>
                        <td class="text-center">{{ $branch_office->nameCanton}}</td>
                        <td class="text-center">{{ $branch_office->nameDistrict }}</td>
                        <td class="text-center">{{ $branch_office->phone }}</td>
                        <td class="text-center">{{ $branch_office->emails }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <!-- end panel-body -->
    </div>
</div>