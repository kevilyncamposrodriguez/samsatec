
<!-- Modal -->
<div wire:ignore.self class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center" >Importar Comprobante </h4> 
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="importD_v" wire:submit.prevent="importDocs" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Clave: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input wire:model="key" class="form-control" type="number"  name="key" id="key"  min="50" data-parsley-required="true"/>
                        </div>
                        @error('key') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">XML Factura: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input wire:model="xmlDoc" type="file" accept="text/xml" name="xmlDoc" class="form-control" />
                        </div>
                        @error('xmlDoc') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">XML Respuesta: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input wire:model="xmlrDoc" type="file" accept="text/xml" name="xmlrDoc" class="form-control" />
                        </div>
                        @error('xmlrDoc') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">PDF Factura: <span class="text-danger"></span></label>
                        <div class="col-lg-9">
                            <input wire:model="pdfDoc"  type="file" accept="application/pdf" name="pdfDoc" id="pdfDoc" class="form-control" />
                        </div>
                        @error('pdfDoc') <span class="text-red-500">{{ $message }}</span>@enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-dark" data-dismiss="modal">Cerrar</a>
                <button type="submit" form="importD_v"  class="btn btn-primary">Importar</button>
            </div>
        </div>
    </div>
</div>
