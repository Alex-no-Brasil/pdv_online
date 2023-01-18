<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Estoque</h4>
</div>

<?php echo form_open_multipart("notaFiscal/salvar_estoque", ['onsubmit' => 'return oficina_submit(this)']); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label>Origem</label>
                <select class="form-control" id="origem" name="origem" required>
                    <option value="0">Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8</option>
                    <option value="1">Estrangeira - Importação direta, exceto a indicada no código 6</option>
                    <option value="2">Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7</option>
                    <option value="3">Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% (quarenta por cento) e inferior ou igual a 70% (setenta por cento)</option>
                    <option value="4">Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam o Decreto-Lei no 288/67, e as Leis nos 8.248/91, 8.387/91, 10.176/01 e 11.484/07</option>
                    <option value="5">Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40% (quarenta por cento)</option>
                    <option value="6">Estrangeira - Importação direta, sem similar nacional, constante em lista de Resolução CAMEX e gás natural</option>
                    <option value="7">Estrangeira - Adquirida no mercado interno, sem similar nacional, constante em lista de Resolução CAMEX e gás natural</option>
                    <option value="8">Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70% (setenta por cento)</option>
                </select>
            </div>
        </div>  
        
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang('XML', 'XML'); ?>
                <input type="file" name="xml" id="xml">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button id="btnConfirmar" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; CONFIRMAR</button>
    </div>
    <?= form_close(); ?>

    <script>
        
    </script>
