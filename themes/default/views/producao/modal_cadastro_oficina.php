
<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Adicionar oficina</h4>
</div>

<?php echo form_open("producao/salvar_oficina", ['onsubmit' => 'return oficina_submit(this)']); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Oficina", "Oficina") ?>
                <?= form_input('nome', set_value('nome', $nome), 'class="form-control" id="nome" required placeholder="Nome da oficina" '); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Prefixo", "Prefixo") ?>
                <?= form_input('prefixo', set_value('prefixo', $prefixo), 'class="form-control" id="prefixo" required placeholder="Prefixo oficina"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Sequência", "Sequência") ?>
                <?= form_input('sequencia', set_value('sequencia', $sequencia), 'class="form-control" id="sequencia" placeholder="00"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Telefone", "Telefone") ?>
                <?= form_input('telefone', set_value('telefone', $telefone), 'class="form-control" id="telefone" required  placeholder="(11)999999999"  onkeypress="mask(this, mtelefone);" onblur="mask(this, mtelefone)";'); ?>
            </div>
        </div>
        
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Endereço", "Endereço") ?>
                <?= form_input('endereco', set_value('endereco', $endereco), 'class="form-control" id="endereco" required placeholder="Localidade"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Número", "Número") ?>
                <?= form_input('numero', set_value('numero', $numero), 'class="form-control" id="numero" required placeholder="nº" '); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Complemento", "Complemento") ?>
                <?= form_input('complemento', set_value('complemento', $complemento), 'class="form-control" id="complemento" placeholder="Ex: Digite complemento se houver"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Bairro", "Bairro") ?>
                <?= form_input('bairro', set_value('bairro', $bairro), 'class="form-control" id="bairro" required placeholder="Bairro"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Cidade", "Cidade") ?>
                <?= form_input('cidade', set_value('cidade', $cidade), 'class="form-control" id="cidade" required placeholder="Cidade"'); ?>
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
        <!--
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Maq. Retas", "Maq. Retas") ?>
                <?= form_input('maq_retas', set_value('maq_retas', $maq_retas), 'class="form-control" id="maq_retas" required placeholder="99" '); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Maq. Overloque", "Maq. Overloque") ?>
                <?= form_input('maq_overloque', set_value('maq_overloque', $maq_overloque), 'class="form-control" id="maq_overloque" required placeholder="99" '); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Maq. Galoneira", "Maq. Galoneira") ?>
                <?= form_input('maq_galoneira', set_value('maq_galoneira', $maq_galoneira), 'class="form-control" id="maq_galoneira" required placeholder="99" '); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Maq. Passadoria", "Maq. Passadoria") ?>
                <?= form_input('maq_passadoria', set_value('maq_passadoria', $maq_passadoria), 'class="form-control" id="maq_passadoria" required placeholder="99" '); ?>
            </div>
        </div>
        -->
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Funcionários", "Funcionários") ?>
                <?= form_input('maq_funcionarios', set_value('maq_funcionarios', $maq_funcionarios), 'class="form-control" id="maq_funcionarios" required placeholder="99" '); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <label>Banco</label>
                <input type="text" name="banco_nome" class="form-control" value="<?=$banco_nome?>">
            </div>
        </div>
        <div class="col-xs-2">
            <div class="form-group">
                <label>Agência</label>
                <input type="text" name="banco_agencia" class="form-control" value="<?=$banco_agencia?>">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label>Conta</label>
                <input type="text" name="banco_conta" class="form-control" value="<?=$banco_conta?>">
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <label>Tipo Conta</label>
                <select name="banco_conta_tipo" class="form-control" id="banco_conta_tipo">
                    <option>Conta corrente</option>
                    <option>Conta poupança</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label>Titular</label>
                <input type="text" name="banco_titular" class="form-control" value="<?=$banco_titular?>">
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <label>CPF/CNPJ</label>
                <input type="text" name="cpf_cnpj" class="form-control" value="<?=$cpf_cnpj?>">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label>Notas</label>
                <textarea id="nota" name="nota" rows="5" class="form-control" placeholder="Observações"><?= $nota ?></textarea>
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
        
    $('#banco_conta_tipo').val('<?= $banco_conta_tipo ?>');

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
