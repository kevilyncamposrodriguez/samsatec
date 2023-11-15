<!-- Modal -->
<div wire:ignore.self class="modal fade" id="countUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Actualización de cuenta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Tipo de cuenta: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" name="id_type_count" id="id_type_count" wire:click='changeCountType' wire:model="id_type_count">
                                @foreach($allCountTypes as $count)
                                <option style="color: black;" value="{{ $count->id }}">{{ $count->name }}</option>
                                @endforeach
                            </select>
                            @error('id_type_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Categoría de cuenta: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <select class="form-control" name="id_category_count" id="id_category_count" wire:click='changeCountCategory' wire:model="id_category_count">
                                @foreach($allCountCategories as $countCategory)
                                <option style="color: black;" value="{{ $countCategory->id }}">{{ $countCategory->name }}</option>
                                @endforeach
                            </select>
                            @error('id_category_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">

                        <label class="col-lg-3 col-form-label">Cuenta secundaria de: <span class="text-danger"></span></label>

                        <div class="col-lg-9 col-xl-9">
                            <!-- begin custom-checkbox -->
                            <div class="custom-control custom-checkbox">
                                <input {{ $disable = ($id_type_count != 0)?'':'disabled' }} type="checkbox" class="custom-control-input" id="customCheck1" name="secundary" wire:model="secundary">
                                <label class="custom-control-label" for="customCheck1">Es una cuenta secundaria?</label>
                            </div>
                            <br>
                            <!-- end custom-checkbox -->
                            <select class="form-control" {{ $disable = ($secundary)?'':'disabled' }} name="id_count" id="id_count" wire:model="id_count">
                                <option style="color: black;" value="0">Seleccionar...</option>
                                @foreach($allCountsS as $count)
                                <option style="color: black;" value="{{ $count['id'] }}">{{ $count['name'] }}</option>
                                @endforeach
                            </select>
                            @error('id_count') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Nombre de cuenta: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="text" name="name" wire:model="name" />
                            @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="text" name="description" wire:model="description" />
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Saldo Inicial: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input class="form-control" type="number" name="initial_balance" wire:model="initial_balance" value="0"/>
                            @error('initial_balance') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="update()" onclick="this.disabled=true;" class="btn btn-primary" data-dismiss="modal">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('countU_modal_hide', event => {
        $('#countUModal').modal('hide');
    });
</script>