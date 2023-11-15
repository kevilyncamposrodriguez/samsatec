<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade " id="AIModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajuste de Inventario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_newAI" class="form-horizontal form-bordered">
                        @csrf
                        <div class="form-group row bg-grey">
                            <div class="col-lg-3">
                                <strong> Consecutivo:</strong>
                                <input disabled type="text" name="consecutive" class="form-control" wire:model="consecutive" />
                            </div>
                            <div class="col-lg-3">
                                <strong> Sucursal:</strong>
                                <select wire:change="updateBO()" {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" name="id_bo" id="id_bo" wire:model="id_bo" {{(isset($this->tu->bo) && $this->tu->bo)?"disabled":''}}>
                                    @foreach($allBranchOffices as $bo)
                                    <option style="color: black;" value="{{ $bo->id }}" label="{{ $bo->name_branch_office }}">
                                        @endforeach
                                </select>
                                @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-3">
                                <strong> Inventario:</strong>
                                <select wire:change="updateInventory()" {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" {{(Auth::user()->currentTeam->bo_inventory)?'':'disabled'}} name="inventary" id="inventary" wire:model="inventary">
                                    @foreach($allInventaries as $inventary)
                                    <option style="color: black;" value="{{ $inventary->id }}" label="{{ $inventary->name }}">
                                        @endforeach
                                </select>
                                @error('inventary') <span class="text-red-500">{{ $message }}</span>@enderror

                            </div>

                            <div class="col-lg-3">
                                <strong> Terminal:</strong>
                                <select {{ (Auth::user()->hasTeamRole(Auth::user()->currentTeam, 'admin'))?'':'disabled' }} class="form-control" {{(isset($this->tu->terminal) && $this->tu->terminal)?"disabled":''}} name="id_terminal" id="id_terminal" wire:model="id_terminal">
                                    @foreach($allTerminals as $terminal)
                                    <option style="color: black;" value="{{ $terminal->id }}" label="{{ $terminal->number }}">
                                        @endforeach
                                </select>
                                @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror

                            </div>

                        </div>
                        <!-- begin form-group -->
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <strong> Producto:</strong>
                                <input class="form-control" type="text" name="name_product" wire:change="changeProduct()" list="productsA" placeholder="Ninguno" wire:model="name_product">
                                <datalist id="productsA">
                                    @foreach($allProducts as $product)
                                    <option style="color: black;" value="{{ $product->description }}" label="{{ $product->internal_code }}">
                                        @endforeach
                                </datalist>
                                <input type="text" name="id_product" wire:model="id_product" hidden>
                                @error('id_product') <span class="text-red-500">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-lg-3">
                                <strong> Cantidad Actual:</strong>
                                <input type="number" disabled name="qty_start" class="form-control" wire:model="qty_start" />
                            </div>
                            <div class="col-lg-3">
                                <strong> Cantidad Variante:</strong>
                                <input type="number" name="qty" class="form-control" wire:change="updateQty()" wire:model="qty" />
                            </div>

                        </div>
                        <!-- begin form-group -->
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <strong> Costo:</strong>
                                <input type="number" name="cost" class="form-control" wire:model="cost" />
                            </div>
                            <div class="col-lg-3">
                                <strong> Cantidad Final:</strong>
                                <input type="text" disabled name="qty_end" class="form-control" wire:model="qty_end" />
                            </div>
                            <div class="col-lg-3">
                                <strong> Ajuste:</strong>
                                <input type="number" disabled name="total_line" class="form-control" wire:model="total_line" />
                            </div>
                            <div class="col-lg-3">
                                <strong> Agregar Linea:</strong>
                                <button type="button" wire:click.prevent="addLine()" onclick="this.disabled=true;" class="form-control btn btn-primary"><strong>+</strong></button>
                            </div>
                        </div>
                        <!-- begin form-group -->
                    </form>
                    <!-- begin invoice-content -->
                    <div class="invoice-content">
                        @error('allLines') <span class="text-red-500">Debes agregar al menos una linea</span>@enderror
                        <!-- begin table-responsive -->
                        <div class="table-responsive bg-gray-400">
                            <table class="table table-invoice" id="DetalleServicio" name="DetalleServicio">
                                <thead>
                                    <tr class="text-white" style="background-color:#192743;">
                                        <th class="text-center" width="1%"></th>
                                        <th class="text-center" width="1%">N°</th>
                                        <th class="text-center" width="48%">PRODUCTO</th>
                                        <th class="text-center" width="10%">CANT. ACTUAL</th>
                                        <th class="text-center" width="10%">VARIANTE</th>
                                        <th class="text-center" width="10%">CANT FINAL</th>
                                        <th class="text-center" width="10%">COSTO</th>
                                        <th class="text-center" width="10%">AJUSTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLines as $index => $line)
                                    <tr class="gradeU">
                                        <td width="1%" class="text-center btn-group btn-group-justified">
                                            <button wire:click.prevent="deleteLine({{ $index }})" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                            <button wire:click.prevent="editLine({{ $index }})" class="btn btn-primary btn-xs"><i class="fa fa-pen"></i></button>
                                        </td>
                                        <td width="1%" class="text-center">{{ $index+1 }}</td>
                                        <td width="48%" class="text-center">{{ $line['name_product'] }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qty_start'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qty'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['qty_end'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['cost'],2,'.',',') }}</td>
                                        <td width="10%" class="text-center">{{ number_format($line['total_line'],2,'.',',') }}</td>
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
                                <div class="invoice-price-right col-4" style="background-color:#192743;">
                                    <h3 class="text-white">TOTAL</h3>
                                    <h3 class="f-w-600 text-white text-right"> {{ number_format($total,2,'.',',') }}</h3>
                                </div>
                            </div>
                        </div>
                        <!-- end invoice-price -->
                    </div>
                    <!-- end invoice-content -->
                    <div class="col-lg-12">
                        <strong> Observaciones:</strong>
                        <textarea class="form-control" id="observation" name="observation" rows="3" class="col-lg-12" wire:model="observation"></textarea>
                        @error('observation') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                    <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary">Ajustar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('AI_modal_hide', event => {
        $('#AIModal').modal('hide');
    });
    document.addEventListener('DOMContentLoaded', function() {
        $("#AIModal").on("hidden.bs.modal", function() {
            Livewire.emit('cleanInputs')
        });

    });
</script>