
<!-- Modal -->
<div wire:ignore.self class="modal fade " id="taxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de impuesto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <div class="col-xs-9">
                            <input class="form-control" type="text" name="description" wire:model="description" placeholder="Descripcion o nombre de impuesto"/>
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror                        
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10 ">
                        <label class="col-lg-3 col-form-label">Código Impuesto: <span class="text-danger"></span></label>
                        <div class="col-xs-9">
                            <select wire:change="changeTaxCode" class="form-control" data-parsley-required="true" name="id_taxes_code" id="id_taxes_code" wire:model="id_taxes_code">
                                @foreach($allTaxesCodes as $taxCode)                                       
                                <option style="color: black;"  value="{{ $taxCode->id }}">{{ $taxCode->description }}</option>
                                @endforeach
                            </select>
                            @error('id_taxes_code') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>                        
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Código Tarifa: <span class="text-danger"></span></label>
                        <div class="col-xs-4">
                            <select  wire:change="changeRateCode" {{ $disable = ( $id_taxes_code == '7' || $id_taxes_code == '1')?'':'disabled' }} class="form-control" data-parsley-required="true" name="id_rate_code" id="id_rate_code" wire:model="id_rate_code">
                                @foreach($allRatesCodes as $rateCode)                                       
                                <option style="color: black;"  value="{{ $rateCode->id }}">{{ $rateCode->description }}</option>
                                @endforeach
                            </select>
                            @error('id_rate_code') <span class="text-red-500">{{ $message }}</span>@enderror                            
                        </div>    
                        <label class="col-lg-2 col-form-label">Tarifa: <span class="text-danger"></span></label>
                        <div class="col-3">
                            <input {{ $d = ( $id_taxes_code == 1 || $id_taxes_code == 7 || $id_taxes_code == 8)?'disabled=""':'' }} class="form-control" type="number"  name="rate"  wire:model="rate" />
                            @error('rate') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->                   
                     <!-- begin form-group -->
                    <div class="form-group row m-b-10 ">
                        <label class="col-lg-3 col-form-label">Exoneración: <span class="text-danger"></span></label>
                        <div class="col-xs-9">
                            <select class="form-control" data-parsley-required="true" name="id_exoneration" id="id_exoneration" wire:model="id_exoneration">
                                <option style="color: black;" value="0">Sin exoneración</option>
                                @foreach($allExonerations as $exoneration)                                       
                                <option style="color: black;" value="{{ $exoneration->id }}">{{ $exoneration->description }}</option>
                                @endforeach
                            </select>
                            @error('id_exoneration') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>                        
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Monto exoneración: <span class="text-danger"></span></label>
                        <div class="col-3">
                            <input class="form-control" disabled="" type="number"  name="exoneration_amount"  wire:model="exoneration_amount" />
                            @error('exoneration_amount') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>  
                        <label class="col-lg-3 col-form-label">Impuesto neto: <span class="text-danger"></span></label>
                        <div class="col-3">
                            <input class="form-control" disabled="" type="number"  name="tax_net"  wire:model="tax_net" />
                            @error('tax_net') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Guardar</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('tax_modal_hide', event => {
        $('#taxModal').modal('hide');
    });
</script>

