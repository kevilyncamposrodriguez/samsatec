
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="exonerationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Creación de exoneracion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal form-bordered">
                    @csrf

                    <!-- begin form-group -->
                    <div class="form-group row m-b-2">
                        <label class="col-lg-2 col-form-label">Tipo de documento: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <select class="form-control" data-parsley-required="true" name="id_type_document_exoneration" id="id_type_document_exoneration" wire:model="id_type_document_exoneration">
                                @foreach($allTypeDocuments as $type_document)                                       
                                <option style="color: black;" value="{{ $type_document->id }}">{{ $type_document->document }}</option>
                                @endforeach
                            </select>
                            @error('id_type_document_exoneration') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>  
                        <label class="col-lg-2 col-form-label">Número: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="document_number" wire:model="document_number" />
                            @error('document_number') <span class="text-red-500">{{ $message }}</span>@enderror                        
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Descripción: <span class="text-danger"></span></label>
                        <div class="col-9">
                            <input class="form-control" type="text" name="description" wire:model="description" />
                            @error('description') <span class="text-red-500">{{ $message }}</span>@enderror                        
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Institución: <span class="text-danger"></span></label>
                        <div class="col-9">
                            <input class="form-control" type="text" name="institutional_name" wire:model="institutional_name" />
                            @error('institutional_name') <span class="text-red-500">{{ $message }}</span>@enderror                        
                        </div>
                    </div>
                    <!-- end form-group -->
                    <!-- begin form-group -->
                    <div class="form-group row m-b-2">
                        <label class="col-lg-2 col-form-label">Fecha de Documento: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input class="form-control" type="datetime-local" name="date" wire:model="date" step="1" />                        
                            @error('date') <span class="text-red-500">{{ $message }}</span>@enderror 
                        </div>
                        <label class="col-lg-2 col-form-label">% exonerado: <span class="text-danger"></span></label>
                        <div class="col-4">
                            <input class="form-control" type="number" name="exemption_percentage" wire:model="exemption_percentage" />
                            @error('exemption_percentage') <span class="text-red-500">{{ $message }}</span>@enderror                        
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
    window.addEventListener('exoneration_modal_hide', event => {
        $('#exonerationModal').modal('hide');
    });
</script>
