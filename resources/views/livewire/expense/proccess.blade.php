<!-- Modal -->
<div wire:ignore.self class="modal fade" id="proccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Clasificación de comprobantes </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="import_v" class="form-horizontal form-bordered">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Clave: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input wire:model="key" disabled type="text" name="key" class="form-control" />
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Sucursal: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" name="id_branch_office" id="id_branch_office" wire:model="id_branch_office">
                                @foreach($allBO as $bo)
                                <option active style="color: black;" value="{{ $bo->id }}">{{ $bo->name_branch_office }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Actividad Economica: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" name="number_ea2" id="number_ea2" wire:model="number_ea">
                                @foreach($all_eas as $ea)
                                <option active style="color: black;" value="{{ $ea->number }}">{{ $ea->name_ea }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('number_ea') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Categoria MH: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" data-parsley-required="true" name="id_mh_category" id="id_mh_category" wire:model="id_mh_category">
                                @foreach($allMHCategories as $mhCategory)
                                <option style="color: black;" value="{{ $mhCategory->id }}">{{ $mhCategory->name }}</option>
                                @endforeach
                            </select> @error('id_mh_category') <span class="text-red-500">{{ $message }}</span>@enderror <br>
                        </div>
                        @error('id_branch_office') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-black" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="import_v" onclick="this.disabled=true;" class="btn btn-blue" wire:click.prevent="saveVoucher()">Procesar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function count_change() {
        window.livewire.emit('changeCount', document.getElementById("id_count").value);
    }
</script>