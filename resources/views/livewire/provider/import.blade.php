
<!-- Modal -->
<div wire:ignore.self class="modal fade " id="providerImportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importar proveedores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="import_provider_form" wire:submit.prevent="importProviders" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Archivo: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input type="file" name="file_import" wire:model="file_import" class="form-control" />
                        </div>
                        @error('file_import') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <a href="{{ 'machotes/Importacion Proveedores.xlsx' }}" download="Importacion Proveedores.xlsx" style="width: 100%;" class="btn btn-dark">Descargar ejemplo</a>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="import_provider_form"  class="btn btn-primary close-modal">Importar</button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('providerImport_modal_hide', event => {
        $('#providerImportModal').modal('hide');
    });
</script>