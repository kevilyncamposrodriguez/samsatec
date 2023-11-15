@push('css')
<link href="/assets/plugins/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
@endpush
<!-- Modal -->
<div wire:ignore.self class="modal fade " id="documentImportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importar documentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="import_document_form" wire:submit.prevent="importDocuments" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <!-- begin form-group -->
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Archivo: <span class="text-danger"></span></label>
                        <div class="col-lg-9" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <input type="file" name="file_import" accept=".zip" wire:model="file_import" class="form-control" />
                            <div x-show="isUploading">
                                <progress max="100" x-bind:value="progress"></progress>
                            </div>
                        </div>
                        @error('file_import') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <!-- end form-group -->
                    <span>Guarde todos los archivos xml a importar en un archivo comprimido .zip</span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="import_document_form" class="btn btn-primary close-modal">Importar</button>
            </div>
        </div>
    </div>
</div>