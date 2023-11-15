<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.seller.create')
    @include('livewire.seller.update')
    <!-- begin panel -->
    <div class="panel panel-inverse" data-sortable-id="form-plugins-1">
        <div class="panel panel-inverse">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title">Listado de Vendedores</h4>
                <div class="panel-heading-btn">
                    <div class="btn-group m-r-5 m-b-1">
                        <a title="Nuevo Vendedor" data-toggle="modal" data-target="#sellerModal" wire:click.prevent="cancel()" class="btn btn-blue">Nuevo Vendedor</a>
                        <!--<a href="#" data-toggle="dropdown" class="btn btn-blue dropdown-toggle"><b class="caret"></b></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" title="Importar Productos" wire:click.prevent="cancel()" data-toggle="modal" data-target="#sellerImportModal">Importar</a>
                        </div> -->
                    </div>
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
                <table id="data-table-product" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr>
                            <th data-orderable="false">N°</th>
                            <th data-orderable="false">Acciones</th>
                            <th class="text-nowrap">Tipo de ID</th>
                            <th class="text-nowrap">Nombre</th>
                            <th class="text-nowrap">Telefono</th>
                            <th class="text-nowrap">Correo</th>
                            <th class="text-nowrap">Comision</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allSellers as $index => $seller)
                        <tr class="gradeU">
                            <td width="1%" class="text-center">{{ $index+1 }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-justified">
                                    @if($seller->active)
                                    <button data-toggle="modal" data-target="#sellerUModal" data-toggle="modal" wire:click="edit({{ $seller->id }})" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                                    <button wire:click="delete({{ $seller->id }})" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    @else
                                    <button wire:click="enable({{ $seller->id }})" class="btn btn-danger btn-sm"><i class="">Habilitar</i></button>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">{{ $seller->id_card }}</td>
                            <td class="text-center">{{ $seller->name }}</td>
                            <td class="text-center">{{ $seller->phone }}</td>
                            <td class="text-center">{{ $seller->emails }}</td>
                            <td class="text-center">{{ $seller->commission }}%</td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- end panel-body -->
        </div>
    </div>

</div>