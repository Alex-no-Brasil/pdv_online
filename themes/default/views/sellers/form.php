<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('update_info'); ?></h3>
                </div>
                <div class="box-body">
                    <?php echo form_open("sellers/save", 'class="validation"'); ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="code"><?= $this->lang->line("name"); ?></label>
                            <?= form_input('name', set_value('name', $name), 'class="form-control" id="name" required'); ?>
                        </div>

                        <div class="form-group">
                            <label>Loja</label>
                            <?php
                            $options[''] = lang("select");
                            foreach ($lojas as $loja) {
                                $options[$loja->cod] = $loja->nome;
                            }

                            ?>
<?= form_dropdown('cod_loja', $options, $cod_loja, 'class="form-control select2 tip" id="cod_loja"  required="required"'); ?>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <?php
                            $options = [
                                '' => lang("select"),
                                'A' => 'Ativo',
                                'I' => 'Inativo',
                                'F' => 'Férias',
                                'L' => 'Licença médica'
                            ];

                            ?>
<?= form_dropdown('status', $options, $status, 'class="form-control select2 tip" id="status"  required="required"'); ?>
                        </div>
                        <div class="form-group">
<?php echo form_submit('save_sellers', 'Salvar', 'class="btn btn-primary"'); ?>
                        </div>
                    </div>
<?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
