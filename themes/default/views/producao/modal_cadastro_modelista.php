
<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Modelista</h4>
</div>

<?php echo form_open("producao/salvar_modelista"); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Nome", "Nome") ?>
                <?= form_input('nome', set_value('nome', $nome), 'class="form-control" required placeholder="Nome" '); ?>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Telefone", "Telefone") ?>
                <?= form_input('telefone', set_value('telefone', $telefone), 'class="form-control" required placeholder="(11)99999-9999"  onkeypress="mask(this, mtelefone);" onblur="mask(this, mtelefone)";'); ?>
            </div>
        </div>
        
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Endereço","Endereço") ?>
                <?= form_input('endereco', set_value('endereco', $endereco), 'class="form-control" required placeholder="Rua exemplo"'); ?>
            </div>
        </div>
        
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Número","Número") ?>
                <?= form_input('numero', set_value('numero', $numero), 'class="form-control" required placeholder="nº"'); ?>
            </div>
        </div>
        
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Complemento","Complemento") ?>
                <?= form_input('complemento', set_value('complemento', $complemento), 'class="form-control" placeholder="Exemplo: prox ao mercado"'); ?>
            </div>
        </div>
        
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Bairro","Bairro") ?>
                <?= form_input('bairro', set_value('bairro', $bairro), 'class="form-control" required placeholder="bairro"'); ?>
            </div>
        </div>
        
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Cidade","Cidade") ?>
                <?= form_input('cidade', set_value('cidade', $cidade), 'class="form-control" required placeholder="cidade"'); ?>
            </div>
        </div>
        
        <div class="col-xs-4">
            <div class="form-group">
                <label>UF</label>
                <select class="form-control" id="uf" name="uf" required>
                    <?php
                    $root_theme = getcwd() . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'default' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;

                    include($root_theme . 'estados.php');
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="id" value="<?= $id ?>">
    <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; CONFIRMAR</button>
</div>
<?= form_close(); ?>


<script>
    
    $('#uf').val('<?= $uf ?>');
    
    function mask(o, f) {
        setTimeout(function () {
            
            var v = mtelefone(o.value);
            
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mtelefone(v) {
        
        var r = v.replace(/\D/g, "");
        
        r = r.replace(/^0/, "");
        
        if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
        } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
        } else {
            r = r.replace(/^(\d*)/, "($1");
        }
        
        return r;
    }
</script>
