<div>
    <x-banner-buton></x-banner-buton>
    @if(Auth::user()->isMemberOfATeam())
    <!-- begin page-header -->
    <h1 class="page-header mb-3">{{ Auth::user()->currentTeam->name }}</h1>
    <!-- end page-header -->
    @endif
    @include('livewire.expense.proccess')
    @include('livewire.expense.import')
    @include('livewire.expense.view')
    @include('livewire.expense.accept')

    <!-- begin panel -->
    <div class="panel panel-inverse">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <h1 class="panel-title font-medium uppercase">Documentos sin procesar</h1>
            <a class="btn btn-blue m-r-5 uppercase" title="Importar Comprobante: para la importación es necesario los tres archivos correspondientes, PDF, XML de factua y XML de Respuesta. " data-toggle="modal" data-target="#importModal">Importar</a>
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
            <table id="data-table-document" class="table table-striped table-bordered table-td-valign-middle">
                <thead>
                    <tr>
                        <th width="7%">ID</th>
                        <th width="19%" data-orderable="false">Acciones</th>
                        <th width="5%" class="text-nowrap">Fecha</th>
                        <th width="10%" class="text-nowrap">Cedula</th>
                        <th width="24%" class="text-nowrap">Nombre Emisor</th>
                        <th width="1%" class="text-nowrap">Moneda</th>
                        <th width="10%" class="text-nowrap">Total</th>
                        <th width="24%" class="text-nowrap">Consecutivo</th>
                        <th width="24%" class="text-nowrap">Clave</th>
                    </tr>
                </thead>
                <tbody> @php $cont = 1 @endphp
                    @foreach($allVouchers as $v)
                    <tr class="gradeU">
                        <td width="1%">{{ $cont++ }}</td>
                        <td width="10%" class="text-center">
                            <div class="btn-group btn-group-justified">
                                @if(!file_exists( $v["ruta"].$v["clave"].'/'.$v["clave"].'.pdf' ))
                                <a data-toggle="modal" data-target="#viewExpenseModal" wire:click="viewVoucher('{{ $v['clave'] }}')" title="Mostrar comprobante: si existe el PDF muestra la informción de este, al no ser asi genera una vista segun los datos del XML principal." data-toggle="modal" class="btn btn-primary btn-ms"><i class="fa fa-search"></i></a>
                                @else
                                <a title="Mostrar Comprobante: si existe el PDF muestra la informción de este, al no ser asi genera una vista segun los datos del XML principal." href="{{ $v['ruta'].$v['clave'].'/'.$v['clave'].'.pdf' }}" target="_blank" class="btn btn-primary btn-ms"><i title="Vista del Documento" class="fa fa-search"></i></a>
                                @endif
                                <a title="Solo guardar: si solo se guarda el documento, el Ministerio de Hacienda lo dará por aceptado despues de 15 dias de su emisión" class="btn btn-ms text-white" style="background-color: #192743;" data-target="#acceptExpenseModal" wire:click="viewVoucher('{{ $v['clave'] }}','0')" data-toggle="modal"><i class="fa fa-save"></i></a>
                                <a title="Aprobar comprobante: hace constar que todos los montos e información en la factura son correctos." data-toggle="modal" data-target="#acceptExpenseModal" wire:click="viewVoucher('{{ $v['clave'] }}','1')" data-toggle="modal" class="btn btn-blue btn-ms"><i class="fa fa-check text-white"></i></a>
                                <a title="Rechazar comprobante: el comprobante posee montos o datos incorrectos por lo que debe de solicitar al emisor la nota de crédito correspondiente." class="btn btn-ms text-white" style="background-color: #192743;" wire:click="proccessModal('{{ $v['clave'] }}','rechazado')" data-toggle="modal" data-target="#proccessModal"><i class=" fa fa-times"></i></a>
                                <a title="Eliminar comprobante: Si ya el comprobante ha sido procesado o no es necesario procesarlo, puede proceder a su eliminación." class="btn btn-primary btn-ms" wire:click="delete('{{ $v['clave'] }}')"><i class="fa fa-trash btn-ms text-white"></i></a>
                            </div>
                        </td>
                        <td width="10%" class="text-center">{{ substr($v["fecha"], 0, 10) }}</td>
                        <td width="7%" class="text-center">{{ $idcar = (isset($v["emisor"]->Identificacion->Numero))?$v["emisor"]->Identificacion->Numero:"" }}</td>
                        <td width="24%" class="text-center">{{ $emisor=(isset($v['emisor']->Nombre))?$v['emisor']->Nombre:"" }}</td>
                        <td width="7%" class="text-center">{{ $v["moneda"] }}</td>
                        <td width="10%" class="text-center">{{ $v["total"] }}</td>
                        <td width="24%" class="text-center">{{ $v["consecutivo"] }}</td>
                        <td width="24%" class="text-center">{{ $v["clave"] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- end panel-body -->
    </div>
</div>