<!-- #modal-addDocument -->
<div wire:ignore.self class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center"> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

            </div>
            <div class="modal-body col-lg-12" style="background-color: #E5E4E3;">
                <label class="col-form-label" for="notes">Notas:</label>
                <textarea id="notes" class="form-control" name="notes" rows="4" cols="50" wire:model="notes"></textarea>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
                <button type="button" wire:click.prevent="saveNotes()" onclick="this.disabled=true;" class="btn btn-btn btn-secondary close-btn">Guardar</button>

            </div>
        </div>
    </div>
</div>
<!-- #modal-addDocument -->
<script>
    window.addEventListener('sendReport_modal_hide', event => {
        $('#sendReportModal').modal('hide');
    });
</script>