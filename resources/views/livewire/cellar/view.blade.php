<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal modal-message fade" style="width: 100%; margin-left: -10px;" id="documentVModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #E1E6E4;">
                <div class="modal-header text-center" style="width:80%;">
                    <h4 class="modal-title text-center"><strong class="text-center">
                            <font size=5>{{$type_document}}</font>
                        </strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body" style="width:80%;">
                    <form class="form-horizontal form-bordered">
                        @csrf
                        <!-- begin invoice -->
                        <div class="invoice">
                             <!-- begin invoice-header -->
                             <div class="invoice-header m-l-10  m-r-10 p-t-10 p-b-10 row">
                                <br>
                                <div class="col-md-3">
                                    <strong>Preparar para:</strong>
                                    <label>{{ $client }}</label>
                                </div>
                                <div class="col-md-3">
                                    <strong>Fecha de creación:</strong>
                                    <label>{{ $date_issue }}</label>
                                </div>
                                <div class="col-md-3">
                                    <strong>Fecha de entraga:</strong>
                                    <label>{{ $delivery_date }}</label>
                                </div>
                                <div class="col-md-3">
                                    <strong>Prioridad:</strong>
                                    <label>{{ ($priority == 1)?'Baja':(($priority == 2)?'Media':'Alta')}} </label>
                                </div>

                            </div>
                            <!-- begin invoice-content -->
                            <!-- begin invoice-content -->
                            <div class="invoice-content">
                                <!-- begin table-responsive -->
                                <div class="table-responsive bg-gray-400">
                                    <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                        <thead>
                                            <tr class="text-white" style="background-color:#192743;">
                                            <th class="text-center" width="1%">N°</th>
                                                <th class="text-center" width="10%">Codigo Interno</th>
                                                <th class="text-center" width="29%">DESCRIPCION</th>
                                                <th class="text-center" width="5%">UNIDAD</th>
                                                <th class="text-center" width="5%">CANTIDAD SOLICITADA</th>
                                                <th class="text-center" width="15%">CANTIDAD DESPACHADA</th>
                                                <th class="text-center" width="35%">NOTAS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allLines as $index => $line)
                                            <tr class="gradeU">
                                                <td width="1%" class="text-center">{{ $index+1 }}</td>
                                                <td width="10%" class="text-center">{{ $line['code'] }}</td>
                                                <td width="29%" class="text-center">{{ $line['detail'] }}</td>
                                                <td width="5%" class="text-center">{{ $line['qty'] }}</td>
                                                <td width="5%" class="text-center">{{ $line['qty_dispatch'] }}</td>
                                                <td width="15%" class="text-center">{{ $line['qty_dispatch'] }}</td>
                                                <td width="35%" class="text-center">{{ $line['note'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->
                            </div>
                            <!-- end invoice-header -->

                        </div>
                        <!-- end invoice -->

                    </form>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                    <button type="button" wire:click.prevent="re_processed()" onclick="this.disabled=true;" class="btn text-white" style="background-color:#010e2c;">Reprocesar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('cellarP_modal_hide', event => {
        $('#documentVModal').modal('hide');
    });
</script>