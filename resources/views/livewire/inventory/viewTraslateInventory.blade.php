<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="viewTransferIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{($type == 'Traslado')?'Traslado ': 'Transformación '}} de Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    @csrf
                    <div class="form-group row bg-grey">
                        <div class="col-lg-6">
                            <strong> {{($type == 'Traslado')?'Traslado ': 'Transformación '}} de Inventario #{{ $consecutive }}</strong>
                        </div>
                        <div class="col-lg-6" style="text-align: right;">
                            <strong> Fecha: {{ $date }}</strong>
                        </div>
                    </div>
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        @error('allLinesT') <span class="text-red-500">Debes agregar al menos una linea</span>@enderror
                        <!-- begin table-responsive -->
                        <div class="table-responsive bg-gray-400">
                            <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                <thead>
                                    <tr class="text-white" style="background-color:#192743;">
                                        <th class="text-center" width="1%">N°</th>
                                        <th class="text-center" width="48%">PROD. SALIDA</th>
                                        <th class="text-center" width="10%">CANT. ACTUAL</th>
                                        <th class="text-center" width="10%">CANT FINAL</th>
                                        <th class="text-center" width="48%">PROD. ENTRADA</th>
                                        <th class="text-center" width="10%">CANT. ACTUAL</th>
                                        <th class="text-center" width="10%">CANT FINAL</th>
                                        @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                                        <th class="text-center" width="10%">COSTO</th>
                                        <th class="text-center" width="10%">TOTAL {{($this->type == 'Traslado')?'TRASLADO': 'TRANSFORMADO'}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLinesT as $index => $line)
                                    <tr class="gradeU">
                                        <td width="1%" class="text-center">{{ $index+1 }}</td>
                                        <td width="48%" class="text-center">{{ $line['name_productS'] }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyAS'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyNS'],2,'.',',') }}</td>
                                        <td width="48%" class="text-center">{{ $line['name_productE'] }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyAE'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qtyNE'],2,'.',',') }}</td>
                                        @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                                        <td width="10%" class="text-center">{{ number_format($line['cost'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['total_line'],2,'.',',') }}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- begin invoice-price -->
                        <div class="col-lg-12">
                            <div class="invoice-price row">
                                <div class="invoice-price-left col-8">
                                    <div class="invoice-price-row row">

                                    </div>
                                </div>
                                @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                                <div class="invoice-price-right col-4" style="background-color:#192743;">
                                    <h3 class="text-white">TOTAL</h3>
                                    <h3 class="f-w-600 text-white text-right"> {{ number_format($total,2,'.',',') }}</h3>
                                </div>
                                @endif
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <div class="col-lg-12">
                        <strong> Observaciones:</strong>
                        <textarea disabled class="form-control" name="observation" rows="3" class="col-lg-12" wire:model="observation"></textarea>
                        @error('observation') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cerrar</button>
                    @if (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))
                    <a href="{{ 'files/traslados/' . Auth::user()->currentTeam->id_card.'/' . $this->consecutive . '.pdf' }}" target="_blank" class="btn btn-primary close-modal">Exportar PDF</a>
                    @endif
                    @if($type == 'Traslado')
                    <a href="{{ 'files/traslados/' . Auth::user()->currentTeam->id_card.'/I' . $this->consecutive . '.pdf' }}" target="_blank" class="btn btn-primary close-modal">Informe Envío</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('transferI_modal_hide', event => {
        $('#transferIModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $("#viewTransferIModal").on("hidden.bs.modal", function() {
            Livewire.emit('cleanInputsT')
        });

    });
</script>