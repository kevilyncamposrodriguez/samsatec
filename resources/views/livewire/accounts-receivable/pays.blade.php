<!-- Modal -->
<div wire:ignore.self class="modal fade " id="paysModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Pago de facturas seleccionadas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            
            <div class="modal-body">
                <table id="" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr style="background-color: #192743;" class="text-white text-center">
                            <th class="text-nowrap">
                                <h4>Factura</h4>
                            </th>
                            <th class="text-nowrap">
                                <h4>Monto</h4>
                            </th>
                            <th class="text-nowrap">
                                <h4>Abono</h4>
                            </th>
                            <th class="text-nowrap">
                                <h4>Saldo Actual</h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->facts as $index => $fact)
                        <tr class="gradeU">
                            <td width="25%" class="text-center">{{ (isset($fact->consecutive))?$fact->consecutive:0 }}</td>
                            <td width="25%" class="text-center">{{ (isset($fact->balance))?number_format($fact->balance,2,'.',','):number_format(0,2,'.',',') }}</td>
                            <td width="25%" class="text-center"><input class="form-control" type="number" value="$fact->balance" wire:model="pay.{{ $index }}"></td>
                            <td width="25%" class="text-center">{{ number_format($fact->balance - ((isset($pay[$index]) && is_numeric($pay[$index]))?$pay[$index]:0),2,'.',',') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <table id="" class="table table-striped table-bordered table-td-valign-middle">
                    <thead>
                        <tr style="background-color: #192743;" class="text-white">
                            <th class="text-nowrap" colspan="2">
                                <h4>Monto a cancelar: ₡ {{ number_format($totalPay,2,'.',',') }}</h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="gradeU">
                            <td width="25%" class=""><b class="uppercase">Acreditar a la cuenta:</b></td>
                            <td width="25%" class="text-center">
                                <select class="form-control" name="id_count" id="id_count" wire:model="id_count">
                                    <option style="color: black;" value="">Ninguna</option>
                                    @foreach ($this->allAcounts as $index => $acount)
                                    @if($acount != null)
                                    <option style="color: black;" value="{{ $acount['id'] }}">{{ $acount['name'] }}</option>
                                    @endif
                                    @endforeach
                                </select> @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                            </td>
                        </tr>
                        <tr class="gradeU">
                            <td width="25%" class=""><b class="uppercase">Número de comprobante</b></td>
                            <td width="25%" class="text-center"><input class="form-control" type="text" name="reference" wire:model="reference">
                                @error('reference') <span class="text-red-500">{{ $message }}</span>@enderror
                            </td>

                        </tr>
                        <tr class="gradeU">
                            <td width="25%" class=""><b class="uppercase">Fecha de pago</b></td>
                            <td width="25%" class="text-center"><input class="form-control" type="date" name="date" wire:model="date">
                                @error('date') <span class="text-red-500">{{ $message }}</span>@enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="storePays()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Realizar Pagos</button>
            </div>
        </div>
    </div>
</div>