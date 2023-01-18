<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">NFC-e</h4>
</div>

<?php echo form_open("notaFiscal/salvar_nfce", ['onsubmit' => 'return oficina_submit(this)']); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Empresa", "Empresa") ?>
                <?= form_input('id_empresa', set_value('id_empresa', $id_empresa), 'class="form-control" id="id_empresa" required  '); ?>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Cliente", "Cliente") ?>
                <?= form_input('id_cliente', set_value('id_cliente', $id_cliente), 'class="form-control" id="status" required  '); ?>
            </div>
        </div>

        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Status", "Status") ?>
                <?= form_input('status', set_value('status', $status), 'class="form-control" id="status" required  '); ?>
            </div>
        </div>
    </div>
    <br>

    <div class="modal-footer">
        <input type="hidden" name="id" value="<?= $id ?>">
        <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; CONFIRMAR</button>
    </div>
    <?= form_close(); ?>