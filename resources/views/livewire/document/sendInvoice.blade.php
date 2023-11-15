<!-- #modal-addDocument -->
<div wire:ignore.self class="modal fade" id="sendInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" name="send_document" id="send_document" class="form-horizontal form-bordered">
                    @csrf

                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Documento a enviar: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="text" disabled name="key_send" id="key_send" data-parsley-required="true" class="form-control" placeholder="Clave de documento a enviar" wire:model="key_send"/>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">Envia a: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="mail_send" id="mail_send" data-parsley-required="true" class="form-control" placeholder="Envia a esta direccion" wire:model="mail_send"/>
                        </div>
                    </div>
                    <!-- begin form-group -->
                    <div class="form-group row m-b-10">
                        <label class="col-lg-3 col-form-label">CC a: <span class="text-danger"></span></label>
                        <div class="col-lg-9 col-xl-9">
                            <input type="email" name="cc_mail" id="cc_mail" class="form-control" placeholder="Enviar copia a..." wire:model="cc_mail"/>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="button" wire:click.prevent="sendInvoice()" onclick="this.disabled=true;" class="btn btn-dark">Enviar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<script>
     window.addEventListener('sendInvoice_modal_hide', event => {
        $('#sendInvoiceModal').modal('hide');
    });
</script>