<!-- Modal -->
<div wire:ignore.self class="modal fade" id="OCRModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apertura de Caja {{$prueba}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Terminal o Caja:{{ $t_select }} <span class="text-danger"></span></label>
                        <div class="col-lg-6">
                            <select class="form-control" name="id_terminal" {{ $select = ($t_select)?'':'disabled' }} id="id_terminal" wire:model="id_terminal">
                                @foreach($allTerminals as $terminal)
                                <option style="color: black;" value="{{ $terminal->id }}">{{ $terminal->number }}</option>
                                @endforeach
                            </select>
                            @error('id_terminal') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-6 col-form-label">Saldo Inicial: <span class="text-danger"></span></label>
                        <div class="col-lg-6">
                            <input class="form-control" type="number" name="start_balance" wire:model="start_balance" />
                            @error('start_balance') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <!-- end form-group -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="button" wire:click.prevent="store()" onclick="this.disabled=true;" class="btn btn-primary close-modal">Abrir</button>
            </div>
        </div>
    </div>
</div>


<script>
    window.addEventListener('ocr_modal_hide', event => {
        $('#OCRModal').modal('hide');
    });
</script>