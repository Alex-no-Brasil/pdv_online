<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Transporadora</h4>
</div>

<?php echo form_open("notaFiscal/salvar_transportadora", ['onsubmit' => 'return oficina_submit(this)']); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Razão Social", "Razão Social") ?>
                <?= form_input('razaoSocial', set_value('razaoSocial', $razaoSocial), 'class="form-control" id="razaoSocial" required  placeholder="Razão social da empresa" '); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("CNPJ", "CNPJ") ?>
                <?= form_input('cnpj', set_value('cnpj', $cnpj), 'class="form-control" id="cnpj" required  placeholder="99999999000199" '); ?>
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
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Complemento", "Complemento") ?>
                <?= form_input('complemento', set_value('complemento', $complemento), 'class="form-control" id="complemento" placeholder="Ex: Digite complemento se houver"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Bairro", "Bairro") ?>
                <?= form_input('bairro', set_value('bairro', $bairro), 'class="form-control" id="bairro" required placeholder="Bairro"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Cidade", "Cidade") ?>
                <?= form_input('cidade', set_value('cidade', $cidade), 'class="form-control" id="cidade" required placeholder="Cidade"'); ?>
            </div>
        </div>
        <div class="col-xs-2">
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
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Cep", "Cep") ?>
                <?= form_input('cep', set_value('cep', $cep), 'class="form-control" id="cep" required placeholder="00000000"'); ?>
            </div>
        </div>
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Telefone", "Telefone") ?>
                <?= form_input('telefone', set_value('telefone', $telefone), 'class="form-control" id="telefone" required  placeholder="(11)999999999"  onkeypress="mask(this, mtelefone);" onblur="mask(this, mtelefone)";'); ?>
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
