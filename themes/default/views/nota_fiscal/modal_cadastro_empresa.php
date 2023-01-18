<div class="modal-header bg-success">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">Empresa</h4>
</div>

<?php echo form_open("notaFiscal/salvar_empresa", ['onsubmit' => 'return oficina_submit(this)']); ?>
<div class="modal-body">
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <?= lang("Empresa", "Empresa") ?>
                <?= form_input('nome', set_value('nome', $nome), 'class="form-control" id="nome" required placeholder="Nome da empresa" '); ?>
            </div>
        </div>
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
        <div class="col-xs-3">
            <div class="form-group">
                <?= lang("Inscrição Estadual", "Inscrição Estadual") ?>
                <?= form_input('ie', set_value('ie', $ie), 'class="form-control" id="ie" required  placeholder="000000000" '); ?>
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
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Certificado senha", "Certificado senha") ?>
                <?= form_input('certSenha', set_value('certSenha', $certSenha), 'class="form-control" id="senha certificado" required placeholder="**********"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Série DANF", "Série DANF") ?>
                <?= form_input('serie_danf', set_value('serie_danf', $serie_danf), 'class="form-control" id="serie_danf" required placeholder="série DANF"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Número DANF", "Número DANF") ?>
                <?= form_input('numero_danf', set_value('numero_danf', $numero_danf), 'class="form-control" id="numero_danf" required placeholder="número DANF"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Série NFC-e", "Série NFC-e") ?>
                <?= form_input('serie_nfce', set_value('serie_nfce', $serie_nfce), 'class="form-control" id="serie_nfce" required placeholder="série NFC-e"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Número NFC-e", "Número NFC-e") ?>
                <?= form_input('numero_nfce', set_value('numero_nfce', $numero_nfce), 'class="form-control" id="numero_nfce" required placeholder="número NFC-e"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("Token NFC-e", "Token NFC-e") ?>
                <?= form_input('token_nfce', set_value('token_nfce', $token_nfce), 'class="form-control" id="token_nfce" required placeholder="Token NFC-e"'); ?>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang("ID Token NFC-e", "ID Token NFC-e") ?>
                <?= form_input('id_token_nfce', set_value('id_token_nfce', $id_token_nfce), 'class="form-control" id="id_token_nfce" required placeholder="ID do Token NFC-e"'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4">
            <div class="form-group">
                <?= lang('Certificado', 'Certificado'); ?>
                <input type="file" name="certArquivo" id="image">
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
